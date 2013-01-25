<?php if (!defined ('BASEPATH')) exit ('No direct script access allowed.');

class Core_Functions extends CI_Model {

  function form_error_class($field) {
  $class = '';
  if (form_error($field)) {
    $class = 'error';
  }
    return $class;
  }

}

/* End of file core_functions.php */
