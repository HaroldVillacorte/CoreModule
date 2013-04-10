<?php if (!defined('BASEPATH')) exit ('No direct script access allowed.');

class Core_developer extends MX_Controller
{
    /**
     * The data array.
     *
     * @var array
     */
    public $data = array();

    function __construct()
    {
        parent::__construct();

        $this->template = 'default';
    }

    public function callback_one($args)
    {

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

        echo $this->core_template_library->parse_view($this->template, $this->data, FALSE);
    }

}

/* End of file core_developer.php */
