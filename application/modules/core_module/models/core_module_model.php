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

}
/* End of file core_module_model.php */
