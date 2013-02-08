<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Demo Grocery Crud Module
 *
 * This is a sample of basic crud in CI Starter using Grocery CRUD.  The code is
 * not heavily commented as it does not do anything beyond basic Codeigniter
 * combined with HMVC and Grocery CRUD.  To learn these technologies it is best
 * refer to their respective documentation sites.
 *
 * @package CI Starter
 * @subpackage Modules
 * @category Demos
 * @author Harold Villacorte
 * @link http://laughinghost.com/CI_Starter/
 */
class Demo_Grocery_Crud extends MX_Controller
{
    protected static $data;

    public function __construct()
    {
        parent::__construct();
        self::$data = $this->core_model->site_info();
        self::$data['module'] = 'demo_grocery_crud';
    }

    public function index()
    {
        $this->load->module('user');
        $this->user->permission('admin');
        $this->load->library('grocery_crud');

        $this->benchmark->mark('code_start');
        $crud = new grocery_CRUD ();
        $crud->set_table('crud_demo');
        self::$data['output'] = $crud->render();
        $this->benchmark->mark('code_end');
        self::$data['elapsed_time'] = $this->benchmark->elapsed_time('code_start', 'code_end');
        self::$data['view_file'] = 'demo_grocery_crud';
        echo Modules::run('core_template/default_template', self::$data);
    }

}

/* End of file demo_grocery_crud_.php */
