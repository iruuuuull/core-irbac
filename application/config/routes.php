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
$route['default_controller'] = 'SiteController/actionIndex';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

# START API
require_once __DIR__ . '/api.php';
# END API

# START API
require_once __DIR__ . '/rbac.php';
# END API

# START SITE
$route['site'] = 'SiteController/actionIndex';
$route['site/index'] = 'SiteController/actionIndex';
$route['site/login'] = 'SiteController/actionLogin';
$route['site/logout'] = 'SiteController/actionLogout';
$route['site/lock'] = 'SiteController/actionLock';
$route['site/google-auth'] = 'SiteController/actionGoogleAuth';
# END SITE


# START MODULE MASTER ROUTES

# MASTER UNIT CAMPUS
$route['master/unit-campus'] 						= 'master/UnitCampusController/actionIndex';
$route['master/unit-campus/index'] 					= 'master/UnitCampusController/actionIndex';
$route['master/unit-campus/tambah'] 				= 'master/UnitCampusController/actionTambah';
$route['master/unit-campus/detail/(:num)'] 			= 'master/UnitCampusController/actionDetail/$1';
$route['master/unit-campus/get-data']['post'] 		= 'master/UnitCampusController/actionGetData';
$route['master/unit-campus/hapus/(:num)']['post'] 	= 'master/UnitCampusController/actionHapus/$1';
$route['master/unit-campus/update/(:num)']['post'] 	= 'master/UnitCampusController/actionUpdate/$1';
$route['master/unit-campus/update/(:num)']			= 'master/UnitCampusController/actionUpdate/$1';

# END MODULE MASTER ROUTES

# MASTER  PENGELOLAAN MAHASISWA

$route['pengelolaan-mahasiswa'] 						= 'master/PengelolaanMahasiswaController/actionIndex';
$route['pengelolaan-mahasiswa/index'] 					= 'master/PengelolaanMahasiswaController/actionIndex';
$route['pengelolaan-mahasiswa/tambah'] 					= 'master/PengelolaanMahasiswaController/actionTambah';
$route['pengelolaan-mahasiswa/detail/(:num)'] 			= 'master/PengelolaanMahasiswaController/actionDetail/$1';
$route['pengelolaan-mahasiswa/get-data']['post'] 		= 'master/PengelolaanMahasiswaController/actionGetData';
$route['pengelolaan-mahasiswa/hapus/(:num)']['post'] 	= 'master/PengelolaanMahasiswaController/actionHapus/$1';
$route['pengelolaan-mahasiswa/update/(:num)']['post'] 	= 'master/PengelolaanMahasiswaController/actionUpdate/$1';
$route['pengelolaan-mahasiswa/update/(:num)']			= 'master/PengelolaanMahasiswaController/actionUpdate/$1';
$route['pengelolaan-mahasiswa/get-kabupaten/(:num)']	= 'master/PengelolaanMahasiswaController/actionGetKabupaten/$1';
$route['pengelolaan-mahasiswa/get-kecamatan/(:num)']	= 'master/PengelolaanMahasiswaController/actionGetKecamatan/$1';
$route['pengelolaan-mahasiswa/get-kelurahan/(:num)']	= 'master/PengelolaanMahasiswaController/actionGetKelurahan/$1';
$route['pengelolaan-mahasiswa/get-kampus/(:num)']		= 'master/PengelolaanMahasiswaController/actionGetKampus/$1';
$route['pengelolaan-mahasiswa/get-product/(:num)']		= 'master/PengelolaanMahasiswaController/actionGetProductByUnit/$1';
# END MODULE MASTER ROUTES

# START NOTIFIKASI
$route['notifikasi'] = 'NotifikasiController/actionIndex';
$route['notifikasi/index'] = 'NotifikasiController/actionIndex';
$route['notifikasi/read/(:num)'] = 'NotifikasiController/actionRead/$1';
# END NOTIFIKASI
