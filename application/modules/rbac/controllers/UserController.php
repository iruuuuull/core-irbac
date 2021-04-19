<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserController extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			'master/user',
			'master/group',
			'master/usergroup',
			'master/department',
			'master/unit',
			'master/gradingtype',
			'master/status',
			'master/unitgroup',
			'master/jabatan',
			'master/subgrade',
			'master/designation',
			'transaksi/userdetail',
		]);
	}

	public function actionIndex()
	{
		$this->layout->view_js = ['js', 'js_form'];
		$this->layout->view_css = 'css';

		$statuses = $this->status->getListStatus(true);

		$this->layout->render('index', [
			'statuses' => $statuses,
		]);
	}

	public function actionGetDepartment($unit_id='')
	{
		$units = $this->unit->get($unit_id);
		$departments = [];

		if ($units) {
			$department = $this->department->getAll(['sbu_id' => $units->sbu_id]);
			$departments = $this->department->getListDepartment($department);
		}

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($departments));
	}

	public function actionGetData()
	{
		if (!$this->input->is_ajax_request()) {
			show_error('Halaman tidak valid', 404);exit();
		}

		$list = $this->user->get_datatables();
        $data = [];
        $no = $_POST['start'];

        foreach ($list as $field) {
            $no++;
            $row = [];
            $row[] = $no;
            $row[] = $field->username;
            $row[] = $field->email;
            $row[] = $field->status == 1 ? 'Aktif' : 'NonAktif';
            $row[] = date('d-m-Y H:i:s', strtotime($field->created_at));
            $row[] = "
            	<div class='text-center'>
	            	<button data-id='{$field->id}' class='btn btn-info btn-xs btn-preview'><i class='fa fa-eye'></i></button> 
	            	<a href='". base_url("/rbac/user/edit/{$field->id}") ."' class='btn btn-warning btn-xs'><i class='fa fa-pencil-alt'></i></a> 
	            	<button data-id='{$field->id}' class='btn btn-danger btn-xs btn-delete'><i class='fa fa-trash-alt'></i></button>
	            	<button data-id='{$field->id}' class='btn btn-primary btn-xs btn-add-detail' title='Tambah Detail'>
	            		<i class='fa fa-share'></i></button>
            	</div>
            ";
 
            $data[] = $row;
        }
 
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->user->count_all(),
            "recordsFiltered" => $this->user->count_filtered(),
            "data" => $data,
        );

        //output dalam format JSON
        echo json_encode($output);
	}

	public function actionCreate()
	{
		$groups = $this->group->getListGroup($this->session->userdata('group_id'));

		if ($post = $this->input->post()) {
			$user = $post['User'];
			$user_group = def($post, 'UserGroup');

			if ($post['password_conf'] == $user['password']) {
				$user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);
				$user['status'] = 1;

				$this->db->trans_start();
				try {
					$save_user = $this->user->insert($user);

					if ($save_user && $user_group) {
						foreach ($user_group as $key => $value) {
							$this->usergroup->insert([
								'user_id' => $save_user,
								'group_id' => $value,
							]);
						}
					}

					$this->session->set_flashdata('success', 'Tambah user berhasil');
					$this->db->trans_commit();

				} catch (Exception $e) {
					$this->db->trans_rollback();
					$this->session->set_flashdata('danger', 'Tambah user gagal');
				}

				redirect('/rbac/user', 'refresh');

			} else {
				$this->session->set_flashdata('warning', 'Password dan password konfirmasi harus sama');
			}
		}

		$this->layout->render('create', [
			'groups' => $groups,
		]);
	}

	public function actionDetail($id)
	{
		$user_detail = $this->userdetail->get(['user_id' => $id]);
		$units = $this->unit->getUnitByUserGroup($id);

		$result = [
			'user_detail' => null,
			'units' => $this->unit->getListUnits($units),
			'atasans' => null,
		];

		if ($user_detail) {
			// $atasans = $this->userdetail->getListAtasanByIdDetail($user_detail->id);

			if ($user_detail) {
				$user_detail->tanggal_gabung = (int)strtotime($user_detail->tanggal_gabung) > 0 ? 
					date('d-m-Y', strtotime($user_detail->tanggal_gabung)) : '';
				$user_detail->tanggal_selesai = (int)strtotime($user_detail->tanggal_selesai) > 0 ? 
					date('d-m-Y', strtotime($user_detail->tanggal_selesai)) : '';
			}

			$result = [
				'user_detail' => $user_detail,
				'units' => $this->unit->getListUnits($units),
				'atasans' => null,
			];
		}

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($result));
	}

	public function actionGetAtasan()
	{
		$id_unit = $this->input->post('id_unit');
		$id_department = $this->input->post('id_department');
		$id_grade = $this->input->post('id_grade');
		$golongan = $this->input->post('golongan');

		$atasans = [];

		if (
			!empty($id_unit) 
			&& !empty($id_department) 
			&& !empty($id_grade)
			&& !empty($golongan)
		) {
			$jabatan = $this->jabatan->findOne($id_grade);

			$atasans = $this->userdetail->getListAtasans($id_unit, $id_department, $id_grade, $golongan);
		}

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($atasans));
	}

	// public function setDataNIK($setUnitID,$setStatusID,$setJoinDate)
	// {
	// 	// PENGECEKAN NOMOR URUT AKHIR CABANG
	// 	$count_params = "(unit_id = ".$setUnitID. " AND nik != '' )";
	// 	$count_query = $this->userdetail->SelData($count_params,'tbl_user_detail');
	// 	$count = $count_query->num_rows();
	// 	$set_count = $count+1;

	// 	// PENGECEKAN KODE SBU & KODE UNIT
	// 	$sbu_query = array('tbl_m_unit.id' => $setUnitID);
	// 	$sbu_query = $this->userdetail->CariSBU($sbu_query);

	// 	foreach ($sbu_query as $keyx => $valuex) {
	// 		$sbu_code = $valuex['kode_sbu'];
	// 		$unit_code = $valuex['kode_unit'];
	// 	}

	// 	// PENGECEKAN STATUS KERJA
	// 	$stats_kj_query = array('tbl_user_detail.status_id' => $setStatusID);
	// 	$stats_kj_query = $this->userdetail->CariStatus($stats_kj_query);

	// 	foreach ($stats_kj_query as $keyx => $valuex) {
	// 		$status = $valuex['kd_status'];
	// 	}

	// 	// PENGECEKAN JUMLAH DIGIT
	// 	if(strlen($set_count) == 1){
	// 		$set_num = "000".$set_count;
	// 	}elseif(strlen($set_count) == 2){
	// 		$set_num = "00".$set_count;
	// 	}elseif(strlen($set_count) == 3){
	// 		$set_num = "0".$set_count;
	// 	}else{
	// 		$set_num = $set_count;
	// 	}

	// 	// AMBIL TAHUN & TANGGAL 
	// 	$month = date('m', strtotime($setJoinDate));
	// 	$year = date('Y', strtotime($setJoinDate));
	// 	$sub_year = substr($year, -2,2);
	// 	$sub_date = $month.$sub_year;

	// 	$dataNIK = $set_num.".".$sub_date.".".$sbu_code.".".$unit_code.".".$status;
	// 	return $dataNIK;
	// }

	// public function actionSimpanDetail($id)
	// {
	// 	$user_detail = $this->userdetail->get(['user_id' => $id]);

	// 	if ($post = $this->input->post('UserDetail')) {
	// 		$post['user_id'] = $id;

	// 		$set_nik = $this->setDataNIK($post['unit_id'],$post['status_id'],$post['tanggal_gabung']);

	// 		$post['nik'] = $set_nik;
	// 		$post['tanggal_gabung'] = $post['tanggal_gabung'] ? date('Y-m-d', strtotime($post['tanggal_gabung'])) : '';
	// 		$post['tanggal_selesai'] = $post['tanggal_selesai'] ? date('Y-m-d', strtotime($post['tanggal_selesai'])) : '';

	// 		if ($user_detail) {
	// 			$save = $this->userdetail->update($post, $user_detail->id);
	// 		} else {
	// 			$save = $this->userdetail->insert($post);
	// 		}

	// 		if ($save) {
	// 			$this->session->set_flashdata('success', 'Proses simpan data berhasil');
	// 		} else {
	// 			$this->session->set_flashdata('danger', 'Proses simpan data gagal');
	// 		}
	// 	}

	// 	redirect('/rbac/user/', 'refresh');
	// }

	public function actionSimpanDetail($id)
	{
		$user_detail = $this->userdetail->get(['user_id' => $id]);
		if ($post = $this->input->post('UserDetail')) {
			$post['user_id'] = $id;

			// PENGECEKAN NOMOR URUT AKHIR CABANG
			$count_params = "(unit_id = ".$post['unit_id']. " AND nik != '' )";
			$count_query = $this->userdetail->SelData($count_params,'tbl_user_detail');
			$count = $count_query->num_rows();

			// PENGECEKAN KODE SBU & KODE UNIT
			$sbu_query = array('tbl_m_unit.id' => $post['unit_id']);
			$sbu_query = $this->userdetail->CariSBU($sbu_query);

			foreach ($sbu_query as $keyx => $valuex) {
				$sbu_code = $valuex['kode_sbu'];
				$unit_code = $valuex['kode_unit'];
			}

			// PENGECEKAN STATUS KERJA
			$stats_kj_query = array('tbl_user_detail.status_id' => $post['status_id']);
			$stats_kj_query = $this->userdetail->CariStatus($stats_kj_query);

			foreach ($stats_kj_query as $keyx => $valuex) {
				$status = $valuex['kd_status'];
			}

			// PENGECEKAN JUMLAH DIGIT
			if(strlen($count) == 1){
				$set_num = "000".$count;
			}elseif(strlen($count) == 2){
				$set_num = "00".$count;
			}elseif(strlen($count) == 3){
				$set_num = "0".$count;
			}else{
				$set_num = $count;
			}

			// AMBIL TAHUN & TANGGAL 
			$month = date('m', strtotime($post['tanggal_gabung']));
			$year = date('Y', strtotime($post['tanggal_gabung']));
			$sub_year = substr($year, -2,2);
			$sub_date = $month.$sub_year;

			# Set autonumbering nik jika kosong
			// if (empty($post['nik'])) {
			// 	$post['nik'] = $set_num.".".$sub_date.".".$sbu_code.".".$unit_code.".".$status;
			// }

			$post['tanggal_gabung'] = $post['tanggal_gabung'] ? date('Y-m-d', strtotime($post['tanggal_gabung'])) : '';
			$post['tanggal_selesai'] = $post['tanggal_selesai'] ? date('Y-m-d', strtotime($post['tanggal_selesai'])) : '';

			if ($user_detail) {
				$save = $this->userdetail->update($post, $user_detail->id);
			} else {
				$save = $this->userdetail->insert($post);
			}

			if ($save) {
				$this->session->set_flashdata('success', 'Proses simpan data berhasil');
			} else {
				$this->session->set_flashdata('danger', 'Proses simpan data gagal');
			}
		}

		redirect('/rbac/user/', 'refresh');
	}

	public function actionEdit($id)
	{
		$groups = $this->group->getListGroup($this->session->userdata('group_id'));
		$user = $this->user->get($id);
		$user_group = $this->usergroup->getGroupUser($user->id);

		if ($post = $this->input->post()) {
			$user_post = $post['User'];
			$user_group = $post['UserGroup'];

			if (!empty($user_post['password']) && ($post['password_conf'] == $user_post['password'])) {
				$user_post['password'] = password_hash($user_post['password'], PASSWORD_DEFAULT);
			} else {
				$user_post['password'] = $user->password;
			}

			$this->db->trans_start();
			try {
				$save_user = $this->user->update($user_post, $id);

				if ($save_user) {
					$this->usergroup->delete(['user_id' => $id]);

					foreach ($user_group as $key => $value) {

						$this->usergroup->insert([
							'user_id' => $id,
							'group_id' => $value,
						]);
					}
				}

				$this->session->set_flashdata('success', 'Edit user berhasil');
				$this->db->trans_commit();

			} catch (Exception $e) {
				$this->db->trans_rollback();
				$this->session->set_flashdata('danger', 'Edit user gagal');
			}

			redirect('/rbac/user', 'refresh');
		}

		$this->layout->render('create', [
			'groups' => $groups,
			'user' => $user,
			'user_group' => $user_group,
		]);
	}

	public function actionHapus($id, $deactive = false)
	{
		$user = $this->user->get($id);

		if ($user) {
			$delete = false;

			if ($deactive == 'true') {
				$delete = $this->user->update(['status' => 0], $id);
			} else {
				$delete = $this->user->delete($id);
			}

			if ($delete) {
				$result = ['message' => 'Proses hapus berhasil'];
			} else {
				$result = ['message' => 'Proses hapus gagal'];
			}
		} else {
			$result = ['message' => 'Data tidak ditemukan'];
		}

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($result));
	}

	public function actionGetGrade()
	{
		$grading_type = $this->input->get('grading_type');
		$lists = [];

		$result = [
			'message' => 'Data kosong',
			'data' => $lists
		];

		if ($grading_type) {
			$result = [
				'message' => '',
				'data' => $this->gradingtype->listGradingType($grading_type)
			];
		}

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($result));
	}

	public function actionGetDesignation()
	{
		$kelompok = $this->input->get('kelompok');
		$lists = [];

		$result = [
			'message' => 'Data kosong',
			'data' => $lists
		];

		if ($kelompok) {
			$result = [
				'message' => '',
				'data' => $this->designation->getListDesignation($kelompok)
			];
		}

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($result));
	}

	public function actionGetKelasJabatan()
	{
		$grading_type = $this->input->get('grading_type');
		$grade = $this->input->get('grade');
		$golongan = $this->input->get('golongan');

		$result = [
			'message' => 'Data kosong',
			'data' => [
				'kelas' => '',
				'jabatan' => ''
			]
		];

		if ($grading_type && $grade && $golongan) {
			$kelas = '';

			if ($grading_type == Gradingtype::JENIS_STRUKTURAL) {
				$kelas = $this->jabatan->getKelasJabatan($grade);
			} elseif ($grading_type == Gradingtype::JENIS_FUNGSIONAL) {
				$kelas = $this->subgrade->getKelasJabatan($grade, $golongan);
			}

			$jabatan = $this->jabatan->findOne($grade);

			$result = [
				'message' => '',
				'data' => [
					'kelas' => $kelas,
					'jabatan' => def($jabatan, 'desc', '')
				]
			];
		}

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($result));
	}

	public function actionGetGolongan($grade)
	{
		$result = [
			'message' => 'Data kosong',
			'data' => []
		];

		$golongans = Subgrade::listGolongan($grade);

		if ($golongans) {
			$result = [
				'message' => '',
				'data' => $golongans
			];
		}

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($result));
	}

}

/* End of file UserController.php */
/* Location: ./application/modules/rbac/controllers/UserController.php */