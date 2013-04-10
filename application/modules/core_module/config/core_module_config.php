<?php if (!defined ('BASEPATH')) exit ('No direct script access allowed.');

/*
|--------------------------------------------------------------------------
| Set the basic site information.
|--------------------------------------------------------------------------
*/

$config['core_module_site_name']        = 'CI Starter';
$config['core_module_site_description'] = 'An awesome Codeigniter starter package.';

/*
|--------------------------------------------------------------------------
| Set the the site url's and paths.
|--------------------------------------------------------------------------
*/

$config['core_module_template_url'] = 'assets/templates/default_template/';
$config['core_module_css_url']      = 'assets/templates/default_template/stylesheets/';
$config['core_module_js_url']       = 'assets/templates/default_template/javascripts/';
$config['core_module_img_url']      = 'assets/templates/default_template/images/';
$config['core_module_asset_path']   = 'assets/templates/default_template';
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
$config['pages_uri']       = 'admin/pages/';
$config['page_add_uri']    = 'admin/page_add/';
$config['page_edit_uri']   = 'admin/page_edit/';
$config['page_delete_uri'] = 'admin/page_delete/';

/* End of file core_module_config.php */
