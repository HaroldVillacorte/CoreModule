<?php if (!defined ('BASEPATH')) exit ('No direct script access allowed.');

/*
|--------------------------------------------------------------------------
| Base settings.
|--------------------------------------------------------------------------
*/

$config['core_email_Priority']    = 3;
$config['core_email_CharSet']     = 'utf-8';
$config['core_email_ContentType'] = 'text/plain';
$config['core_email_Encoding']    = '8bit';
$config['core_email_Sendmail']    = '/usr/sbin/sendmail';

/*
|--------------------------------------------------------------------------
| Smtp settings.
|--------------------------------------------------------------------------
*/

$config['core_email_Mailer'] = 'smtp';

/*
|--------------------------------------------------------------------------
| Smtp settings.
|--------------------------------------------------------------------------
*/

$config['core_email_Host']       = '###########';
$config['core_email_Port']       = 587;
$config['core_email_SMTPAuth']   = TRUE;
$config['core_email_SMTPSecure'] = 'tls';
$config['core_email_Username']   = '###########';
$config['core_email_Password']   = '###########';

/*
|--------------------------------------------------------------------------
| Style settings.
|--------------------------------------------------------------------------
*/

/* End of file core_email_config.php */
