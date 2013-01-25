<?php if (!defined ('BASEPATH')) exit ('No direct script access allowed.');
/**
* Demo Doctrine 2 Module
*
* This is a sample of basic crud in CI Starter using Doctrine 2.  The code is
* not heavily commented as it does not do anything beyond basic Codeigniter
* combined with HMVC and Doctrine 2.  To learn these technologies it is best
* refer to their respective documentation sites.
*
* @package CI Starter
* @subpackage Modules
* @category Demos
* @author Harold Villacorte
* @link http://laughinghost.com/CI_Starter/
*/
class Demo_Doctrine2 extends MX_Controller {

  protected static $data;
  protected static $user_page;

  function __construct() {
    parent::__construct();
    $this->load->library('doctrine');
    self::$data = $this->core_model->site_info();
    self::$data['module'] = 'demo_doctrine2';
    $this->load->module('user');
    $this->user->permission('admin');
    self::$user_page = NULL;
    if ($this->session->userdata('demo_doctrine2_page')) {
      self::$user_page = $this->session->userdata('demo_doctrine2_page');
    }
    self::$data['user_page'] = self::$user_page;
  }

  public function index() {
    $this->session->keep_flashdata('message_success');
    $this->session->keep_flashdata('message_error');
    redirect(base_url() . 'demo_doctrine2/data/');
  }

  public function data($page = NULL) {
    $this->load->library('table');
    $this->load->library('pagination');

    // Perpage for pagination and Doctrine
    $per_page = 10;

    // Set start record for Doctrine.
    $start = 0;
    if ($page) {
      $start = $page;
    }
    // Doctrine
    $this->benchmark->mark('start'); // Benchmark start

    $dql = 'SELECT u FROM Entities\CrudDemo u';
    $query1 = $this->doctrine->em->createQuery($dql);
    $query2 = $this->doctrine->em
            ->createQuery($dql)
            ->setMaxResults($per_page)
            ->setFirstResult($start);
    $output = $query2->getArrayResult();
    $count = count($query1->getArrayResult());
    // Get first and last id's.
    self::$data['first'] = $page + 1;
    self::$data['last'] = $page + count($output);

    $this->benchmark->mark('stop'); // Benchmark end

    // Pagination setup
    $pagination_config['base_url'] = base_url() . 'demo_doctrine2/data/';
    $pagination_config['total_rows'] = $count;
    $pagination_config['per_page'] = $per_page;
    // Style pagination Foundation 3
    // Full open
    $pagination_config['full_tag_open'] = '<ul class="pagination">';
      // Digits
      $pagination_config['num_tag_open'] = '<li>';
      $pagination_config['num_tag_close'] = '</li>';
      // Current
      $pagination_config['cur_tag_open'] = '<li class="current"><a href="#">';
      $pagination_config['cur_tag_close'] = '</a></li>';
      // Previous link
      $pagination_config['prev_tag_open'] = '<li class="arrow">';
      $pagination_config['prev_tag_close'] = '</li>';
      // Next link
      $pagination_config['next_tag_open'] = '<li class="arrow">';
      $pagination_config['nect_tag_close'] = '<li>';
      // First link
      $pagination_config['first_tag_open'] = '<li>';
      $pagination_config['first_tag_close'] = '</li>';
      // Last link
      $pagination_config['last_tag_open'] = '<li>';
      $pagination_config['last_tag_close'] = '</li>';
    // Full close
    $pagination_config['full_tag_close'] = '</ul>';
    // Pagination render
    $this->pagination->initialize($pagination_config);
    self::$data['pagination_links'] = $this->pagination->create_links();

    // Generate table
    // Table headings
    $add_link = base_url() . 'demo_doctrine2/add/';
    $heading = array(
        'ID', 'Order number', 'Product Code', 'Quantity',
        'Price','Line number', 'Comments', '<a href="' . $add_link
        . '" class="right">Add record +</a>',
    );
    $this->table->set_heading($heading);

    // Table template
    $template = array (
        'table_open'  => '<table width="100%">',
        'table_close' => '</table>',
        );
    $this->table->set_template($template);

    // Table render
    foreach ($output as $key => $value) { // add edit link.
      $output[$key]['edit'] =
              '<a href="' . base_url() . 'demo_doctrine2/delete/'
              . $output[$key]['id']
              . '" class="label alert round right" style="margin-left:10px;"'
              . 'onClick="return confirm(\'Are you sure?\')">Del</a>'
              . '<a href="' . base_url()
              . 'demo_doctrine2/edit/' . $output[$key]['id']
              . '" class="label secondary round right">Edit</a>';
    }
    self::$data['output'] = $this->table->generate($output);

    // Page render
    self::$data['count'] = $count;
    self::$data['elapsed_time'] = $this->benchmark->elapsed_time('start', 'stop');
    array_unshift(self::$data['scripts'], 'demo_doctrine2_ajax.js');
    // Check for ajax request then pick view_file.
    if ($this->input->is_ajax_request()) {
      // Set current page to session.
      $this->session->set_userdata(array('demo_doctrine2_page' => $page));
      $this->load->view('demo_doctrine2_ajax', self::$data);
    }
    else {
      // Set current page to session.
      $this->session->set_userdata(array('demo_doctrine2_page' => $page));
      self::$data['view_file'] = 'demo_doctrine2';
      echo Modules::run('core_template/default_template', self::$data);
    }

  }

  public function edit ($id = NULL) {
    $this->load->helper('form');
    $this->benchmark->mark('start');// Benchmark start.
    if ($id == NULL && !$this->input->post('save')) {
      redirect(base_url() . 'demo_doctrine2/');
    }
    elseif ($this->input->post('save')) {
      //Doctrine
      $record = $this->doctrine->em->find('Entities\CrudDemo', $this->input->post('id'));
      $record->setOrdernumber($this->input->post('order_number'));
      $record->setProductcode($this->input->post('product_code'));
      $record->setQuantityordered($this->input->post('quantitiy_ordered'));
      $record->setPriceeach($this->input->post('price_each'));
      $record->setOrderlinenumber($this->input->post('line_number'));
      $record->setText($this->input->post('text'));

      try {
        $this->doctrine->em->flush();
        $this->session->set_flashdata('message_success', 'Message was successfully saved.');
        redirect(current_url() . '/' . $this->input->post('id'));
      }
      catch (Exception $e) {
        $this->session->set_flashdata('message_error', 'Record could not be saved');
        redirect(current_url() . '/' . $this->input->post('id'));
      }
      $this->benchmark->mark('stop');// Benchmark stop.
      // End Doctrine
    }
    else {
      $record = $this->doctrine->em->find('Entities\CrudDemo', $id);
      $this->benchmark->mark('stop');// Benchmark stop.
      array_unshift(self::$data['scripts'], 'demo_doctrine2_ajax.js');
      self::$data['elapsed_time'] = $this->benchmark->elapsed_time('start', 'stop');
      self::$data['record'] = $record;
      self::$data['view_file'] = 'demo_doctrine2_edit';
      echo Modules::run('core_template/default_template', self::$data);
    }
  }

  public function add () {
    if ($this->input->post('save')) {
      // Doctrine
      $record = new Entities\CrudDemo;
      $record->setOrdernumber($this->input->post('order_number'));
      $record->setProductcode($this->input->post('product_code'));
      $record->setQuantityordered($this->input->post('quantitiy_ordered'));
      $record->setPriceeach($this->input->post('price_each'));
      $record->setOrderlinenumber($this->input->post('line_number'));
      $record->setText($this->input->post('text'));
      $this->doctrine->em->persist($record);
      try {
        $this->doctrine->em->flush();
        $this->session->set_flashdata('message_success', 'Record was successfully added.');
        redirect(base_url() . 'demo_doctrine2/data/' . self::$user_page);
      }
      catch (Exception $e) {
        $this->session->set_flashdata('message_error', 'Record could not be saved.');
        redirect(base_url() . 'demo_doctrine2/data/' . self::$user_page);
      }
    }
    else {
      array_unshift(self::$data['scripts'], 'demo_doctrine2_ajax.js');
      self::$data['view_file'] = 'demo_doctrine2_add';
      echo Modules::run('core_template/default_template', self::$data);
    }
  }

  public function delete ($id = NULL) {
    if ($id == NULL) {
      redirect(base_url());
    }
    else {
      $record = $this->doctrine->em->find('Entities\CrudDemo', $id);
      $this->doctrine->em->remove($record);
      try {
        $this->doctrine->em->flush();
        $this->session->set_flashdata('message_success', 'Record was successfully deleted.');
        redirect(base_url() . 'demo_doctrine2/data/' . self::$user_page);
      }
      catch (Exception $e) {
        $this->session->set_flashdata('message_error', 'Record could not be deleted.');
        redirect(base_url() . 'demo_doctrine2/data/' . self::$user_page);
      }
    }
  }

}

/* End of file demo_doctrine2.php */
