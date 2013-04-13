<?php if (!defined ('BASEPATH')) exit ('No direct script access allowed.');

/*
|--------------------------------------------------------------------------
| Set the basic site information.
|--------------------------------------------------------------------------
*/

$config['core_module_site_name']        = 'CoreModule';
$config['core_module_site_description'] = 'An awesome Codeigniter starter package.';

/*
|--------------------------------------------------------------------------
| Set the the site url's and paths.
|--------------------------------------------------------------------------
*/

$config['core_module_scripts']      = array('custom.js');
$config['core_module_stylesheets']  = array('custom.css');

/*
|--------------------------------------------------------------------------
| Set allowable html tags in posts.
|--------------------------------------------------------------------------
*/

$config['core_module_allowed_tags'] = '<h1><h2><h3><h4><h5><h6><p><strong><em><ul><ol><li><br><label>';

/*
|--------------------------------------------------------------------------
| Uri's.
|--------------------------------------------------------------------------
*/

// Admin.
$config['pages_uri']            = 'pages/';
$config['admin_pages_uri']      = 'admin_pages/';
$config['page_add_uri']         = 'page_add/';
$config['admin_page_add_uri']   = 'admin_page_add/';
$config['page_edit_uri']        = 'page_edit/';
$config['admin_page_edit_uri']  = 'admin_page_edit/';
$config['page_delete_uri']      = 'page_delete/';
$config['adminpage_delete_uri'] = 'admin_page_delete/';

/* End of file core_module_config.php */
