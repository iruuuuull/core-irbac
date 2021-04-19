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

# Group
$route['rbac/group'] = 'rbac/GroupController/actionIndex';
$route['rbac/group/index'] = 'rbac/GroupController/actionIndex';
$route['rbac/group/detail/(:num)'] = 'rbac/GroupController/actionDetail/$1';
$route['rbac/group/get-data']['post'] = 'rbac/GroupController/actionGetData';
$route['rbac/group/simpan']['post'] = 'rbac/GroupController/actionSimpan';
$route['rbac/group/simpan/(:num)']['post'] = 'rbac/GroupController/actionSimpan/$1';
$route['rbac/group/hapus/(:num)']['post'] = 'rbac/GroupController/actionHapus/$1';

# Route
$route['rbac/route'] = 'rbac/RouteController/actionIndex';
$route['rbac/route/index'] = 'rbac/RouteController/actionIndex';
$route['rbac/route/create']['post'] = 'rbac/RouteController/actionCreate';
$route['rbac/route/assign']['post'] = 'rbac/RouteController/actionAssign';
$route['rbac/route/remove']['post'] = 'rbac/RouteController/actionRemove';
$route['rbac/route/refresh']['post'] = 'rbac/RouteController/actionRefresh';

# Allowed
$route['rbac/allowed'] = 'rbac/AllowedController/actionIndex';
$route['rbac/allowed/index'] = 'rbac/AllowedController/actionIndex';
$route['rbac/allowed/create']['post'] = 'rbac/AllowedController/actionCreate';
$route['rbac/allowed/assign']['post'] = 'rbac/AllowedController/actionAssign';
$route['rbac/allowed/remove']['post'] = 'rbac/AllowedController/actionRemove';
$route['rbac/allowed/refresh']['post'] = 'rbac/AllowedController/actionRefresh';

# Permission
$route['rbac/permission'] = 'rbac/PermissionController/actionIndex';
$route['rbac/permission/index'] = 'rbac/PermissionController/actionIndex';
$route['rbac/permission/detail/(:num)'] = 'rbac/PermissionController/actionDetail/$1';
$route['rbac/permission/get-data']['post'] = 'rbac/PermissionController/actionGetData';
$route['rbac/permission/simpan']['post'] = 'rbac/PermissionController/actionSimpan';
$route['rbac/permission/simpan/(:num)']['post'] = 'rbac/PermissionController/actionSimpan/$1';
$route['rbac/permission/hapus/(:num)']['post'] = 'rbac/PermissionController/actionHapus/$1';
$route['rbac/permission/view/(:num)'] = 'rbac/PermissionController/actionView/$1';
$route['rbac/permission/assign/(:num)']['post'] = 'rbac/PermissionController/actionAssign/$1';
$route['rbac/permission/remove/(:num)']['post'] = 'rbac/PermissionController/actionRemove/$1';
$route['rbac/permission/refresh/(:num)']['post'] = 'rbac/PermissionController/actionRefresh/$1';

# Permission
$route['rbac/assignment'] = 'rbac/AssignmentController/actionIndex';
$route['rbac/assignment/index'] = 'rbac/AssignmentController/actionIndex';
$route['rbac/assignment/get-data']['post'] = 'rbac/AssignmentController/actionGetData';
$route['rbac/assignment/view/(:num)'] = 'rbac/AssignmentController/actionView/$1';
$route['rbac/assignment/assign/(:num)']['post'] = 'rbac/AssignmentController/actionAssign/$1';
$route['rbac/assignment/remove/(:num)']['post'] = 'rbac/AssignmentController/actionRemove/$1';
$route['rbac/assignment/refresh/(:num)']['post'] = 'rbac/AssignmentController/actionRefresh/$1';

# END MODULE RBAC ROUTES