<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Crud extends MX_Controller {

  protected static $data;

  public function __construct() {
    parent::__construct();
    self::$data = $this->default_model->site_info();
    self::$data['module'] = 'crud';
  }

  public function index()
  {
  	$this->load->module('user');
  	$this->user->permission('admin');
    $this->load->library('grocery_crud');
    $crud = new grocery_CRUD();
    $crud->set_table('crud_demo');
    self::$data['output'] = $crud->render();
    self::$data['view_file'] = 'crud';
    echo Modules::run('template/default_template', self::$data);
  }

}

/* End of file crud.php */