<?php if (!defined ('BASEPATH')) exit ('No direct script access allowed.');

class Core_email_library
{

    /**
     * The CI super object reference.
     *
     * @var object
     */
    private static $CI;

    /**
     * The data array.
     *
     * @var array
     */
    private static $data;

    /**
     * The PHPMailer object.
     *
     * @var object
     */
    public $mail;

    function __construct()
    {
        self::$CI =& get_instance();

        // Load the config and language files.
        self::$CI->load->config('core_email/core_email_config');
        self::$CI->lang->load('core_email/core_email');

        // Load the libraries.
        self::$CI->load->library('core_email/phpmailer');
        self::$CI->load->library('form_validation');
        self::$CI->load->library('encrypt');

        // Load the helpers.
        self::$CI->load->helper('language');

        // Initialize the data array.
        self::$data = self::$CI->core_module_model->site_info();

        // Instatiate PHPMailer object.
        $this->mail = new PHPMailer;

        // Get the email settings.
        $this->mail->Timeout = self::$CI->config->item('core_email_smtp_Timeout');
        $this->mail->Priority = self::$CI->config->item('core_email_Priority');
        $this->mail->CharSet = self::$CI->config->item('core_email_CharSet');
        $this->mail->ContentType = self::$CI->config->item('core_email_ContentType');
        $this->mail->Encoding = self::$CI->config->item('core_email_Encoding');
        $this->mail->Sendmail = self::$CI->config->item('core_email_Sendmail');

        // Set the protocol.
        $this->mail->Mailer = self::$CI->config->item('core_email_Mailer');

        // Set the smtp config database.
        //$system_smtp_settings   = $this->system_settings_get(TRUE);
        $this->mail->Host       = setting_get('core_email_Host');
        $this->mail->Port       = setting_get('core_email_Port');
        $this->mail->SMTPAuth   = setting_get('core_email_SMTPAuth');
        $this->mail->SMTPSecure = setting_get('core_email_SMTPSecure');
        $this->mail->Username   = setting_get('core_email_Username');
        $this->mail->Password   = self::$CI->encrypt->decode(setting_get('core_email_Password'));
        $this->mail->From       = setting_get('core_email_From');
        $this->mail->FromName   = setting_get('core_email_FromName');

        // Set reply-to.
        $this->mail->AddReplyTo(
            setting_get('core_email_reply_to'),
            setting_get('core_email_reply_to_name')
        );
    }

    /**
     * Set the validation rules.
     */
    public function system_settings_set_validation_rules()
    {
        $validation_rules = array(
            array(
                'field' => 'core_email_Host',
                'label' => 'Host',
                'rules' => 'required|trim|xss_clean',
            ),
            array(
                'field' => 'core_email_Port',
                'label' => 'Port',
                'rules' => 'required|trim|integer|xss_clean',
            ),
            array(
                'field' => 'core_email_SMTPAuth',
                'label' => 'Authorization Required',
                'rules' => 'trim|integer|exact_length[1]|xss_clean',
            ),
            array(
                'field' => 'core_email_SMTPSecure',
                'label' => 'Security protocol',
                'rules' => 'trim|xss_clean',
            ),
            array(
                'field' => 'core_email_Username',
                'label' => 'Username',
                'rules' => 'trim|xss_clean',
            ),
            array(
                'field' => 'core_email_Password',
                'label' => 'Password',
                'rules' => 'trim|xss_clean',
            ),
            array(
                'field' => 'core_email_From',
                'label' => 'From email',
                'rules' => 'required|trim|valid_email|xss_clean',
            ),
            array(
                'field' => 'core_email_FromName',
                'label' => 'From name',
                'rules' => 'required|trim|xss_clean',
            ),
            array(
                'field' => 'core_email_reply_to',
                'label' => 'Reply-to email',
                'rules' => 'required|trim|valid_email|xss_clean',
            ),
            array(
                'field' => 'core_email_reply_to_name',
                'label' => 'Reply-to name',
                'rules' => 'required|trim|xss_clean',
            ),
        );

        self::$CI->form_validation->set_rules($validation_rules);
    }

    /**
     * Process post array.
     *
     * @param array $post
     * @return array
     */
    public function system_settings_process_post($post = array())
    {
        // Process password.
        if ($post['core_email_Password'] != '')
        {
            $post['core_email_Password'] = self::$CI->encrypt->encode($post['core_email_Password']);
        }
        else
        {
            unset($post['core_email_Password']);
        }

        // Process core_email_SMTPAuth.
        if (!isset($post['core_email_SMTPAuth']))
        {
            $post['core_email_SMTPAuth'] = 0;
        }

        return $post;
    }

    /**
     * Send an email.  Required options are:
     *     'to'
     *     'to_name'
     *     'reply_to'
     *     'reply_to_name'
     *     'subject'
     *     'message'
     *
     * @param array $email
     * @return boolean
     */
    public function system_email_send($email = array())
    {
        // Who to send the email to.
        $to_name = $email['to_name'];
        $to      = $email['to'];

        // Subject and message.
        $subject     = $email['subject'];
        $message     = $email['message'];
        $message_alt = $email['message_alt'];

        // Set the email.
        $this->mail->AddAddress($to, $to_name);
        $this->mail->Subject = $subject;
        $this->mail->Body    = $message;
        $this->mail->AltBody = $message_alt;

        // Send the email.
        $result= $this->mail->Send();

        return ($result) ? TRUE : FALSE;
    }

    /**
     * Send a test email.
     */
    public function system_email_test_send()
    {
        // Message.
        $message     = self::$CI->load->view('core_email/email_templates/system_test', self::$data, TRUE);
        $message_alt = self::$CI->load->view('core_email/email_templates/system_test_alt', self::$data, TRUE);

        // Set the message array.
        $email = array(
            'to'          => $this->mail->From,
            'to_name'     => $this->mail->FromName,
            'subject'     => 'System test from ' . self::$data['site_name'],
            'message'     => $message,
            'message_alt' => $message_alt,
        );

        // Send the email.
        $result = $this->system_email_send($email);
        if ($result)
        {
            self::$CI->session->set_flashdata('message_success', lang('success_system_settings_test_send')
            . $this->mail->From);
            redirect(current_url());
            exit();
        }
        else
        {
            self::$CI->session->set_flashdata('message_error', lang('error_system_settings_test_send'));
            redirect(current_url());
            exit();
        }
    }

}

/* End of file core_email_library */
