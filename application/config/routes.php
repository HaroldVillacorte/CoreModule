<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

// Uncomment the following custom code when directed during intall.

/* Start custom code:

// Load the database class.
require_once BASEPATH . 'database/DB' . EXT ;

// Do not delete this route.  It is used by the system to check route functionality.
$route['core_module_route_test'] = TRUE;

// Reference the database object.
$db =& DB();

if ($db->hostname != '' && $db->username != '' && $db->password != '' && $db->database != '')
{
    // Disable database caching here to avoid WSOD.
    $db->cache_off();

    // Get the page slugs.
    $query = $db->select('slug')->get('core_pages');

    // Define the page routes.
    if ($query->num_rows() > 0)
    {
        $routes = $query->result();

        foreach ($routes as $route_row)
        {
            $route[$route_row->slug] = 'core_module/page/' . $route_row->slug;
        }
    }

    // Get the admin page slugs.
    $query = $db->select('slug')->get('core_pages_admin');

    // Define the page routes.
    if ($query->num_rows() > 0)
    {
        $routes = $query->result();

        foreach ($routes as $route_row)
        {
            $route[$route_row->slug . '(.*)'] = 'core_module/admin/' . $route_row->slug;
        }
    }

    // Reenable database caching.
    $db->cache_on();
}

 * End of custom code.
 */

$route['default_controller'] = 'core_module/core_module';
$route['404_override'] = '';


/* End of file routes.php */
/* Location: ./application/config/routes.php */