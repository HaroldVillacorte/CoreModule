<?php if (!defined ('BASEPATH')) exit ('No direct script access allowed.');

/*
|--------------------------------------------------------------------------
| Set the language.
|--------------------------------------------------------------------------
*/
$config['user_language']    = 'english';

/*
|--------------------------------------------------------------------------
| Uri's.
|--------------------------------------------------------------------------
*/

// User.
$config['user_index_uri']   = 'user/';
$config['user_login_uri']   = 'user/login/';
$config['user_logout_uri']  = 'user/logout/';
$config['user_add_uri']     = 'user/add/';
$config['user_edit_uri']    = 'user/edit/';
$config['user_delete_uri']  = 'user/delete/';

// User admin roles.
$config['user_admin_roles_uri']   = 'user/admin_roles/';
$config['user_admin_add_role_uri']  = 'user/admin_add_role/';
$config['user_admin_edit_role_uri']     = 'user/admin_edit_role/';
$config['user_delete_role_uri']    = 'user/admin_delete_role/';

// User admin users.
$config['user_admin_users_uri']  = 'user/admin_users/';
$config['user_admin_edit_user_uri']  = 'user/admin_edit_user/';
$config['user_admin_add_user_uri']  = 'user/admin_add_user/';
$config['user_admin_delete_user_uri']  = 'user/admin_delete_user/';

/*
|--------------------------------------------------------------------------
| Perisitent login cookie.
|--------------------------------------------------------------------------
*/

// Persistent login cookie name.
$config['user_persistent_cookie_name'] = 'CI_Starter_login';

/* End of file user_config.php */
