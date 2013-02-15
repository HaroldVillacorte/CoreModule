<?php if (!defined ('BASEPATH')) exit ('No direct script access allowed.');

/*
|--------------------------------------------------------------------------
| Base settings.
|--------------------------------------------------------------------------
*/

// Email settings.
$config['core_email_Priority']    = 3;
$config['core_email_CharSet']     = 'utf-8';
$config['core_email_ContentType'] = 'text/html';
$config['core_email_Encoding']    = '8bit';
$config['core_email_Sendmail']    = '/usr/sbin/sendmail';

/*
|--------------------------------------------------------------------------
| Set the Mailer type.
|--------------------------------------------------------------------------
*/

$config['core_email_Mailer'] = 'smtp';

/*
|--------------------------------------------------------------------------
| Style settings.
|--------------------------------------------------------------------------
*/

/* End of file core_email_config.php */
