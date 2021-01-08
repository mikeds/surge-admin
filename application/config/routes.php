<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

$route["default_controller"]    = "admin/Dashboard";

$route["pastors"]           = "admin/Pastors";
$route["pastors/(:num)"]    = "admin/Pastors/index";

$route["church-leaders"]           = "admin/Church_leaders";
$route["church-leaders/(:num)"]    = "admin/Church_leaders/index";

$route["church-leaders/new"]            = "admin/Church_leaders/new";
$route["church-leaders/update/(:any)"]  = "admin/Church_leaders/update/$1";

$route["church-branches"]           = "admin/Church_branches";
$route["church-branches/(:num)"]    = "admin/Church_branches/index";

$route["church-branches/new"]            = "admin/Church_branches/new";
$route["church-branches/update/(:any)"]  = "admin/Church_branches/update/$1";

$route["church-transactions"]           = "admin/Church_transactions";
$route["church-transactions/(:num)"]    = "admin/Church_transactions/index/$1";

$route["client-transactions"]           = "admin/Client_transactions";
$route["client-transactions/(:num)"]    = "admin/Client_transactions/index/$1";

$route["pastor-transactions"]           = "admin/Pastor_transactions";
$route["pastor-transactions/(:num)"]    = "admin/Pastor_transactions/index/$1";

$route["login"]     = "public/login";
$route["logout"]    = "public/logout";

$route['404_override'] = 'public/Error_404';
$route['translate_uri_dashes'] = FALSE;

























