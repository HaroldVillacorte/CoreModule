<?php if (!defined ('BASEPATH')) exit ('No direct script access allowed');
/**
 * CI Starter Core.
 * @see default_model.php
 */
class Asset_Loader extends MX_Controller {

  public function javascript() {
    $this->load->view('asset_loader_js');
  }

  public function stylesheets() {
    $this->load->view('asset_loader_stylesheets');
  }
}

/* End of file asset_loader.php */