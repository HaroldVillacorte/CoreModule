<?php if (!defined ('BASEPATH')) exit ('No direct script access allowed.');

class Core_email extends MX_Controller
{
    public function index()
    {
        $this->load->library('email');
        $config['useragent']      = 'CI Starter';
        $config['mailpath']       = '/usr/sbin/sendmail';
        $config['charset']        = 'utf-8';
        //$config['validate']       = TRUE;
        $config['newline']        = '\r\n';
        $config['bcc_batch_mode'] = FALSE;
        $config['bcc_batch_size'] = 200;
        $config['priority']       = 3;

        // SMTP.
        $config['protocol']     = 'smtp';
        $config['smtp_timeout'] = 10;
        $config['smtp_host']    = 'smtp.laughinghost.com';
        $config['smtp_user']    = 'admin@lauginghost.com';
        $config['smtp_pass']    = 'hano2laramie';
        $config['smtp_port']    = 587;

        // Styling.
        $config['mailtype']  = 'html';
        $config['wordwrap']  = FALSE;
        $config['wrapchars'] = 70;

        // Initailize email class.
        $this->email->initialize($config);

        // Set up email.
        $this->email->from('admin@lauginghost.com', 'Laughing Host');
        $this->email->reply_to('admin@lauginghost.com');
        $this->email->to('hvillacorte@fastmail.fm');
        $this->email->subject('test');
        $this->email->message('test');

        if (!$this->email->send())
        {
            show_error($this->email->print_debugger());
        }
        else
        {
            echo('DONE');
        }
    }
}
