<?php if (!defined ('BASEPATH')) exit ('No direct script access allowed');

/**
 * This model needs to be autoloaded.
 * @author Harld Villcorte
 *
 */
class Default_Model extends CI_Model {

  public function site_info() {
    $data = array(
      'site_name' => 'CI Starter',
      'template_url' => base_url () . 'assets/templates/default_template/',
      'css_url' => base_url () . 'assets/templates/default_template/stylesheets/',
      'js_url' => base_url () . 'assets/templates/default_template/javascripts/',
      'img_url' => base_url () . 'assets/templates/default_template/images/',
      'asset_path' => FCPATH . 'assets/templates/default_template',
      'js_path' => FCPATH . 'assets/templates/default_template/javascripts',
    	// This js file is required by asset loader module. Do not delete.
      'scripts' => array('custom.js'),
      // This js file is required by asset loader module. Do not delete.
      'stylesheets' => array('custom.css'),
    );
    return $data;
  }
}
/* End of file default_model.php */
