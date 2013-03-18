<?php if (!defined ('BASEPATH')) exit ('No direct script access allowed');

class Core_menu_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();

        // Load the database class.
        $this->load->database();
    }

    /**
     * Find a menu.
     *
     * @param string $by
     * @param mixed $identifier
     * @return object
     */
    public function menu_find($by = 'id', $identifier = NULL)
    {
        // Sanitize.
        $by = $this->db->escape_str($by);
        if (is_string($identifier))
        {
            $identifier = $this->db->escape_str($identifier);
        }
        elseif (is_int($identifier) || is_numeric($identifier))
        {
            $identifier = (int) $identifier;
        }

        // Run the query.
        $query = $this->db
            ->select('id, menu_name, description')
            ->where($by, $identifier)
            ->get('core_menus', 1);

        // Return result.
        return ($query->num_rows() == 1) ? $query->row() : FALSE;
    }

    /**
     * Find all menus.
     *
     * @return array
     */
    public function menu_find_all($data_type = 'object')
    {
        // Run the query.
        $query = $this->db
            ->select('id, menu_name, description')
            ->get('core_menus');

        // Choose data type.
        switch ($data_type)
        {
           case 'object':
               $result_set = $query->result();
               break;
           case 'array':
               $result_set = $query->result_array();
               break;
        }

        // Return result.
        return ($query->num_rows() > 0) ? $result_set : FALSE;
    }

    /**
     * Find all menus with limit and offset.
     *
     * @return array
     */
    public function menu_find_limit_offset($limit = 1, $offset = 0, $data_type = 'object')
    {
        // Run the query.
        $query = $this->db
            ->select('id, menu_name, description')
            ->get('core_menus', (int) $limit, (int) $offset);

        // Choose data type.
        switch ($data_type)
        {
           case 'object':
               $result_set = $query->result();
               foreach ($result_set as $value)
               {
                   $value->links = $this->menu_link_find('parent_menu_id', $value->id);
               }

               break;
           case 'array':
               $result_set = $query->result_array();
               foreach ($result_set as $value)
               {
                   $value['links'] = $this->menu_link_find('parent_menu_id', $value->id);
               }
               break;
        }

        // Return result.
        return ($query->num_rows() > 0) ? $result_set : FALSE;
    }

    /**
     * Add a menu.
     *
     * @param array $post
     * @return integer
     */
    public function menu_add($post = array())
    {
        // Sanitize.
        $post = $this->core_module_library->prep_post($post);

        // Set and unset.
        unset($post['submit']);

        // Run the query.
        $this->db->insert('core_menus', $post);

        // Get the menu id.
        $id = $this->db->insert_id();

        // Return result.
        return ($id) ? $id : FALSE;
    }

    /**
     * Edit a menu.
     *
     * @param array $post
     * @return boolean
     */
    public function menu_edit($post = array())
    {
        // Set and unset.
        unset($post['submit']);

        // Sanitize.
        $post = $this->core_module_library->prep_post($post);

        // Run the query.
        $result = $this->db->where('id', (int) $post['id'])->update('core_menus', $post);

        // Return result.
        return ($result) ? TRUE : FALSE;
    }

    /**
     * Delete a menu.
     *
     * @param integer $id
     * @return boolean
     */
    public function menu_delete($id = NULL)
    {
        // Delete the menu.
        $this->db->delete('core_menus', array('id' => (int) $id), 1);

        // If menu delete was successful.
        if ($this->db->affected_rows() == 1)
        {
            $count = count($this->db->select('id')
                ->get_where('core_menu_links', array('parent_menu_id' => (int) $id))
                ->result_array());

            // Delete all associated links.
            $this->db->delete('core_menu_links', array('parent_menu_id' => (int) $id));
            return ($this->db->affected_rows() == $count) ? TRUE : FALSE;
        }
        else
        {
            // Could not delete menu.
            return FALSE;
        }
    }

    /**
     * Find a menu link.
     *
     * @param string $by
     * @param mixed $identifier
     * @return object
     */
    public function menu_link_find($by = 'id', $identifier = NULL, $get_row = FALSE)
    {
        // Sanitize.
        $by = $this->db->escape_str($by);
        if (is_string($identifier))
        {
            $identifier = $this->db->escape_str($identifier);
        }
        elseif (is_int($identifier) || is_numeric($identifier))
        {
            $identifier = (int) $identifier;
        }

        // Run the query.
        $query = $this->db
            ->select('id, parent_menu_id, external, title, text, link, permissions')
            ->get_where('core_menu_links', array($by => $identifier));

        // Return result.
        if ($get_row)
        {
            return ($query->num_rows() > 0) ? $query->row() : FALSE;
        }
        else
        {
            return ($query->num_rows() > 0) ? $query->result() : FALSE;
        }
    }

    /**
     * Add a menu link.
     *
     * @param array $post
     * @return integer
     */
    public function menu_link_add($post = array())
    {
        // Sanitize.
        $post = $this->core_module_library->prep_post($post);

        // Remove white space from permissions string.
        $post['permissions'] = $this->core_module_library->strip_whitespace($post['permissions']);

        // Set and unset.
        unset($post['submit']);

        // Run the query.
        $this->db->insert('core_menu_links', $post);

        // Get the menu id.
        $id = $this->db->insert_id();

        // Return result.
        return ($id) ? $id : FALSE;
    }

    /**
     * Edit a menu link.
     *
     * @param array $post
     * @return boolean
     */
    public function menu_link_edit($post = array())
    {
        // Set and unset.
        unset($post['submit']);
        $post['external'] = (!isset($post['external'])) ? 0 : $post['external'];

        // Remove white space from permissions string.
        $post['permissions'] = $this->core_module_library->strip_whitespace($post['permissions']);

        // Sanitize.
        $post = $this->core_module_library->prep_post($post);

        // Run the query.
        $result = $this->db->where('id', (int) $post['id'])->update('core_menu_links', $post);

        // Return result.
        return ($result) ? TRUE : FALSE;
    }

}

/* End of file core_menus_model.php */
