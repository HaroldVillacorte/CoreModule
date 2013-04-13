<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Default Controller Module
 *
 * Serves as a basic boilerplate for develeping with CoreModule.
 *
 * @package CoreModule
 * @subpackage Modules
 * @category Core
 * @author Harold Villacorte
 */
class Core_module_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();

        // Load the configs.
        $this->config->load('core_module/core_module_config');
    }

    /**
     * This model should be autolaoded.  Additionally the $data array should be
     * first set to site_info().  for example:
     * self:$data = $this->core_model->site_info().
     * Then subsequently anything can be added to the array.
     *
     * @return array $data An array containing site wide information.
     */
    public function site_info()
    {
        $data = array(
            // The name of the Website or application.
            'site_name'        => $this->config->item('core_module_site_name'),
            // The site description te be echoed in the head meta descrition.
            'site_description' => $this->config->item('core_module_site_description'),
            // Sets the $template_url variable available application-wide.
            'template_url'     => base_url() . $this->config->item('core_module_template_url'),
            // Sets the $css_url variable available application-wide.  This is not
            // integrated with the Asset loader module.
            'css_url'          => base_url() . $this->config->item('core_module_css_url'),
            // Sets the $js_url variable available application-wide.  This is not
            // integrated with the Asset loader module.
            'js_url'           => base_url() . $this->config->item('core_module_js_url'),
            // Sets the $img_url variable available application-wide.  This is not
            // integrated with the Asset loader module.
            'img_url'          => base_url() . $this->config->item('core_module_img_url'),
            // Sets the $aset_path variable available application-wide.  This is not
            // integrated with the Asset loader module.
            'asset_path'       => FCPATH . $this->config->item('core_module_asset_path'),
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
    public function setting_set($name = NULL, $setting = NULL)
    {
        // Escape, type case, and serialize.
        $name = $this->db->escape_str($name);
        $setting = (is_int($setting) || is_numeric($setting)) ? (int) $setting : $setting;
        $setting = serialize($this->db->escape_str($setting));

        // Set the post array.
        $array = array(
            'name' => $name,
            'setting' => $setting,
        );

        // Check if the setting exists.
        if (!$this->setting_get($name))
        {
            // Insert if setting does not exist.
            $query = $this->db->insert('core_settings', $array);
            $result = ($this->db->insert_id());
        }
        else
        {
            // Update if setting exists.
            $query = $this->db
                           ->where('name', $name)
                           ->update('core_settings', $array);
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
    public function setting_get($name = NULL)
    {
        // Excape.
        $name = $this->db->escape_str($name);

        // Runf the query.
        $result = $this->db
                       ->select('setting')
                       ->get_where('core_settings', array('name' => $name), 1);

        // Get result.
        $row = $result->row();

        // Get the setting field and unserialize.
        $setting = ($result->num_rows() > 0) ? unserialize($row->setting) : NULL;

        // Reurn the setting.
        return ($result->num_rows() > 0) ? $setting : FALSE;
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
            ->select($table . '.id, is_front, published, author, created, last_edit,
                last_edit_username, slug, title, body, template')
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
            ->select($table . '.id, is_front, published, author, created, last_edit,
                last_edit_username, slug, title, body, template')
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
            ->select($table . '.id, is_front, published, author, created, last_edit,
                last_edit_username, slug, title, body, template')
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
        // Sanitize.
        $post = prep_post($post);

        // Set and unset.
        unset($post['submit']);
        $post['author'] = $this->session->userdata('username');
        $post['created'] = time();

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
        $post['last_edit'] = time();
        $post['last_edit_username'] = $this->session->userdata('username');
        $post['is_front'] = ($post['is_front']) ? $post['is_front'] : 0;
        $post['published'] = ($post['published']) ? $post['published'] : 0;

        // Sanitize.
        $post = prep_post($post);

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
