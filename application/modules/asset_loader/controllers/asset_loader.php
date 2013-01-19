<?php if (!defined ('BASEPATH')) exit ('No direct script access allowed');
/**
* Asset Loader Module
*
* This module allows you to load css and javascript files from the controller.
*
* @package CI Starter
* @subpackage Modules
* @category Core
* @author Harold Villacorte
* @link http://laughinghost.com/CI_Starter/
*/
class Asset_Loader extends MX_Controller {
  // Loads the associated view file.
  public function javascript() {
    $this->load->view('asset_loader_js');
  }
  // Loads the associated view file.
  public function stylesheets() {
    $this->load->view('asset_loader_stylesheets');
  }
}

/* End of file asset_loader.php */