<?php if (!defined ('BASEPATH')) exit ('No direct script access allowed');

class Core_pages_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();

        // Load the database class.
        $this->load->database();
    }

    /**
     * Find a page.
     *
     * @param string $by
     * @param mixed $identifier
     * @return object
     */
    public function page_find($by = 'id', $identifier = NULL)
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
            ->select('core_pages.id, is_front, published, username, core_pages.created, last_edit,
                last_edit_username, slug, title, teaser, body')
            ->join('core_users', 'core_users.id = core_pages.user_id')
            ->where('core_pages.' . $by, $identifier)
            ->get('core_pages', 1);

        // Return result.
        return ($query->num_rows() == 1) ? $query->row() : FALSE;
    }

    /**
     * Find all pages.
     *
     * @return array
     */
    public function page_find_all($data_type = 'object')
    {
        // Run the query.
        $query = $this->db
            ->select('core_pages.id, is_front, published, core_users.username, core_pages.created, last_edit,
                last_edit_username, slug, title, teaser, body')
            ->join('core_users', 'core_users.id = core_pages.user_id')
            ->get('core_pages');

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
     * Find all pages with limit and offset.
     *
     * @return array
     */
    public function page_find_limit_offset($limit = 1, $offset = 0, $data_type = 'object')
    {
        // Run the query.
        $query = $this->db
            ->select('core_pages.id, is_front, published, core_users.username, core_pages.created, last_edit,
                last_edit_username, slug, title, teaser, body')
            ->join('core_users', 'core_users.id = core_pages.user_id')
            ->get('core_pages', (int) $limit, (int) $offset);

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
     * Add a page.
     *
     * @param array $post
     * @return integer
     */
    public function page_add($post = array())
    {
        // Sanitize.
        $post = $this->core_module_library->prep_post($post);

        // Set and unset.
        unset($post['submit']);
        $post['user_id'] = $this->session->userdata('user_id');
        $post['created'] = time();

        // Run the query.
        $this->db->insert('core_pages', $post);

        // Get the page id.
        $id = $this->db->insert_id();

        // Return result.
        return ($id) ? $id : FALSE;
    }

    /**
     * Edit a page.
     *
     * @param array $post
     * @return boolean
     */
    public function page_edit($post = array())
    {
        // Set and unset.
        unset($post['submit']);
        $post['last_edit'] = time();
        $post['last_edit_username'] = $this->session->userdata('username');
        $post['is_front'] = ($post['is_front']) ? $post['is_front'] : 0;
        $post['published'] = ($post['published']) ? $post['published'] : 0;

        // Sanitize.
        $post = $this->core_module_library->prep_post($post);

        // Run the query.
        $result = $this->db->where('id', (int) $post['id'])->update('core_pages', $post);

        // Return result.
        return ($result) ? TRUE : FALSE;
    }

    /**
     * Delete a page.
     *
     * @param integer $id
     * @return boolean
     */
    public function page_delete($id = NULL)
    {
        // Run the query.
        $this->db->delete('core_pages', array('id' => (int) $id), 1);

        // Return the result.
        return ($this->db->affected_rows() == 1) ? TRUE : FALSE;
    }

}

/* End of file core_pages_model.php */
