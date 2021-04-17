<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ProfilController extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			'transaksi/userdetail',
			'transaksi/userkeluarga',
			'transaksi/userpendidikan',
			'transaksi/usersertifikasi',
			'transaksi/userkarirlp3i',
			'transaksi/userpengalaman',
			'transaksi/userlampiran',
			'master/pendidikan',
			'master/usergroup',
			'master/group',
			'master/unit',
			'master/grade',
			'master/department',
			'master/relation',
			'master/unitgroup',
			'master/jenisdokumen',
			'master/jabatan',
			'master/gradingtype',
			'master/subgrade',
			'forms/FormChangePassword',
		]);
	}

	public function actionIndex()
	{
		$id = $this->input->get('id');

		$this->layout->view_js = [
			'_partial/sub_js',
			'_partial/js_password',
			'_partial/js_karir',
		];
		$this->layout->view_css = '/_partial/sub_css';
		$this->layout->title = 'Profil';	

		if ($id) {
			$user = $this->user->findOne($id);
			$user_detail = $user->userDetail;
			$user_session = $this->usergroup->getGroupUser($id);
			$session_profil = $user_session[0] ?? '';
			
			$data_login = CekGroupProfil($this->session->userdata('group_id')[0]);
			$data_profil = CekGroupProfil($session_profil);
			$data_lampiran = $this->userlampiran->getLampiranByUser($id);
			$param = $id;
		} else {
			$user = $this->user->findOne($this->session->userdata('identity')->id);
			$user_session = '';
			$user_detail = $user->userDetail;
			$data_login = '';
			$data_profil = '';
			$data_lampiran = $this->userlampiran->getLampiranByUser($this->session->userdata('identity')->id);
			$param = '';
		}

		$data_pendidikan = $this->pendidikan->getListPendidikan();
		$data_Groupunit = $this->unitgroup->getListGroupunit(true);
		$data_unit = $this->unit->getUnitByUserGroup($user->id);
		$data_unit = $this->unit->getListUnits($data_unit, true);
		// $data_grade = $this->grade->getListGrade(true);
		$data_depart = $this->department->getListDepartment();
		$data_hubungan = $this->relation->getListRelation([], true);

		$dokumens = $this->jenisdokumen->findAll(['is_lampiran' => 1]);

		$this->layout->render('index', [
			'user_detail' => $user_detail,
			'user_session' => $user_session,
			'data_pendidikan' => $data_pendidikan,
			// 'data_grade' => $data_grade,
			'data_unit' => $data_unit,
			'data_depart' => $data_depart,
			'data_hubungan' => $data_hubungan,
			'data_Groupunit' => $data_Groupunit,
			'param' => $param,
			'dokumens' => $dokumens,
			'data_lampiran' => $data_lampiran,
			'user' => $user,
			'data_login' => $data_login,
			'data_profil' => $data_profil,
		]);
	}

	public function actionSimpanInfoPersonal()
	{
		$post = $this->input->post('Personal');

		$id = $this->input->get('id');
		if ($id) {
			$redirect = "/profil?id={$id}";
		} else {
			$id = $this->session->userdata('identity')->id;
			$redirect = "/profil";
		}

		$current_data = $this->userdetail->get(['user_id' => $id]);

		if ($post) {
			# Reformat tanggal_lahir
			$post['tanggal_lahir'] = $post['tanggal_lahir'] ? date('Y-m-d', strtotime($post['tanggal_lahir'])) : '';

			# Jika data ada, maka edit. Selain itu insert
			if ($current_data) {
				$save = $this->userdetail->update($post, $current_data->id);
			} else {
				$post['user_id'] = $id;
				$post['created_by'] = $id;

				$save = $this->userdetail->insert($post);
			}

			if ($save) {
				$this->session->set_flashdata('success', 'Penyimpanan data berhasil');
			} else {
				$this->session->set_flashdata('danger', 'Penyimpanan data gagal');
			}
		}

		redirect($redirect, 'refresh');
	}

	public function actionSimpanInfoKeluarga($id = null)
	{
		$post = $this->input->post('Keluarga');
		$user_id = $this->input->get('id');

		if (empty($user_id)) {
			$user_id = $this->session->userdata('identity')->id;
		}

		if ($post) {
			$post['tanggal_lahir'] = $post['tanggal_lahir'] ? date('Y-m-d', strtotime($post['tanggal_lahir'])) : '';
			# Reformat tanggal_lahir

 			if ($id) {
					$save = $this->userkeluarga->update($post, $id);
				} else {
					$post['user_id'] = $user_id;
					$post['created_by'] = $user_id;
					$save = $this->userkeluarga->insert($post);
				}

				if ($save) {
					$result = ['message' => 'Proses simpan berhasil', 'success' => true];
				} else {
					$result = ['message' => 'Proses simpan gagal', 'success' => false];
				}
		}

 		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($result));
	}


	public function actionGetDataInfoKeluarga($id = null) {
    	if (!$this->input->is_ajax_request()) {
			show_error('Halaman tidak valid', 404);exit();
		}

		$model = new Userkeluarga;
		
		if($id){
			$model->user_id = $id;
			$user_session = $this->usergroup->getGroupUser($id);
			$session_profil = $user_session[0] ?? '';
			$data_login = CekGroupProfil($this->session->userdata('group_id')[0]);
			$data_profil = CekGroupProfil($session_profil);
		}else{
			$model->user_id =  $this->session->userdata('identity')->id;
			$user_session = '';
			$data_login = '';
			$data_profil = '';
		}


		$list = $model->get_datatables();
        $data = [];
        $no = $_POST['start'];

        foreach ($list as $field) {

	        if ($data_login >= $data_profil) {
				$button_edit = $this->html->button('<i class=\'fa fa-pencil\'></i>', [
		    		'data-id' => $field->id,
		    		'class' => 'btn btn-warning btn-xs btn-edit-keluarga',
		    		'visible' => (
		    			($this->session->userdata('identity')->id == $field->user_id)
		    			|| $this->helpers->isSuperAdmin()
		    		)
		    	]);

				$button_delete = $this->html->button('<i class=\'fa fa-trash-o\'></i>', [
		    		'data-id' => $field->id,
		    		'class' => 'btn btn-danger btn-xs btn-delete-keluarga',
		    		'visible' => (
		    			($this->session->userdata('identity')->id == $field->user_id)
		    			|| $this->helpers->isSuperAdmin()
		    		)
		    	]);
		    } else {
				$button_edit = $this->html->button('<i class=\'fa fa-pencil\'></i>', [
		    		'data-id' => $field->id,
		    		'class' => 'btn btn-warning btn-xs btn-edit-keluarga',
		    		'visible' => (
		    			($this->session->userdata('identity')->id == $field->user_id)
		    			|| $this->helpers->isSuperAdmin() || $this->helpers->isAdminDirektorat() || $this->helpers->isAdminCabang()
		    		)
		    	]);

				$button_delete = $this->html->button('<i class=\'fa fa-trash-o\'></i>', [
		    		'data-id' => $field->id,
		    		'class' => 'btn btn-danger btn-xs btn-delete-keluarga',
		    		'visible' => (
		    			($this->session->userdata('identity')->id == $field->user_id)
		    			|| $this->helpers->isSuperAdmin() || $this->helpers->isAdminDirektorat() || $this->helpers->isAdminCabang()
		    		)
		    	]);
			}

            $no++;
            $row = [];
            $row[] = $no;
            $row[] = $field->no_ktp;
            $row[] = $field->nama_lengkap;
            $row[] = $field->tanggal_lahir;
            $row[] = $field->jenis_hubungan;
            $row[] = $field->label;
            $row[] = "
            	<div class='text-center'>
            		<button data-id='{$field->id}' class='btn btn-info btn-xs btn-preview-keluarga'><i class='fa fa-eye'></i></button>
	            	". $button_edit ."
	            	". $button_delete ."
            	</div>
            ";
 
            $data[] = $row;
        }
 
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->userkeluarga->count_all($model->user_id,'user_id'),
            "recordsFiltered" => $this->userkeluarga->count_filtered($model->user_id,'user_id'),
            "data" => $data,
        );

         //output dalam format JSON
        echo json_encode($output);
	}

	public function actionDetail($id)
	{
		$model = $this->userkeluarga->get($id);

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($model));
	}



	public function actionHapusInfoKeluarga($id)
	{
		# Spot data before deleting
		$model = $this->userkeluarga->get($id);

		if ($model) {
			// $save = $this->userkeluarga->deletedata($id,'tbl_user_keluarga');
			$save = $this->userkeluarga->delete($id);

			if ($save) {
				$result = [
					'message' => 'Penghapusan data Info Anggota Keluarga berhasil'
				];
			} else {
				$result = [
					'message' => 'Proses hapus data Info Anggota Keluarga gagal, silahkan coba beberapa saat lagi'
				];
			}
		} else {
			$result = [
				'message' => 'Proses hapus data Info Anggota Keluarga gagal, data tidak ditemukan'
			];
		}

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($result));
	}


	public function actionGetDataHistoryPendidikan($id = null)
  {
    if (!$this->input->is_ajax_request()) {
			show_error('Halaman tidak valid', 404);exit();
		}

		$model = new Userpendidikan;

		if($id){
			$model->user_id = $id;
			$user_session = $this->usergroup->getGroupUser($id);
			$session_profil = $user_session[0] ?? '';
			$data_login = CekGroupProfil($this->session->userdata('group_id')[0]);
			$data_profil = CekGroupProfil($session_profil);
		}else{
			$model->user_id =  $this->session->userdata('identity')->id;
			$user_session = '';
			$data_login = '';
			$data_profil = '';
		}


		$list = $model->get_datatables();
        $data = [];
        $no = $_POST['start'];

        foreach ($list as $field) {
        	
        	$tahun_masuk =  $field->tahun_masuk;
        	$tahun_keluar =  $field->tahun_keluar;
        	$jangka_waktu = '-';
        	if ($tahun_masuk && $tahun_keluar) {
        		$jangka_waktu = $tahun_masuk .' - '. $tahun_keluar;
        	}

        	if ($data_login >= $data_profil) {
				$button_edit = $this->html->button('<i class=\'fa fa-pencil\'></i>', [
		    		'data-id' => $field->id,
		    		'class' => 'btn btn-warning btn-xs btn-edit-pendidikan',
		    		'visible' => (
		    			($this->session->userdata('identity')->id == $field->user_id)
		    			|| $this->helpers->isSuperAdmin()
		    		)
		    	]);

				$button_delete = $this->html->button('<i class=\'fa fa-trash-o\'></i>', [
		    		'data-id' => $field->id,
		    		'class' => 'btn btn-danger btn-xs btn-delete-pendidikan',
		    		'visible' => (
		    			($this->session->userdata('identity')->id == $field->user_id)
		    			|| $this->helpers->isSuperAdmin()
		    		)
		    	]);

			} else {
				$button_edit = $this->html->button('<i class=\'fa fa-pencil\'></i>', [
		    		'data-id' => $field->id,
		    		'class' => 'btn btn-warning btn-xs btn-edit-pendidikan',
		    		'visible' => (
		    			($this->session->userdata('identity')->id == $field->user_id)
		    			|| $this->helpers->isSuperAdmin() || $this->helpers->isAdminDirektorat() || $this->helpers->isAdminCabang()
		    		)
		    	]);

				$button_delete = $this->html->button('<i class=\'fa fa-trash-o\'></i>', [
		    		'data-id' => $field->id,
		    		'class' => 'btn btn-danger btn-xs btn-delete-pendidikan',
		    		'visible' => (
		    			($this->session->userdata('identity')->id == $field->user_id)
		    			|| $this->helpers->isSuperAdmin() || $this->helpers->isAdminDirektorat() || $this->helpers->isAdminCabang()
		    		)
		    	]);
			}

            $no++;
            $row = [];
            $row[] = $no;
            $row[] = $field->nama_sekolah;
            $row[] = $field->lokasi;
            $row[] = $jangka_waktu;
            $row[] = $field->jurusan;

            if ($field->ijazah) {
            	$row[] ="<i class='fa fa-check'>";
            } else {
            	$row[] ="<i class='fa fa-times'>";
            }

            $row[] = "
            	<div class='text-center'>
            		<button data-id='{$field->id}' class='btn btn-info btn-xs btn-preview-pendidikan'><i class='fa fa-eye'></i></button>
	            	". $button_edit ."
	            	". $button_delete ."
            	</div>
            ";
 
            $data[] = $row;
        }
 
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->userpendidikan->count_all($model->user_id,'user_id'),
            "recordsFiltered" => $this->userpendidikan->count_filtered($model->user_id,'user_id'),
            "data" => $data,
        );

         //output dalam format JSON
        echo json_encode($output);

  }


	public function actionSimpanHistoryPendidikan($id = null)
	{
		$model = new Userpendidikan;
		$user_id = $this->input->get('id');
		$post = $this->input->post('Pendidikan');
		$id_sekolah = $post['m_pendidikan_id'];
		$getSchool = $this->pendidikan->get($id_sekolah);

		if (empty($user_id)) {
			$user_id = $this->session->userdata('identity')->id;
		}

		$result = [
			'message' => 'Proses simpan gagal, silahkan periksa isian anda kembali',
			'success' => false,
		];
		
		if ($post) {
			$post['user_id'] = $user_id;

			$upload = true;
			if (!empty($_FILES['ijazah']['name'])) {
				$path = './web/uploads/ijazah/';

				if (!file_exists($path)) {
				    mkdir($path, 0777, true);
				}

				$config['upload_path'] = $path;
				$config['allowed_types'] = 'gif|jpg|png|jpeg|pdf';
				$config['max_size'] = '500';
				$config['file_name'] = 'ijazah_'. $getSchool->label .'_'. $user_id;
				
				$this->load->library('upload', $config);

				if ( ! $this->upload->do_upload('ijazah')){
					$result = [
						'message' => $this->upload->display_errors(),
						'success' => false
					];
					$upload = false;
				} else {
					$post['ijazah'] = $path .'/'. $this->upload->data("file_name");
					$upload = true;
				}
			}

			if ($upload) {
				if ($id) {
					$save = $this->userpendidikan->update($post, $id);
				} else {
					$save = $this->userpendidikan->insert($post);
				}

				if ($save) {
					$result = ['message' => 'Proses simpan berhasil', 'success' => true];
				} else {
					$result = ['message' => 'Proses simpan gagal', 'success' => false];
				}
			}
		}

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($result));
	}


	public function actionDetailHistoryPendidikan($id)
	{	

		$model = $this->userpendidikan->get($id);

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($model));
	}

	public function actionHapusHistoryPendidikan($id)
	{
		# Spot data before deleting
		$model = $this->userpendidikan->get($id);

		if ($model) {
			$save = $this->userpendidikan->delete($id);

			if ($save) {
				$result = [
					'message' => 'Penghapusan data History Pendidikan berhasil'
				];
			} else {
				$result = [
					'message' => 'Proses hapus data History Pendidikan gagal, silahkan coba beberapa saat lagi'
				];
			}
		} else {
			$result = [
				'message' => 'Proses hapus data History Pendidikan gagal, data tidak ditemukan'
			];
		}

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($result));
	}

	public function actionGetDataSertifikasi($id = null)
	{
	    if (!$this->input->is_ajax_request()) {
	      show_error('Halaman tidak valid', 404);exit();
	    }

		$model = new Usersertifikasi;

		if($id){
			$model->user_id = $id;
			$user_session = $this->usergroup->getGroupUser($id);
			$session_profil = $user_session[0] ?? '';
			$data_login = CekGroupProfil($this->session->userdata('group_id')[0]);
			$data_profil = CekGroupProfil($session_profil);
		}else{
			$model->user_id =  $this->session->userdata('identity')->id;
			$user_session = '';
			$data_login = '';
			$data_profil = '';
		}


		$list = $model->get_datatables();
		$data = [];
        $no = $_POST['start'];

        foreach ($list as $field) {
        	$tanggal_mulai = def($field, 'tanggal_mulai');
        	$tanggal_selesai = def($field, 'tanggal_selesai');
        	$jangka_waktu = '-';

        	if ($tanggal_mulai && $tanggal_selesai) {
        		$jangka_waktu = date('d/m/Y', strtotime($tanggal_mulai)) .' - '. date('d/m/Y', strtotime($tanggal_selesai));
        	}

        	if ($data_login >= $data_profil) {
				$button_edit = $this->html->button('<i class=\'fa fa-pencil\'></i>', [
		    		'data-id' => $field->id,
		    		'class' => 'btn btn-warning btn-xs btn-edit-sertifikasi',
		    		'visible' => (
		    			($this->session->userdata('identity')->id == $field->user_id)
		    			|| $this->helpers->isSuperAdmin()
		    		)
		    	]);

				$button_delete = $this->html->button('<i class=\'fa fa-trash-o\'></i>', [
		    		'data-id' => $field->id,
		    		'class' => 'btn btn-danger btn-xs btn-delete-sertifikasi',
		    		'visible' => (
		    			($this->session->userdata('identity')->id == $field->user_id)
		    			|| $this->helpers->isSuperAdmin()
		    		)
		    	]);

		    } else {
				$button_edit = $this->html->button('<i class=\'fa fa-pencil\'></i>', [
		    		'data-id' => $field->id,
		    		'class' => 'btn btn-warning btn-xs btn-edit-sertifikasi',
		    		'visible' => (
		    			($this->session->userdata('identity')->id == $field->user_id)
		    			|| $this->helpers->isSuperAdmin() || $this->helpers->isAdminDirektorat() || $this->helpers->isAdminCabang()
		    		)
		    	]);

				$button_delete = $this->html->button('<i class=\'fa fa-trash-o\'></i>', [
		    		'data-id' => $field->id,
		    		'class' => 'btn btn-danger btn-xs btn-delete-sertifikasi',
		    		'visible' => (
		    			($this->session->userdata('identity')->id == $field->user_id)
		    			|| $this->helpers->isSuperAdmin() || $this->helpers->isAdminDirektorat() || $this->helpers->isAdminCabang()
		    		)
		    	]);
			}

            $no++;
            $row = [];
            $row[] = $no;
            $row[] = $field->nama_pelatihan;
            $row[] = $field->penyelenggara;
            $row[] = $field->lokasi;
            $row[] = $jangka_waktu;
            $row[] = "
            	<div class='text-center'>
            		<button data-id='{$field->id}' class='btn btn-info btn-xs btn-preview-sertifikasi'><i class='fa fa-eye'></i></button>
	            	". $button_edit ."
	            	". $button_delete ."
            	</div>
            ";
 
            $data[] = $row;
        }
 
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->usersertifikasi->count_all($model->user_id,'user_id'),
            "recordsFiltered" => $this->usersertifikasi->count_filtered($model->user_id,'user_id'),
            "data" => $data,
        );

         //output dalam format JSON
        echo json_encode($output);

  }

	public function actionSimpanSertifikasi($id = null)
	{	
		$model = new Usersertifikasi;
		$user_id = $this->input->get('id');

		if (empty($user_id)) {
			$user_id = $this->session->userdata('identity')->id;
		}

		$result = [
			'message' => 'Proses simpan gagal, silahkan periksa isian anda kembali',
			'success' => false,
		];

		if ($post = $this->input->post('Sertifikasi')) {
			$post['user_id'] = $user_id;
			$post['tanggal_mulai'] = $post['tanggal_mulai'] ? date('Y-m-d', strtotime($post['tanggal_mulai'])) : '';
			$post['tanggal_selesai'] = $post['tanggal_selesai'] ? date('Y-m-d', strtotime($post['tanggal_selesai'])) : '';

			$upload = true;
			if (!empty($_FILES['lampiran']['name'])) {
				$path = './web/uploads/paklaring';

				if (!file_exists($path)) {
				    mkdir($path, 0777, true);
				}

				$config['upload_path'] = $path;
				$config['allowed_types'] = 'gif|jpg|png|jpeg|pdf';
				$config['max_size'] = '500';
				$config['file_name'] = 'Sertifikasi_'. date('YmdHis') .'_'. $user_id;

				$this->load->library('upload', $config);

				if ( ! $this->upload->do_upload('lampiran')){
					$result = [
						'message' => $this->upload->display_errors(),
						'success' => false
					];
					$upload = false;
				} else {
					$post['file_sertifikasi'] = $path .'/'. $this->upload->data("file_name");
					$upload = true;
				}
			}

			if ($upload) {
				if ($id) {
					$save = $this->usersertifikasi->update($post, $id);
				} else {
					$post['user_id'] = $user_id;
			 		$post['created_by'] = $user_id;
					$save = $this->usersertifikasi->insert($post);
				}

				if ($save) {
					$result = ['message' => 'Proses simpan berhasil', 'success' => true];
				} else {
					$result = ['message' => 'Proses simpan gagal', 'success' => false];
				}
			}
		}

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($result));
		
	}

	public function actionDetailSertifikasi($id)
	{
		$model = $this->usersertifikasi->get($id);

			return $this->output
		        ->set_content_type('application/json')
		        ->set_status_header(200) // Return status
		        ->set_output(json_encode($model));
		
	}


	public function actionHapusSertifikasi($id)
	{
		# Spot data before deleting
		$model = $this->usersertifikasi->get($id);

		if ($model) {
			$save = $this->usersertifikasi->delete($id);

			if ($save) {
				$result = [
					'message' => 'Penghapusan data History Pendidikan berhasil'
				];
			} else {
				$result = [
					'message' => 'Proses hapus data History Pendidikan gagal, silahkan coba beberapa saat lagi'
				];
			}
		} else {
			$result = [
				'message' => 'Proses hapus data History Pendidikan gagal, data tidak ditemukan'
			];
		}

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($result));
	}

	public function actionGetUnit($group_id='')
	{
		$unitsGroup = $this->unitgroup->get($group_id);
		$units = [];
		if($unitsGroup){	
			$unit = $this->unit->getAll(['sbu_id' => $unitsGroup->id]);
			$units = $this->unit->getListUnits($unit);
		}

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($units));
	}

	public function actionGetDepartment($unit_id='')
	{
		$units = $this->unit->get($unit_id);
		$departments = [];

		if($units){	
			$department = $this->department->getAll(['sbu_id' => $units->sbu_id]);
			$departments = $this->department->getListDepartment($department);
		}

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($departments));
	}

	public function actionGetDatakarirLp3i($id = null)
	{
	    if (!$this->input->is_ajax_request()) {
	    	show_error('Halaman tidak valid', 404);exit();
	    }

		$model = new Userkarirlp3i;

		if($id){
			$model->user_id = $id;
			$user_session = $this->usergroup->getGroupUser($id);
			$session_profil = $user_session[0] ?? '';
			$data_login = CekGroupProfil($this->session->userdata('group_id')[0]);
			$data_profil = CekGroupProfil($session_profil);
		}else{
			$model->user_id =  $this->session->userdata('identity')->id;
			$user_session = '';
			$data_login = '';
			$data_profil = '';
		}


		$list = $model->get_datatables();
		
        $data = [];
        $no = $_POST['start'];

        foreach ($list as $field) {

         	if($data_login >= $data_profil) {
				$button_edit = $this->html->button('<i class=\'fa fa-pencil\'></i>', [
		    		'data-id' => $field->id,
		    		'class' => 'btn btn-warning btn-xs btn-edit-karir-lp3i',
		    		'visible' => (
		    			($this->session->userdata('identity')->id == $field->user_id)
		    			|| $this->helpers->isSuperAdmin()
		    		)
		    	]);

				$button_delete = $this->html->button('<i class=\'fa fa-trash-o\'></i>', [
		    		'data-id' => $field->id,
		    		'class' => 'btn btn-danger btn-xs btn-delete-karir-lp3i',
		    		'visible' => (
		    			($this->session->userdata('identity')->id == $field->user_id)
		    			|| $this->helpers->isSuperAdmin()
		    		)
		    	]);
		    } else {
				$button_edit = $this->html->button('<i class=\'fa fa-pencil\'></i>', [
		    		'data-id' => $field->id,
		    		'class' => 'btn btn-warning btn-xs btn-edit-karir-lp3i',
		    		'visible' => (
		    			($this->session->userdata('identity')->id == $field->user_id)
		    			|| $this->helpers->isSuperAdmin() || $this->helpers->isAdminDirektorat() || $this->helpers->isAdminCabang()
		    		)
		    	]);

				$button_delete = $this->html->button('<i class=\'fa fa-trash-o\'></i>', [
		    		'data-id' => $field->id,
		    		'class' => 'btn btn-danger btn-xs btn-delete-karir-lp3i',
		    		'visible' => (
		    			($this->session->userdata('identity')->id == $field->user_id)
		    			|| $this->helpers->isSuperAdmin() || $this->helpers->isAdminDirektorat() || $this->helpers->isAdminCabang()
		    		)
		    	]);
			}

            $no++;
            $row = [];
            $row[] = $no;
            $row[] = $field->nama_unit;
            $row[] = $field->department;
            $row[] = $field->job_title;
            $row[] = $field->golongan;
            $row[] = $field->tanggal_sk;
            $row[] = $field->tanggal_berakhir;
            $row[] = "
             	<div class='text-center'>
             		<button data-id='{$field->id}' class='btn btn-info btn-xs btn-preview-karir-lp3i'><i class='fa fa-eye'></i></button>
	            	". $button_edit ."
	            	". $button_delete ."	
             	</div>
            ";
 
            $data[] = $row;
        }
 
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->userkarirlp3i->count_all($model->user_id,'user_id'),
            "recordsFiltered" => $this->userkarirlp3i->count_filtered($model->user_id,'user_id'),
            "data" => $data,
        );

        //output dalam format JSON
        echo json_encode($output);
    }

	public function actionSimpankarir($id = null)
	{
		$model = new Userkarirlp3i;
		$user_id = $this->input->get('id');

		if (empty($user_id)) {
			$user_id = $this->session->userdata('identity')->id;
		}

		$result = [
			'message' => 'Proses simpan gagal, silahkan periksa isian anda kembali',
			'success' => false,
		];

		if ($post = $this->input->post('Karir')) {
			$post['user_id'] = $user_id;
			$post['tanggal_sk'] = $post['tanggal_sk'] ? date('Y-m-d', strtotime($post['tanggal_sk'])) : '';
			$post['tanggal_berakhir'] = $post['tanggal_berakhir'] ? date('Y-m-d', strtotime($post['tanggal_berakhir'])) : '';

			$upload = true;
			if (!empty($_FILES['lampiran']['name'])) {
				$path = './web/uploads/paklaring';

				if (!file_exists($path)) {
				    mkdir($path, 0777, true);
				}

				$config['upload_path'] = $path;
				$config['allowed_types'] = 'gif|jpg|png|jpeg|pdf';
				$config['max_size'] = '1024';
				$config['file_name'] = 'Surat_Keterangan_'. date('YmdHis') .'_'. $user_id;
				
				$this->load->library('upload', $config);

				if ( ! $this->upload->do_upload('lampiran')){
					$result = [
						'message' => $this->upload->display_errors(),
						'success' => false
					];
					$upload = false;
				} else {
					$post['file_sk'] = $path .'/'. $this->upload->data("file_name");
					$upload = true;
				}
			}

			if ($upload) {
				if ($id) {
					$save = $this->userkarirlp3i->update($post, $id);
				} else {
					$save = $this->userkarirlp3i->insert($post);
				}

				if ($save) {
					$result = ['message' => 'Proses simpan berhasil', 'success' => true];
				} else {
					$result = ['message' => 'Proses simpan gagal', 'success' => false];
				}
			}
		}

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($result));
	}

	public function actionDetailkarir($id)
	{
		$model = $this->userkarirlp3i->findOne($id)->toArray();

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($model));		
	}


	public function actionHapusKarir($id)
	{
		# Spot data before deleting
		$model = $this->userkarirlp3i->getAllwithID(array('tbl_user_karir_lp3i.id' => $id))->row_array();

		if ($model) {
			$save = $this->userkarirlp3i->delete($id);

			if ($save) {
				$result = [
					'message' => 'Penghapusan data History Pendidikan berhasil'
				];
			} else {
				$result = [
					'message' => 'Proses hapus data History Pendidikan gagal, silahkan coba beberapa saat lagi'
				];
			}
		} else {
			$result = [
				'message' => 'Proses hapus data History Pendidikan gagal, data tidak ditemukan'
			];
		}

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($result));
  }
    
	public function actionUbahFoto()
	{
		$id = $this->input->get('id');
		if (empty($id)) {
			$id = $this->session->userdata('identity')->id;
		}

		$user_detail = $this->userdetail->get(['user_id' => $id]);
		$result = [
	    	'message' => 'Ubah foto profil gagal, silahkan lengkapi data personal terlebih dahulu',
	    	'images' => "/web/assets/pages/img/no_avatar.jpg",
    		'success' => false,
	    ];

		if ($user_detail) {
			$path = './web/uploads/profile';

			if (!file_exists($path)) {
			    mkdir($path, 0777, true);
			}

			$config['upload_path']		= $path;
		    $config['allowed_types']	= 'png|jpeg|jpg';
		    $config['file_name']		= 'Foto_Profil_' . $id;
		    $config['overwrite']		= true;
		    $config['max_size']			= 500; // 500KB

		    $this->load->library('upload', $config);

		    if ($this->upload->do_upload('profil_pic')) {
		    	# RESIZE IMAGE WITH JCROP OPTIONS
		    	$uploaded_file = $this->upload->data();
		    	unset($config);
                //Compress Image
                $config['image_library'] = 'gd2';
                $config['source_image'] = $path .'/'. $uploaded_file['file_name'];
                $config['quality'] = "100%";
                $config['maintain_ratio'] = FALSE; 
		    	$config['width'] = 512;
		    	$config['height'] = 512;
                $config['new_image'] = $path .'/'. $uploaded_file['file_name'];
		    	$config['x_axis'] = intval($this->input->post('x1'));
		    	$config['y_axis'] = intval($this->input->post('y1'));
                $config['width'] = intval($this->input->post('w'));
                $config['height'] = intval($this->input->post('h'));
                $this->load->library('image_lib', $config);
                $this->image_lib->crop();

		    	$update = $this->userdetail->update([
		    		'profile_pic' => $path .'/'. $this->upload->data("file_name")
		    	], $user_detail->id);

		    	if ($update) {
		    		if (empty($this->input->get('id'))) {
		    			$this->session->set_userdata('detail_identity', $this->userdetail->get(['user_id' => $id]));
		    		}

			        $result = [
				    	'message' => 'Ubah foto profil berhasil',
				    	'images' => $path .'/'. $this->upload->data("file_name"),
			    		'success' => true,
				    ];
		    	} else {
		    		$result = [
				    	'message' => 'Proses simpan foto ke database gagal. Silahkan coba lagi',
			    		'images' => ($user_detail->profile_pic ? $user_detail->profile_pic : "/web/assets/pages/img/no_avatar.jpg"),
			    		'success' => false,
				    ];
		    	}

		    } else {
		    	$result = [
			    	'message' => $this->upload->display_errors(),
			    	'images' => ($user_detail->profile_pic ? $user_detail->profile_pic : "/web/assets/pages/img/no_avatar.jpg"),
			    	'success' => false,
			    ];
		    }
		}

	    return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($result));
	}

	public function actionGetDataPengalaman($id  = null)
	{
		$model = new Userpengalaman;

		if($id){
			$model->user_id = $id;
			$user_session = $this->usergroup->getGroupUser($id);
			$session_profil = $user_session[0] ?? '';
			$data_login = CekGroupProfil($this->session->userdata('group_id')[0]);
			$data_profil = CekGroupProfil($session_profil);
		}else{
			$model->user_id =  $this->session->userdata('identity')->id;
			$user_session = '';
			$data_login = '';
			$data_profil = '';
		}


		$list = $model->get_datatables();
        $data = [];
        $no = $_POST['start'];

        foreach ($list as $key => $field) {
        	$tanggal_mulai = def($field, 'tanggal_mulai');
        	$tanggal_selesai = def($field, 'tanggal_selesai');
        	$jangka_waktu = '-';

        	if ($tanggal_mulai && $tanggal_selesai) {
        		$jangka_waktu = date('d/m/Y', strtotime($tanggal_mulai)) .' - '. date('d/m/Y', strtotime($tanggal_selesai));
        	}

        	if ($data_login >= $data_profil) {
				$button_edit = $this->html->button('<i class=\'fa fa-pencil\'></i>', [
		    		'data-id' => $field->id,
		    		'class' => 'btn btn-warning btn-xs btn-edit-experience',
		    		'visible' => (
		    			($this->session->userdata('identity')->id == $field->user_id)
		    			|| $this->helpers->isSuperAdmin()
		    		)
		    	]);

				$button_delete = $this->html->button('<i class=\'fa fa-trash-o\'></i>', [
		    		'data-id' => $field->id,
		    		'class' => 'btn btn-danger btn-xs btn-delete-experience',
		    		'visible' => (
		    			($this->session->userdata('identity')->id == $field->user_id)
		    			|| $this->helpers->isSuperAdmin()
		    		)
		    	]);
		    } else {
				$button_edit = $this->html->button('<i class=\'fa fa-pencil\'></i>', [
		    		'data-id' => $field->id,
		    		'class' => 'btn btn-warning btn-xs btn-edit-experience',
		    		'visible' => (
		    			($this->session->userdata('identity')->id == $field->user_id)
		    			|| $this->helpers->isSuperAdmin() || $this->helpers->isAdminDirektorat() || $this->helpers->isAdminCabang()
		    		)
		    	]);

				$button_delete = $this->html->button('<i class=\'fa fa-trash-o\'></i>', [
		    		'data-id' => $field->id,
		    		'class' => 'btn btn-danger btn-xs btn-delete-experience',
		    		'visible' => (
		    			($this->session->userdata('identity')->id == $field->user_id)
		    			|| $this->helpers->isSuperAdmin() || $this->helpers->isAdminDirektorat() || $this->helpers->isAdminCabang()
		    		)
		    	]);
			}

            $row = [];
            $row[] = ($key + 1);
            $row[] = $field->nama_perusahaan;
            $row[] = $jangka_waktu;
            $row[] = $field->jabatan;
            $row[] = $field->bagian;
            $row[] = "
            	<div class='text-center'>
	            	<button data-id='{$field->id}' class='btn btn-info btn-xs btn-preview-experience'><i class='fa fa-eye'></i></button> 
	            	". $button_edit ." 
	            	". $button_delete ." 
            	</div>
            ";
 
            $data[] = $row;
        }
 
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->userpengalaman->count_all($model->user_id,'user_id'),
            "recordsFiltered" => $this->userpengalaman->count_filtered($model->user_id,'user_id'),
            "data" => $data,
        );

        //output dalam format JSON
        echo json_encode($output);
	}

	public function actionDetailPengalaman($id)
	{
		$model = $this->userpengalaman->get($id);

		$model->tanggal_mulai = $model->tanggal_mulai ? date('d-m-Y', strtotime($model->tanggal_mulai)) : '';
		$model->tanggal_selesai = $model->tanggal_selesai ? date('d-m-Y', strtotime($model->tanggal_selesai)) : '';

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($model));
	}

	public function actionSimpanPengalaman($id = null)
	{
		$model = new Userpengalaman;
		$user_id = $this->input->get('id');

		if (empty($user_id)) {
			$user_id = $this->session->userdata('identity')->id;
		}

		$result = [
			'message' => 'Proses simpan gagal, silahkan periksa isian anda kembali',
			'success' => false,
		];

		if ($post = $this->input->post('Pengalaman')) {
			$post['user_id'] = $user_id;
			$post['tanggal_mulai'] = $post['tanggal_mulai'] ? date('Y-m-d', strtotime($post['tanggal_mulai'])) : '';
			$post['tanggal_selesai'] = $post['tanggal_selesai'] ? date('Y-m-d', strtotime($post['tanggal_selesai'])) : '';

			$upload = true;
			if (!empty($_FILES['lampiran']['name'])) {
				$path = './web/uploads/paklaring';

				if (!file_exists($path)) {
				    mkdir($path, 0777, true);
				}

				$config['upload_path'] = $path;
				$config['allowed_types'] = 'gif|jpg|png|jpeg|pdf';
				$config['max_size'] = '1024';
				$config['file_name'] = 'Paklaring_'. date('YmdHis') .'_'. $user_id;
				
				$this->load->library('upload', $config);

				if ( ! $this->upload->do_upload('lampiran')){
					$result = [
						'message' => $this->upload->display_errors(),
						'success' => false
					];
					$upload = false;
				} else {
					$post['paklaring'] = $path .'/'. $this->upload->data("file_name");
					$upload = true;
				}
			}

			if ($upload) {
				if ($id) {
					$save = $this->userpengalaman->update($post, $id);
				} else {
					$save = $this->userpengalaman->insert($post);
				}

				if ($save) {
					$result = ['message' => 'Proses simpan berhasil', 'success' => true];
				} else {
					$result = ['message' => 'Proses simpan gagal', 'success' => false];
				}
			}
		}

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($result));
	}

	public function actionHapusPengalaman($id)
	{
		$model = $this->userpengalaman->get($id);

		if ($model) {
			$save = $this->userpengalaman->delete($id);

			if ($save) {
				$result = [
					'message' => 'Penghapusan data pengalaman berhasil'
				];
			} else {
				$result = [
					'message' => 'Proses hapus data pengalaman gagal, silahkan coba beberapa saat lagi'
				];
			}
		} else {
			$result = [
				'message' => 'Proses hapus data pengalaman gagal, data tidak ditemukan'
			];
		}

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($result));
	}

	public function actionSimpanDokumen()
	{
		$post_dokumen = $this->input->post('Dokumen');
		$dokumens = [];
		$file_fail = [];

		$user_id = $this->input->get('id');
		$redirect = "/profil?id={$user_id}#tab_1_7";

		if (empty($user_id)) {
			$user_id = $this->session->userdata('identity')->id;
			$redirect = '/profil#tab_1_7';
		}

		if ($post_dokumen) {
			$this->load->library('upload');

			try {
				$this->db->trans_begin();

				foreach ($post_dokumen as $key => $value) {
					$dokumen_id = $this->jenisdokumen->getIdByKode($key);
					$dokumens[$key] = [
						'm_jenis_dokumen_id' => $dokumen_id,
						'nomor_dokumen' => $value,
						'user_id' => $user_id
					];

					if (!empty($_FILES[$key]['name'])) {
						$path = "./web/uploads/dokumen_pribadi/{$key}";

						if (!file_exists($path)) {
						    mkdir($path, 0777, true);
						}

						$config['upload_path'] = $path;
						$config['allowed_types'] = 'jpg|png|jpeg|pdf';
						$config['max_size'] = '1024';
						$config['file_name'] = "{$key}_" . $user_id;
						$config['overwrite'] = true;
						
						$this->upload->initialize($config);

						if ($this->upload->do_upload($key)){
							$dokumens[$key]['path_dokumen'] = $path .'/'. $this->upload->data("file_name");
						}
					}

					$model = $this->userlampiran->findOne(['user_id' => $user_id, 'm_jenis_dokumen_id' => $dokumen_id]);
					$save = false;

					if ($model) {
						$save = $this->userlampiran->update($dokumens[$key], $model->id);
					} else {
						$save = $this->userlampiran->insert($dokumens[$key]);
					}

					if ($save === false) {
						$file_fail[] = strtoupper(str_replace('_', ' ', $key));
					}
				}

				if (!empty($file_fail)) {
					$this->session->set_flashdata('warning', 'Dokumen '. implode(', ', $file_fail) .' gagal dilakukan penyimpanan');
				} else {
					$this->session->set_flashdata('success', 'Dokumen berhasil disimpan');
				}

				$this->db->trans_commit();

			} catch (Exception $e) {
				$this->session->set_flashdata('danger', 'Proses simpan gagal, silahkan coba beberapa saat lagi. Jika masih gagal, silahkan hubungi Administrator');
				$this->db->trans_rollback();
			}
		}


		redirect($redirect, 'refresh');
	}

	public function actionUbahPassword($id)
	{
		$user = $this->user->findOne($id);

		if ($post = $this->input->post('ChangePassword')) {
			$model = new FormChangePassword;
			$model->setAttributes($post);

			if ($model->validate()) {
				$user->password = password_hash($post['new_password'], PASSWORD_DEFAULT);

				if ($user->save(false)) {
					$result = [
						'message' => 'Perubahan password berhasil, akun anda akan logout',
						'status' => 'success'
					];
				} else {
					$result = [
						'message' => 'Perubahan password gagal, silahkan coba lagi',
						'status' => 'error'
					];
				}
			} else {
				$result = [
					'message' => implode('; ', array_values($model->getErrors())),
					'status' => 'error'
				];
			}
		}

		if (empty($result)) {
			$result = [
				'message' => 'Permohonan perubahan password gagal, mohon periksa isian anda',
				'status' => 'info'
			];
		}

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($result));
	}

}
/* End of file ProfilController.php */
/* Location: ./application/controllers/ProfilController.php */