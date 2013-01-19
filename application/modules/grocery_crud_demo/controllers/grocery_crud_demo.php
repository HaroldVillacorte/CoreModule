<?php if (!defined ('BASEPATH')) exit ('No direct script access allowed');

class Grocery_Crud_Demo extends MX_Controller {

  protected static $data;

  public function __construct() {
    parent::__construct ();
    self::$data = $this->default_model->site_info ();
    self::$data['module'] = 'grocery_crud_demo';
  }

  public function index() {
    $this->load->module ('user');
    $this->user->permission ('admin');
    $this->load->library ('grocery_crud');

    $this->benchmark->mark('code_start');
    $crud = new grocery_CRUD ();
    $crud->set_table ('crud_demo');
    self::$data['output'] = $crud->render ();
    $this->benchmark->mark('code_end');
    self::$data['elapsed_time'] = $this->benchmark->elapsed_time('code_start', 'code_end');
    self::$data['view_file'] = 'grocery_crud_demo';
    echo Modules::run ('template/default_template', self::$data);
  }
}
/* End of file grocery_crud_demo.php */
