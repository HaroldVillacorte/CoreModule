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

        // Load the config.
        self::$CI->load->config('core_email/core_email_config');

        // Load the libraries.
        require_once 'class.phpmailer.php';
        require_once 'class.smtp.php';

        // Initialize the data array.
        self::$data = self::$CI->core_module_model->site_info();

        // Instatiate PHPMailer object.
        $this->mail = new PHPMailer;

        // Get the email settings.
        $this->mail->Priority = self::$CI->config->item('core_email_Priority');
        $this->mail->CharSet = self::$CI->config->item('core_email_CharSet');
        $this->mail->ContentType = self::$CI->config->item('core_email_ContentType');
        $this->mail->Encoding = self::$CI->config->item('core_email_Encoding');
        $this->mail->Sendmail = self::$CI->config->item('core_email_Sendmail');

        // Set the protocol.
        $this->mail->Mailer = self::$CI->config->item('core_email_Mailer');

        // Set the smtp config variables.
        $this->mail->Host       = self::$CI->config->item('core_email_Host');
        $this->mail->Port       = self::$CI->config->item('core_email_Port');
        $this->mail->SMTPAuth   = self::$CI->config->item('core_email_SMTPAuth');
        $this->mail->SMTPSecure = self::$CI->config->item('core_email_SMTPSecure');
        $this->mail->Username   = self::$CI->config->item('core_email_Username');
        $this->mail->Password   = self::$CI->config->item('core_email_Password');

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
     * @param array $dynamic_config
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

        //  Reply to
        $reply_to      = $this->mail->Username;
        $reply_to_name = self::$data['site_name'];

        // From.
        $from      = $this->mail->Username;
        $from_name = self::$data['site_name'];

        // Set the methods.
        $this->mail->AddReplyTo($reply_to, $reply_to_name);
        $this->mail->SetFrom($from, $from_name);
        $this->mail->AddAddress($to, $to_name);
        $this->mail->Subject = $subject;
        $this->mail->Body    = $message;
        $this->mail->AltBody = $message_alt;

        // Send the email.
        $result= $this->mail->Send();

        return ($result) ? TRUE : FALSE;
    }

}

/* End of file core_email_library */
