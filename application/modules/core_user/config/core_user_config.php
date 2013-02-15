<?php if (!defined ('BASEPATH')) exit ('No direct script access allowed.');

/*
|--------------------------------------------------------------------------
| Uri's.
|--------------------------------------------------------------------------
*/

// User.
$config['user_index_uri']      = 'user/';
$config['user_login_uri']      = 'user/login/';
$config['user_logout_uri']     = 'user/logout/';
$config['user_add_uri']        = 'user/add/';
$config['user_activation_uri'] = 'user/activate/';
$config['user_edit_uri']       = 'user/edit/';
$config['user_delete_uri']     = 'user/delete/';

// User admin index redirect to.
$config['user_admin_index_uri'] = base_url();

// User admin roles.
$config['user_admin_roles_uri']   = 'user/admin_roles/';
$config['user_admin_role_add_uri']  = 'user/admin_role_add/';
$config['user_admin_role_edit_uri']     = 'user/admin_role_edit/';
$config['user_admin_role_delete_uri']    = 'user/admin_role_delete/';

// User admin users.
$config['user_admin_users_uri']  = 'user/admin_users/';
$config['user_admin_user_edit_uri']  = 'user/admin_user_edit/';
$config['user_admin_user_add_uri']  = 'user/admin_user_add/';
$config['user_admin_user_delete_uri']  = 'user/admin_user_delete/';

/*
|--------------------------------------------------------------------------
| User activation expire limit.
|--------------------------------------------------------------------------
*/

// Also edit the core_user_lang.php file item $lang['success_user_account_created']
// to reflect any changes here.
$config['user_activation_expire_limit'] = 86400;

/*
|--------------------------------------------------------------------------
| Perisitent login cookie.
|--------------------------------------------------------------------------
*/

// Persistent login cookie name.
$config['user_persistent_cookie_name'] = 'CI_Starter_login';
$config['user_persistent_cookie_expire'] = 1209600;

/*
|--------------------------------------------------------------------------
| Login attempts.
|--------------------------------------------------------------------------
*/

$config['user_login_attempts_max'] = 5;
$config['user_login_attempts_time'] = 120;
$config['user_login_attempts_lockout_time'] = 900;

/* End of file core_user_config.php */
