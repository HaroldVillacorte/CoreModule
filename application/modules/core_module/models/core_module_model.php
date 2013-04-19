<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * The CoreModule model.
 */
class Core_module_model extends CI_Model
{

    /*
     * The CoreModule model constructor.
     */
    function __construct()
    {
        parent::__construct();

        // Load the configs.
        $this->config->load('core_module/core_module_config');
    }

    /**
     * This model should be autolaoded.
     *
     * @return array $data An array containing site wide information.
     */
    public function site_info()
    {
        $data = array(
            'site_name'        => $this->variable_get('site_name'),
            'site_description' => $this->variable_get('site_description'),
            // This file is required by asset loader module. Do not delete.  File
            // can be edited as long as they match what is in the asset tempalte
            // directory.
            'scripts'          => $this->config->item('core_module_scripts'),
            // This file is required by asset loader module. Do not delete.  File
            // can be edited as long as they match what is in the asset tempalte
            // directory.
            'stylesheets'     => $this->config->item('core_module_stylesheets'),
        );
        return $data;
    }

    /**
     * Set a setting.
     *
     * @param string $name
     * @param mixed $setting
     * @return boolean
     */
    public function variable_set($name = NULL, $variable = NULL)
    {
        // Escape, type case, and serialize.
        $name = $this->db->escape_str($name);
        $variable = (is_int($variable) || is_numeric($variable)) ? (int) $variable : $variable;
        $variable = serialize($this->db->escape_str($variable));

        // Set the post array.
        $array = array(
            'name' => $name,
            'variable' => $variable,
        );

        // Check if the setting exists.
        if ((!$this->variable_get($name) && $this->variable_get($name) != 0) || $this->variable_get($name) == NULL)
        {
            // Insert if setting does not exist.
            $query = $this->db->insert('core_variables', $array);
            $result = ($this->db->insert_id());
        }
        else
        {
            // Update if setting exists.
            $query = $this->db
                           ->where('name', $name)
                           ->update('core_variables', $array);
            $result = ($query);
        }

        // Clear the database cache.
        $this->db->cache_delete_all();

        // Return result.
        return $result;
    }

    /**
     * Get a setting.
     *
     * @param string $name
     * @return mixed
     */
    public function variable_get($name = NULL)
    {
        // Excape.
        $name = $this->db->escape_str($name);

        // Runf the query.
        $result = $this->db
                       ->select('variable')
                       ->get_where('core_variables', array('name' => $name), 1);

        // Get result.
        $row = $result->row();

        // Get the setting field and unserialize.
        $variable = ($result->num_rows() > 0) ? unserialize($row->variable) : NULL;

        // Reurn the setting.
        return ($result->num_rows() > 0) ? $variable : FALSE;
    }


    /**
     * Add a category.
     *
     * @param array $post
     * @return integer
     */
    public function category_add($post = array())
    {
        // Set and unset.
        unset($post['submit']);

        // Sanitize and type cast.
        $post['level'] = (int) $post['level'];
        $post['name'] = $this->db->escape_str($post['name']);

        // Run the query.
        $this->db->insert('core_categories', $post);

        // Get the insert id.
        $id = $this->db->insert_id();

        // Clear the database cache.
        $this->db->cache_delete_all();

        // Return the result.
        return ($id) ? $id : FALSE;
    }

    /**
     * Edit a category.
     *
     * @param array $post
     * @return integer
     */
    public function category_edit($post = array())
    {
        // Set and unset.
        unset($post['submit']);

        // Sanitize and type cast.
        $post['level'] = (int) $post['level'];
        $post['name'] = $this->db->escape_str($post['name']);

        // Run the query.
        $result = $this->db
            ->where('id', (int) $post['id'])
            ->update('core_categories', $post);

        // Clear the database cache.
        $this->db->cache_delete_all();

        // Return the result.
        return ($result);
    }

    /**
     * Delete a category.
     * @param integer $id
     * @return boolean
     */
    public function category_delete($id = NULL)
    {
        // Run the query.
        $this->db->delete('core_categories', array('id' => (int) $id), 1);

        // Clear the database cache.
        $this->db->cache_delete_all();

        // Return the result.
        return ($this->db->affected_rows() == 1) ? TRUE : FALSE;
    }

    /**
     * Find a category.
     *
     * @param integer $id
     * @return object
     */
    public function category_find($id = NULL)
    {
        // Run the query.
        $category = $this->db
            ->select('id, level, name')
            ->get_where('core_categories', array('id' => (int) $id), 1);

        // Return the result.
        return ($category->num_rows() == 1) ? $category->row() : FALSE;
    }

    /**
     * Find all categories.
     *
     * @return object
     */
    public function category_find_level($level = NULL, $data_type = 'object')
    {
        // Run the query.
        $categories = $this->db
            ->select('id, level, name')
            ->where('level', (int) $level)
            ->get('core_categories');

        // Return the results
        switch ($data_type)
        {
            case 'object':
                return ($categories->num_rows() > 0) ? $categories->result() : FALSE;
                break;
            case 'array':
                return ($categories->num_rows() > 0) ? $categories->result_array() : FALSE;
                break;
            case 'row':
                return ($categories->num_rows() > 0) ? $categories->row() : FALSE;
                break;
        }
    }

    /**
     * Find all categories.
     *
     * @return object
     */
    public function category_count()
    {
        // Run the query.
        $categories = $this->db
            ->select('id')
            ->get('core_categories');

        return $categories->num_rows();
    }

    /**
     * Get limit offset of categories.
     *
     * @param integer $limit
     * @param integer $offset
     * @param string $data_type
     * @return mixed
     */
    public function category_find_limit_offset($limit = NULL, $offset = NULL, $data_type = 'object')
    {
        // Run the query.
        $categories = $this->db
            ->select('id, level, name')
            ->get('core_categories', (int) $limit, (int) $offset);

        // Return the results
        switch ($data_type)
        {
            case 'object':
                return ($categories->num_rows() > 0) ? $categories->result() : FALSE;
                break;
            case 'array':
                return ($categories->num_rows() > 0) ? $categories->result_array() : FALSE;
                break;
            case 'row':
                return ($categories->num_rows() > 0) ? $categories->row() : FALSE;
                break;
        }
    }

    /**
     * Find a page.
     *
     * @param string $by
     * @param mixed $identifier
     * @return object
     */
    public function page_find($table = 'core_pages', $by = 'id', $identifier = NULL)
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
            ->select($table . '.id, category, is_front, published, permissions, author,
                created, last_edit, last_edit_username, slug, title, body, template')
            ->where($table . '.' . $by, $identifier)
            ->get($table, 1);

        // Return result.
        return ($query->num_rows() == 1) ? $query->row() : FALSE;
    }

    /**
     * Find all pages.
     *
     * @return array
     */
    public function page_find_all($table = 'core_pages', $data_type = 'object')
    {
        // Run the query.
        $query = $this->db
            ->select($table . '.id, category, is_front, published, permissions, author,
                created, last_edit, last_edit_username, slug, title, body, template')
            ->get($table);

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
    public function page_find_limit_offset($table = 'core_pages', $limit = 1, $offset = 0, $data_type = 'object')
    {
        // Run the query.
        $query = $this->db
            ->select($table . '.id, core_categories.name AS category, is_front,
                published, permissions, author, created, last_edit, last_edit_username,
                slug, title, body, template')
            ->join('core_categories', $table . '.category = core_categories.id')
            ->order_by('category')
            ->get($table, (int) $limit, (int) $offset);

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
    public function page_add($table = 'core_pages', $post = array())
    {
        // Set and unset.
        unset($post['submit']);
        $post['permissions'] = implode(',', $post['permissions']);
        $post['author'] = $this->session->userdata('username');
        $post['created'] = time();

        // Sanitize.
        $post = prep_post($post);

        // Run the query.
        $this->db->insert($table, $post);

        // Get the page id.
        $id = $this->db->insert_id();

        // Clear the database cache.
        $this->db->cache_delete_all();

        // Return result.
        return ($id) ? $id : FALSE;
    }

    /**
     * Edit a page.
     *
     * @param array $post
     * @return boolean
     */
    public function page_edit($table = 'core_pages', $post = array())
    {
        // Set and unset.
        unset($post['submit']);
        $post['permissions'] = implode(',', $post['permissions']);
        $post['last_edit'] = time();
        $post['last_edit_username'] = $this->session->userdata('username');
        $post['is_front'] = ($post['is_front']) ? $post['is_front'] : 0;
        $post['published'] = ($post['published']) ? $post['published'] : 0;

        // Sanitize.
        $post = prep_post($post);

        // Reset an unset permissions to empty string.
        $post['permissions'] = (isset($post['permissions'])) ? $post['permissions'] : '';

        // Run the query.
        $result = $this->db->where('id', (int) $post['id'])->update($table, $post);

        // Clear the database cache.
        $this->db->cache_delete_all();

        // Return result.
        return ($result) ? TRUE : FALSE;
    }

    /**
     * Delete a page.
     *
     * @param integer $id
     * @return boolean
     */
    public function page_delete($table = 'core_pages', $id = NULL)
    {
        // Run the query.
        $this->db->delete($table, array('id' => (int) $id), 1);

        // Clear the database cache.
        $this->db->cache_delete_all();

        // Return the result.
        return ($this->db->affected_rows() == 1) ? TRUE : FALSE;
    }

}
/* End of file core_module_model.php */
