<?php if (!defined ('BASEPATH')) exit ('No direct script access allowed');
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
class Core_Model extends CI_Model {

  /**
   * This model should be autolaoded.  Additionally the $data array should be
   * first set to site_info().  for example:
   * self:$data = $this->core_model->site_info().
   * Then subsequently anything can be added to the array.
   *
   * @return array $data An array containing site wide information.
   */
  public function site_info() {
    $data = array(
      // The name of the Website or application.
      'site_name' => 'CI Starter',
      // Sets the $template_url variable available application-wide.
      'template_url' => base_url () . 'assets/templates/default_template/',
      // Sets the $css_url variable available application-wide.  This is not
      // integrated with the Asset loader module.
      'css_url' => base_url () . 'assets/templates/default_template/stylesheets/',
      // Sets the $js_url variable available application-wide.  This is not
      // integrated with the Asset loader module.
      'js_url' => base_url () . 'assets/templates/default_template/javascripts/',
      // Sets the $img_url variable available application-wide.  This is not
      // integrated with the Asset loader module.
      'img_url' => base_url () . 'assets/templates/default_template/images/',
      // Sets the $aset_path variable available application-wide.  This is not
      // integrated with the Asset loader module.
      'asset_path' => FCPATH . 'assets/templates/default_template',
    	// This file is required by asset loader module. Do not delete.  File
      // can be edited as long as they match what is in the asset tempalte
      // directory.
      'scripts' => array('custom.js'),
      // This file is required by asset loader module. Do not delete.  File
      // can be edited as long as they match what is in the asset tempalte
      // directory.
      'stylesheets' => array('custom.css'),
    );
    return $data;
  }
}

/* End of file core_model.php */
