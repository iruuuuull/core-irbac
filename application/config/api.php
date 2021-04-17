<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING API
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| The creation of this file was initiated by user needs of API
*/

$route['api/auth/login']['post'] = 'api/AuthController/actionLogin';
$route['api/auth/refresh']['post'] = 'api/AuthController/actionRefresh';
$route['api/auth/detail-user'] = 'api/AuthController/actionDetailUser';
$route['api/auth/detail-user/(:num)'] = 'api/AuthController/actionDetailUser/$1';

$route['api/profile/change-password']['post'] = 'api/ProfileController/actionChangePassword';
$route['api/profile/change-picture']['post'] = 'api/ProfileController/actionChangePicture';

$route['api/attendance/check-in']['post'] = 'api/AttendanceController/actionCheckIn';
$route['api/attendance/check-out']['post'] = 'api/AttendanceController/actionCheckOut';
$route['api/attendance/list'] = 'api/AttendanceController/actionList';
$route['api/attendance/list/(:num)'] = 'api/AttendanceController/actionList/$1';
$route['api/attendance/detail-check-in/(:num)'] = 'api/AttendanceController/actionDetailCheckIn/$1';
$route['api/attendance/detail-check-out/(:num)'] = 'api/AttendanceController/actionDetailCheckOut/$1';
