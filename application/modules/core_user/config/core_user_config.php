<?php if (!defined ('BASEPATH')) exit ('No direct script access allowed.');

/*
|--------------------------------------------------------------------------
| Uri's.
|--------------------------------------------------------------------------
*/

// User.
$config['user_index_uri']                    = 'user/';
$config['user_login_uri']                    = 'login/';
$config['user_forgotten_password_uri']       = 'forgotten_password/';
$config['user_forgotten_password_login_uri'] = 'forgotten_password_login/';
$config['user_logout_uri']                   = 'logout/';
$config['user_add_uri']                      = 'user_add/';
$config['user_activation_uri']               = 'user_activate/';
$config['user_edit_uri']                     = 'user_edit/';
$config['user_delete_uri']                   = 'user_delete/';

// User admin index redirect to.
$config['user_admin_index_uri'] = base_url();

// User admin roles.
$config['user_admin_roles_uri']       = 'admin_user_roles/';
$config['user_admin_role_add_uri']    = 'admin_user_role_add/';
$config['user_admin_role_edit_uri']   = 'admin_user_role_edit/';
$config['user_admin_role_delete_uri'] = 'admin_user_role_delete/';

// User admin users.
$config['user_admin_users_uri']       = 'admin_users/';
$config['user_admin_user_edit_uri']   = 'admin_user_edit/';
$config['user_admin_user_add_uri']    = 'admin_user_add/';
$config['user_admin_user_delete_uri'] = 'admin_user_delete/';

/* End of file core_user_config.php */
