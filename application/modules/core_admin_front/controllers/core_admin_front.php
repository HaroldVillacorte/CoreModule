<?php if (!defined('BASEPATH')) exit ('No direct script access allowed.');

class Core_admin_front extends MX_Controller
{
    /**
     * The data array.
     *
     * @var array
     */
    public $data;

    function __construct()
    {
        parent::__construct();

        // Initialize the module.
        $this->data = initialize_module('core_developer');

        $this->template = 'core_admin_front';
    }

    public function index()
    {
        $this->load->helper('form');

        if ($this->input->post('submit'))
        {
            $post = $this->input->post();
            $this->data['regex'] = htmlspecialchars($post['regex']);
            $this->data['string'] = htmlspecialchars($post['string']);

            //$array = array();
            preg_match_all($post['regex'],$post['string'], $array);

            $this->data['output_array'] = $array[0];
        }

        $this->load->view($this->template, $this->data, FALSE);
    }

}

/* End of file core_admin_front.php */
