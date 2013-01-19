<?php if (!defined ('BASEPATH')) exit ('No direct script access allowed');
/**
* Template Module
*
* This module was inspired by a YouTube video posted by David Connelly which
* can be viewed here: http://www.youtube.com/watch?v=7F4PiyfwOtI
* The functionality is exactly as explained in the video with the exception
* of the addition of partials.
*
* @package CI Starter
* @subpackage Modules
* @category Core
* @author Harold Villacorte
* @link http://laughinghost.com/CI_Starter/
*/

class Template extends MX_Controller {

  public function slider_template($data) {
    // The entire $data array is nested within itself and passed to the template
    // which can then pass it on to the partials.
    $data['data_two'] = $data;
    $this->load->view ('slider_template', $data);
  }

  public function default_template($data) {
    // The entire $data array is nested within itself and passed to the template
    // which can then pass it on to the partials.
    $data['data_two'] = $data;
    $this->load->view ('default_template', $data);
  }
}

/* End of file template.php */
