<?php if (!defined ('BASEPATH')) exit ('No direct script access allowed.');

/*
|--------------------------------------------------------------------------
| User.
|--------------------------------------------------------------------------
*/

// login()
$lang['success_user_login']                     = 'You are now logged in as ';
$lang['notice_user_persistent_fail']            = 'Persistent login failed.';
$lang['error_user_login_failed']                = 'Username and password combination not found.';

// add()
$lang['success_user_account_created']           = 'Acount was successfully created.';
$lang['error_user_account_failed']              = 'There was a problem adding your account.';

// edit()
$lang['success_user_account_edited']            = 'Account was successfully saved.';
$lang['error_user_account_edit_unauthorized']   = 'You are not authorized to edit this user.';
$lang['error_user_account_edit_failed']         = 'There was a problem saving your account.';

// delete()
$lang['error_user_account_delete_failed']       = 'Unable to delete your account.  Please contact administrator.';

/*
|--------------------------------------------------------------------------
| User library.
|--------------------------------------------------------------------------
*/

// logout()
$lang['notice_user_logout']     = 'You are now logged out.';

// permission()
$lang['error_user_permission']  = 'You are not authorized to access that page.';

// check_user_protected()
$lang['error_user_protected']   = 'Unable to process.  User account is protected.';

/*
|--------------------------------------------------------------------------
| User admin.
|--------------------------------------------------------------------------
*/

// add_role()
$lang['success_admin_add_role']     = 'Role was successfully added.';
$lang['error_admin_add_role']       = 'There was problem adding the role.';

// edit_role()
$lang['success_admin_edit_role']    = 'Role was successfully saved.';
$lang['error_admin_edit_role']      = 'There was a problem saving the role.';

// delete_role()
$lang['success_admin_delete_role']  = 'Role was successfully deleted.';
$lang['error_admin_delete_role']    = 'There was a problem deleting the role.';

// edit_user()
$lang['success_admin_edit_user']    = 'User was successfully saved.';
$lang['error_admin_edit_user']      = 'There was a saving the user account.';

// add_user()
$lang['success_admin_add_user']     = 'User was successfully saved.';
$lang['error_admin_add_user']       = 'User could not be saved';

// delete_user()
$lang['success_admin_delete_user']  = 'Record was successfully deleted.';
$lang['error_admin_delete_user']    = 'There was a problem.  Record could not be deleted.';

/*
|--------------------------------------------------------------------------
| User admin library.
|--------------------------------------------------------------------------
*/

// check_user_protected()
$lang['error_admin_user_protected'] = 'Unable to process.  User account is protected.';

// check_role_protected()
$lang['error_admin_role_protected'] = 'Unable to process.  Role is protected.';

// role_table()
$lang['confirm_admin_role_delete']  = '\'Are you sure you want to delete this role?\'';

// user_page_table_setup()
$lang['confirm_admin_user_delete']  = '\'Are you sure you want to delete this user?\'';

/*
|--------------------------------------------------------------------------
| Validation.
|--------------------------------------------------------------------------
*/

// set_validation_rules()
$lang['validation_valid_base64']  = 'Password may only contain alpha-numeric characters, +\'s, and /\'s';
/* End of file user_lang.php */
