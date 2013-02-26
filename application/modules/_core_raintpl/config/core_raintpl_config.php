<?php if (!defined ('BASEPATH')) exit ('No direct script access allowed.');

$config['raintpl_template_directory'] = APPPATH . 'raintpl_templates/';
$config['raintpl_template_name']      = 'default_template/';
$config['raintpl_template_file']      = 'default_template';
$config['raintpl_configuration']      = array(
                                            'cache_dir'             => FCPATH . 'cache/',
                                            'tpl_ext'               => 'tpl',
                                            'path_replace'          => FALSE,
                                            'path_replace_list'     => array('img', 'link', 'script'),
                                            'black_list'            => array(),
                                            'check_template_update' => TRUE,
                                            'php_enabled'           => FALSE,
                                       );

/* End of file core_raintpl_config.php */
