<?php if (!defined ('BASEPATH')) exit ('No direct script access allowed.');

class Admin_library
{
    /**
     * The CI super object.
     *
     * @var object
     */
    private static  $CI;

    function __construct()
    {
        self::$CI =& get_instance();

        $admin_uris = get_module_uris('admin');

        foreach ($admin_uris as $key => $value)
        {
            $property = $key . '_uri';
            $this->$property = $value;
        }
    }

}

/* End of file admin_library.php */
