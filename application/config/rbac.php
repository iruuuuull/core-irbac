<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING API
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| The creation of this file was to separate the routes of RBAC access
*/

# START MODULE RBAC ROUTES

# Menu Type
$route['rbac/menu'] = 'rbac/MenuController/actionIndex';
$route['rbac/menu/index'] = 'rbac/MenuController/actionIndex';
$route['rbac/menu/(:num)'] = 'rbac/MenuController/actionIndex/$1';
$route['rbac/menu/hapus/(:num)'] = 'rbac/MenuController/actionHapus/$1';
$route['rbac/menu/list-menu/(:num)'] = 'rbac/MenuController/actionListMenu/$1';

# User
$route['rbac/user'] = 'rbac/UserController/actionIndex';
$route['rbac/user/index'] = 'rbac/UserController/actionIndex';
$route['rbac/user/get-data']['post'] = 'rbac/UserController/actionGetData';
$route['rbac/user/create'] = 'rbac/UserController/actionCreate';
$route['rbac/user/detail/(:num)'] = 'rbac/UserController/actionDetail/$1';
$route['rbac/user/simpan-detail/(:num)']['post'] = 'rbac/UserController/actionSimpanDetail/$1';
$route['rbac/user/edit/(:num)'] = 'rbac/UserController/actionEdit/$1';
$route['rbac/user/hapus/(:num)/(:any)'] = 'rbac/UserController/actionHapus/$1/$2';
$route['rbac/user/get-department/(:num)'] = 'rbac/UserController/actionGetDepartment/$1';
$route['rbac/user/get-atasan']['post'] = 'rbac/UserController/actionGetAtasan';
$route['rbac/user/get-grade'] = 'rbac/UserController/actionGetGrade';
$route['rbac/user/get-designation'] = 'rbac/UserController/actionGetDesignation';
$route['rbac/user/get-kelas-jabatan'] = 'rbac/UserController/actionGetKelasJabatan';
$route['rbac/user/get-golongan/(:num)'] = 'rbac/UserController/actionGetGolongan/$1';

# END MODULE RBAC ROUTES