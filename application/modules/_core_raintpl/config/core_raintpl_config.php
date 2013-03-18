<?php if (!defined ('BASEPATH')) exit ('No direct script access allowed.');

// Enable and disable design mode.
$raintpl_design_mode = TRUE;

// core_raintpl_library variables.
$config['raintpl_design_mode']        = $raintpl_design_mode;
$config['raintpl_template_directory'] = APPPATH . 'raintpl_templates' . DIRECTORY_SEPARATOR;
$config['raintpl_template_name']      = 'default_template' . DIRECTORY_SEPARATOR;
$config['raintpl_template_file']      = 'default_template';
$config['raintpl_asset_cache']        = FCPATH . 'asset_cache' . DIRECTORY_SEPARATOR;

// RainTPL config variables.
$config['raintpl_configuration']      = array(
                                            'cache_dir'             => APPCACHEPATH . 'rain_templates' . DIRECTORY_SEPARATOR,
                                            'tpl_ext'               => 'tpl',
                                            'path_replace'          => TRUE,
                                            'path_replace_list'     => array('img', 'link', 'script'),
                                            'black_list'            => array(),
                                            'check_template_update' => $raintpl_design_mode,
                                            'php_enabled'           => FALSE,
                                       );

/* End of file core_raintpl_config.php */
