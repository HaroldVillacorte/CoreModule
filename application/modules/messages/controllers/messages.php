<?php if (!defined ('BASEPATH')) exit ('No direct script access allowed');

class Messages extends MX_Controller {

  public function load() {
    $data = array();
    if ($message_success = $this->session->flashdata ('message_success')) {
      $data['message_success'] = $message_success;
    }
    if ($message_error = $this->session->flashdata ('message_error')) {
      $data['message_error'] = $message_error;
    }
    if ($message_notice = $this->session->flashdata ('message_notice')) {
      $data['message_notice'] = $message_notice;
    }
    $this->load->view ('messages', $data);
  }
}
/* End of file messages.php */
