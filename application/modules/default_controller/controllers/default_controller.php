<?php if (!defined ('BASEPATH')) exit ('No direct script access allowed');

class Default_Controller extends MX_Controller {

  protected static $data;
  protected $default_template = 'template/slider_template';
  protected $columns_template = 'template/default_template';

  public function __construct() {
    parent::__construct ();
    self::$data = $this->default_model->site_info ();
    self::$data['module'] = 'default_controller';
  }

  public function index() {
  	// Add filenames to the $scripts array.
    array_unshift(self::$data['scripts'], 'jquery.foundation.orbit.js');
    //array_unshift(self::$data['stylesheets'], 'CSS file GOES HERE');
    self::$data['view_file'] = 'one_column';
    echo Modules::run ($this->default_template, self::$data);
  }

  public function columns($view) {
    self::$data['view_file'] = $view;
    echo Modules::run ($this->columns_template, self::$data);
  }
}
/* End of file welcome.php */
