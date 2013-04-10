<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Default Controller Module
 *
 * Serves as a basic boilerplate for develeping with CI Starter.
 *
 * @package CI Starter
 * @subpackage Modules
 * @category Core
 * @author Harold Villacorte
 * @link http://laughinghost.com/CI_Starter/
 */
class Core_module_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();

        // Load the configs.
        $this->config->load('core_module/core_module_config');

        // Load the databease.
        $this->load->database();
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

    public function setting_set($name = NULL, $setting = NULL)
    {
        $name = $this->db->escape_str($name);
        $setting = serialize($this->db->escape($setting));

        $array = array(
            'name' => $name,
            'setting' => $setting,
        );

        if (!$this->setting_get($name))
        {
            $result = $this->db->insert('core_settings', $array);
        }
        else
        {
            $result = $this->db
                           ->where('name', $name)
                           ->update('core_settings', array('setting' => $setting));
        }

        return ($this->db->affected_rows() > 0);
    }

    public function setting_get($name = NULL)
    {
        $name = $this->db->escape_str($name);

        $result = $this->db
                       ->select('setting')
                       ->get_where('core_settings', array('name' => $name), 1);

        $row = $result->row();
        $setting = $row->setting;

        return ($result->num_rows() > 0) ? unserialize($setting) : FALSE;
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
                last_edit_username, slug, title, body, template')
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
                last_edit_username, slug, title, body, template')
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
                last_edit_username, slug, title, body, template')
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
        $post = prep_post($post);

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
        $post = prep_post($post);

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
/* End of file core_module_model.php */
