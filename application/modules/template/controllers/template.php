<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Template extends MX_Controller {

  public function slider_template($data)
  {
    $data['data_two'] = $data;
    $this->load->view('slider_template', $data);
  }

  public function default_template($data)
  {
    $data['data_two'] = $data;
    $this->load->view('default_template', $data);
  }
}

/* End of file template.php */