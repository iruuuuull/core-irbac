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
$route['404_override'] = 'SiteController/action404NotFound';
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

# START PROFIL
$route['profil']	= 'ProfilController/actionIndex';
$route['profil/index'] 	= 'ProfilController/actionIndex';
$route['profil/simpan-info-personal']['post']	= 'ProfilController/actionSimpanInfoPersonal';
$route['profil/ubah-foto']['post']	= 'ProfilController/actionUbahFoto';
$route['profil/simpan-dokumen']['post']	= 'ProfilController/actionSimpanDokumen';
$route['profil/ubah-password/(:num)']['post']	= 'ProfilController/actionUbahPassword/$1';

# User Anggota Keluarga
$route['profil/getdata-info-keluarga/(:num)']	= 'ProfilController/actionGetDataInfoKeluarga/$1';
$route['profil/getdata-info-keluarga']	= 'ProfilController/actionGetDataInfoKeluarga';
$route['profil/simpan-info-keluarga']['post']	= 'ProfilController/actionSimpanInfoKeluarga';
$route['profil/simpan-info-keluarga/(:num)']['post']	= 'ProfilController/actionSimpanInfoKeluarga/$1';
$route['profil/detail-info-keluarga/(:num)']	= 'ProfilController/actionDetail/$1';
$route['profil/hapus-info-keluarga/(:num)']['post']	= 'ProfilController/actionHapusInfoKeluarga/$1';


# user history Pendidikan
$route['profil/getdata-history-pendidikan/(:num)']['post']	= 'ProfilController/actionGetDataHistoryPendidikan/$1';
$route['profil/getdata-history-pendidikan']	= 'ProfilController/actionGetDataHistoryPendidikan';
$route['profil/detail-history-pendidikan/(:num)'] = 'ProfilController/actionDetailHistoryPendidikan/$1';
$route['profil/simpan-history-pendidikan']['post']	= 'ProfilController/actionSimpanHistoryPendidikan';
$route['profil/simpan-history-pendidikan/(:num)']['post'] = 'ProfilController/actionSimpanHistoryPendidikan/$1';
$route['profil/delete-history-pendidikan/(:num)']['post']	= 'ProfilController/actionHapusHistoryPendidikan/$1';


# user sertifikasi
$route['profil/getdata-sertifikasi/(:num)']	= 'ProfilController/actionGetDataSertifikasi/$1';
$route['profil/getdata-sertifikasi']	= 'ProfilController/actionGetDataSertifikasi';
$route['profil/simpan-sertifikasi']['post']	= 'ProfilController/actionSimpanSertifikasi';
$route['profil/simpan-sertifikasi/(:num)']['post']	= 'ProfilController/actionSimpanSertifikasi/$1';
$route['profil/detail-sertifikasi/(:num)']	= 'ProfilController/actionDetailSertifikasi/$1';
$route['profil/delete-sertifikasi/(:num)']['post']	= 'ProfilController/actionHapusSertifikasi/$1';


# user karir
$route['profil/getdata-karir-lp3i/(:num)']	= 'ProfilController/actionGetDatakarirLp3i/$1';
$route['profil/getdata-karir-lp3i']	= 'ProfilController/actionGetDatakarirLp3i';
$route['profil/simpan-karir']['post']	= 'ProfilController/actionSimpankarir';
$route['profil/simpan-karir/(:num)']['post']	= 'ProfilController/actionSimpankarir/$1';
$route['profil/detail-karir/(:num)']	= 'ProfilController/actionDetailkarir/$1';
$route['profil/get-units/(:num)']	= 'ProfilController/actionGetUnit/$1';
$route['profil/get-depart/(:num)']	= 'ProfilController/actionGetDepartment/$1';
$route['profil/delete-karir/(:num)']['post']	= 'ProfilController/actionHapusKarir/$1';

# user pengalaman
$route['profil/get-data-pengalaman/(:num)']['post'] = 'ProfilController/actionGetDataPengalaman/$1';
$route['profil/get-data-pengalaman'] = 'ProfilController/actionGetDataPengalaman';
$route['profil/detail-pengalaman/(:num)'] = 'ProfilController/actionDetailPengalaman/$1';
$route['profil/simpan-pengalaman']['post'] = 'ProfilController/actionSimpanPengalaman';
$route['profil/simpan-pengalaman/(:num)']['post'] = 'ProfilController/actionSimpanPengalaman/$1';
$route['profil/hapus-pengalaman/(:num)']['post'] = 'ProfilController/actionHapusPengalaman/$1';

# END PROFIL




# START MODULE MASTER ROUTES

# MASTER JABATAN
$route['master/jabatan'] 						= 'master/JabatanController/actionIndex';
$route['master/jabatan/index'] 					= 'master/JabatanController/actionIndex';
$route['master/jabatan/detail/(:num)'] 			= 'master/JabatanController/actionDetail/$1';
$route['master/jabatan/get-data']['post'] 		= 'master/JabatanController/actionGetData';
$route['master/jabatan/tambah']['post'] 		= 'master/JabatanController/actionTambah';
$route['master/jabatan/edit/(:num)']['post'] 	= 'master/JabatanController/actionEdit/$1';
$route['master/jabatan/hapus/(:num)']['post'] 	= 'master/JabatanController/actionHapus/$1';

# MASTER JENIS DOKUMEN
$route['master/jenis-dokumen'] 						= 'master/JenisDokumenController/actionIndex';
$route['master/jenis-dokumen/index'] 				= 'master/JenisDokumenController/actionIndex';
$route['master/jenis-dokumen/detail/(:num)'] 		= 'master/JenisDokumenController/actionDetail/$1';
$route['master/jenis-dokumen/get-data']['post'] 	= 'master/JenisDokumenController/actionGetData';
$route['master/jenis-dokumen/tambah']['post'] 		= 'master/JenisDokumenController/actionTambah';
$route['master/jenis-dokumen/edit/(:num)']['post'] 	= 'master/JenisDokumenController/actionEdit/$1';
$route['master/jenis-dokumen/hapus/(:num)']['post'] = 'master/JenisDokumenController/actionHapus/$1';

# MASTER JENIS DOKUMEN
$route['master/hari-libur'] 						= 'master/HariLiburController/actionIndex';
$route['master/hari-libur/index'] 				= 'master/HariLiburController/actionIndex';
$route['master/hari-libur/detail/(:num)'] 		= 'master/HariLiburController/actionDetail/$1';
$route['master/hari-libur/get-data']['post'] 	= 'master/HariLiburController/actionGetData';
$route['master/hari-libur/tambah']['post'] 		= 'master/HariLiburController/actionTambah';
$route['master/hari-libur/edit/(:num)']['post'] 	= 'master/HariLiburController/actionEdit/$1';
$route['master/hari-libur/hapus/(:num)']['post'] = 'master/HariLiburController/actionHapus/$1';

# MASTER UNIT
$route['master/unit'] 						= 'master/UnitController/actionIndex';
$route['master/unit/index'] 				= 'master/UnitController/actionIndex';
$route['master/unit/detail/(:num)'] 		= 'master/UnitController/actionDetail/$1';
$route['master/unit/get-data']['post'] 		= 'master/UnitController/actionGetData';
$route['master/unit/tambah']['post'] 		= 'master/UnitController/actionTambah';
$route['master/unit/edit/(:num)']['post'] 	= 'master/UnitController/actionEdit/$1';
$route['master/unit/hapus/(:num)']['post']  = 'master/UnitController/actionHapus/$1';

# MASTER KODE SURAT
$route['master/kode-surat'] 					 = 'master/KodeSuratController/actionIndex';
$route['master/kode-surat/index'] 				 = 'master/KodeSuratController/actionIndex';
$route['master/kode-surat/detail/(:num)'] 		 = 'master/KodeSuratController/actionDetail/$1';
$route['master/kode-surat/get-data']['post'] 	 = 'master/KodeSuratController/actionGetData';
$route['master/kode-surat/tambah']['post'] 		 = 'master/KodeSuratController/actionTambah';
$route['master/kode-surat/edit/(:num)']['post']  = 'master/KodeSuratController/actionEdit/$1';
$route['master/kode-surat/hapus/(:num)']['post'] = 'master/KodeSuratController/actionHapus/$1';

# MASTER letter-type
$route['master/letter-type'] 						= 'master/LetterTypeController/actionIndex';
$route['master/letter-type/index'] 					= 'master/LetterTypeController/actionIndex';
$route['master/letter-type/get-data']['post'] 		= 'master/LetterTypeController/actionGetData';
$route['master/letter-type/detail/(:num)'] 			= 'master/LetterTypeController/actionDetail/$1';
$route['master/letter-type/tambah']['post'] 		= 'master/LetterTypeController/actionTambah';
$route['master/letter-type/edit/(:num)']['post'] 	= 'master/LetterTypeController/actionEdit/$1';
$route['master/letter-type/hapus/(:num)']['post'] = 'master/LetterTypeController/actionHapus/$1';

# MASTER TIPE CUTI
$route['master/tipe-cuti'] 						= 'master/TipeCutiController/actionIndex';
$route['master/tipe-cuti/index'] 				= 'master/TipeCutiController/actionIndex';
$route['master/tipe-cuti/get-data']['post'] 	= 'master/TipeCutiController/actionGetData';
$route['master/tipe-cuti/detail/(:num)'] 		= 'master/TipeCutiController/actionDetail/$1';
$route['master/tipe-cuti/tambah']['post'] 		= 'master/TipeCutiController/actionTambah';
$route['master/tipe-cuti/edit/(:num)']['post'] 	= 'master/TipeCutiController/actionEdit/$1';
$route['master/tipe-cuti/hapus/(:num)']['post'] = 'master/TipeCutiController/actionHapus/$1';

# MASTER DESIGNATION
$route['master/designation']						= 'master/DesignationController/actionIndex';
$route['master/designation/index']					= 'master/DesignationController/actionIndex';
$route['master/designation/get-data']['post']		= 'master/DesignationController/actionGetData';
$route['master/designation/detail/(:num)']			= 'master/DesignationController/actionDetail/$1';
$route['master/designation/tambah']['post']			= 'master/DesignationController/actionTambah';
$route['master/designation/edit/(:num)']['post']	= 'master/DesignationController/actionEdit/$1';
$route['master/designation/hapus/(:num)']['post']	= 'master/DesignationController/actionHapus/$1';

# END MODULE MASTER ROUTES

# START NOTIFIKASI
$route['notifikasi'] = 'NotifikasiController/actionIndex';
$route['notifikasi/index'] = 'NotifikasiController/actionIndex';
$route['notifikasi/read/(:num)'] = 'NotifikasiController/actionRead/$1';
# END NOTIFIKASI
