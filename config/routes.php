<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['posts/index'] = 'posts/index';
$route['posts/create'] = 'posts/create';
$route['posts/update'] = 'posts/update';
$route['posts/(:any)'] = 'posts/view/$1';
$route['posts'] = 'posts/index';

$route['default_controller'] = 'pages/view';

$route['categories'] = 'categories/index';
$route['categories/create'] = 'categories/create';
$route['categories/posts/(:any)'] = 'categories/posts/$1';

$route['person'] = 'person';
$route['users'] = 'users';
$route['users/action'] = 'users/action';
$route['members'] = 'members';
$route['exportmembers'] = 'exportmembers';
$route['exportusers'] = 'exportusers';
$route['exportcashbook'] = 'exportcashbook';
$route['exportledger'] = 'exportledger';
$route['ledger'] = 'ledger';
$route['cashbook'] = 'cashbook';

$route['(:any)'] = 'pages/view/$1';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
