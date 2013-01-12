<?php if (!defined ('BASEPATH')) exit ('No direct script access allowed');

class Asset_Loader extends MX_Controller {

  public function javascript() {
    $this->load->view('asset_loader_js');
  }

  public function stylesheets() {
    $this->load->view('asset_loader_stylesheets');
  }
}

/* End of file asset_loader.php */