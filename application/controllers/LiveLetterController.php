<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LiveLetterController extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			'transaksi/letter',
			'master/kodesurat',
			'master/lettertype',
			'master/letterowner',
		]);
	}

	public function actionIndex()
	{	
		

		$unit_sesi = $this->session->userdata('detail_identity')->unit_id;
		
		// 0 jika ada user yg belum mengupload surat diblok
		// 1 jika unit collage dan jika tidak ada unit
		// 2 jika tidak ada kendala bisa mendapatakan penomoran surat

		if($unit_sesi == null){	
			$list_type = '';
			$list_owner = '';
			$list_code = '';
			$is_attach = 1;
			$letter_no = '';
		}else if($unit_sesi == 2){
			$list_type = '';
			$list_owner = '';
			$list_code = '';
			$is_attach = 0;
			$letter_no = '';
		}else{	
			
			$letowner = $this->letter->get(['is_attach' => 0 , 'created_by' => $this->session->userdata('identity')->id]);
			if($letowner){
				$is_attach = $letowner->is_attach;
			}else{
				$is_attach = 2;
			}


			$list_code = $this->kodesurat->getListCode($unit_sesi,true);
			$list_type = $this->lettertype->getListType(true);
			
			# Start Generate Penomoran Surat #
			$list_owner = $this->letterowner->getLetter($unit_sesi);
			// print_r($list_owner);
			$no_urut = $list_owner->last_letter_no + 1;
			$romawi = $this->helpers->formatGetRomawi(date('m'));
			$letter_no = Format_Letter($no_urut,$romawi,$list_owner->year);
			# End Generate Penomoran Surat #
		}


		$this->layout->title = 'Create Letter';
		$this->layout->view_js = '_partial/sub_js';
		$this->layout->view_css = '_partial/sub_css';
		
		$this->layout->render('index', [
			'list_code' => $list_code,
			'list_type' => $list_type,
			'letter_no' => $letter_no,
			'is_attach' => $is_attach
		]);
	}

	public function actionGetDataCreate()
	{
		if (!$this->input->is_ajax_request()) {
			show_error('Halaman tidak valid', 404);exit();
		}
		
		$model = new Letter;

		if($this->helpers->isSuperAdmin() || $this->helpers->isAdminDirektorat() || $this->helpers->isAdminCabang() ){
			$model->unit_id = $this->session->userdata('detail_identity')->unit_id;
			$param = $model->unit_id;
			$name_field = 'tbl_letter_reg.unit_id';
		}else{
			$model->unit_id = $this->session->userdata('detail_identity')->unit_id;
			$model->user_id = $this->session->userdata('identity')->id;
			$param = $model->user_id;
			$name_field = 'tbl_letter_reg.created_by';
		}



		$list = $model->get_datatables();
		$data = [];
		$no = $_POST['start'];

		foreach ($list as $field) {
			
			if($field->attachment){
				$attach = substr($field->attachment,20);	
			}else{
				$attach = '-';
			}

			$no++;
			$row = [];
			$row[] = $no;
			$row[] = date('d F Y', strtotime($field->letter_date));
			$row[] = $field->letter_no;
			$row[] = $field->letter_from;
			$row[] = $field->letter_to;
			$row[] = $field->desc;
			$row[] = $attach;

			$row[] = "
			<div class='text-center'>
				<button data-id='{$field->id}' class='btn btn-danger btn-xs btn-upload-create'><i class='fa fa-upload'></i></button>
				<button data-id='{$field->id}' class='btn btn-warning btn-xs btn-edit-create'><i class='fa fa-pencil'></i></button>
			</div>
			";

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->letter->count_all($param,$name_field),
			"recordsFiltered" => $this->letter->count_filtered($param,$name_field),
			"data" => $data,
		);

         //output dalam format JSON
		echo json_encode($output);

	}


	public function actionDetailCreate($id)
	{	

		$model = $this->letter->get($id);
		$allcode = explode("/", $model->letter_no);
		$getSbu = $this->kodesurat->get(['sbu_code2' => $allcode[4]]);

		if($getSbu){
			$sbu = $getSbu->sbu_code2;
		}else{
			$getSbu_2 = $this->kodesurat->get(['sbu_code' => $allcode[3]]);
			$sbu = $getSbu_2->sbu_code2;
		}

		if($model){
			$result = [
				'letter_date' => $model->letter_date ? date('d-m-Y', strtotime($model->letter_date)) : null,
				'tes' => $model->letter_from ?? null,
				'letter_no' => $model->letter_no ?? null,
				'letter_to' => $model->letter_to ?? null,
				'attachment' => $model->attachment ?? null,
				'sbu_code2' => $sbu ?? null,
				'kode_sku' => $allcode[2] ?? null,
			];
		}

		return $this->output
			->set_content_type('application/json')
	    	->set_status_header(200) // Return status
	    	->set_output(json_encode($result));

	}

	public function actionRomawi()
	{	

		$tanggal = $this->input->post('tanggal');
		$letter = $this->input->post('letter');
		$sbu_code2 = $this->input->post('sbu');
		
		# Generate Bulan Penomoran Surat Sesuai Pemilihan CMB User
		$explod = explode("/",$letter);
		$explod_tanggal = explode("-",$tanggal);
		$romawi = $this->helpers->formatGetRomawi($explod_tanggal[1]);
		if(strpos($sbu_code2,"-")){
			$replace = [4 => $romawi , 5 =>  $explod_tanggal[2]];
		}else{
			$replace = [5 => $romawi , 6 => $explod_tanggal[2]];
		}
		# End Generate

		$array_rep = array_replace($explod,$replace);
		$no_surat = implode('/', $array_rep);

		return $this->output
			->set_content_type('application/json')
	    	->set_status_header(200) // Return status
	    	->set_output(json_encode($no_surat));

	}

	public function actionGetCode()
	{	
		$sbu_code2 = $this->input->post('sbu');
		$letter = $this->input->post('letter');
		$tanggal = $this->input->post('tanggal');
		$model = $this->kodesurat->getOwner(['sbu_code2' => $sbu_code2]);

		# Generate Penomoran Surat Sesuai Pemilihan CMB User
		$explod = explode("/",$letter);
		$explod_tanggal = explode("-",$tanggal);
		$romawi = $this->helpers->formatGetRomawi($explod_tanggal[1]);
		
		if($sbu_code2){
		// jika sbu_code2 mengandung string "-"
			if(strpos($model->sbu_code2,"-")){
				$no_urut = $explod[0];
				$hold = $model->holding_code;
				$surat = $explod[2];
				$sbu = $model->sbu_code;
				$rom = $romawi;
				$year = $explod_tanggal[2];

				$no_surat = $no_urut.'/'.$hold.'/'.$surat.'/'.$sbu.'/'.$rom.'/'.$year;
			}else{
				$replace = [1 => $model->holding_code , 3 => $model->sbu_code, 4 => $model->sbu_code2 , 5 => $romawi , 6 => $explod_tanggal[2]];
				$array_rep = array_replace($explod,$replace);
				$no_surat = implode('/', $array_rep);
			}
		}else{
			$no_surat = $letter;
		}

		# End Generate

		return $this->output
			->set_content_type('application/json')
	    	->set_status_header(200) // Return status
	    	->set_output(json_encode($no_surat));
	}


	public function actionSimpanLetter($id = null)
	{
		$model = new Letter;

		$unit_sesi = $this->session->userdata('detail_identity')->unit_id;
		

		$post = $this->input->post('letter');
		$file_name = str_replace('/', '-', $post['letter_no']);

		$desc = $this->lettertype->get(['letter_code' => $post['desc']]);
		$list_owner = $this->kodesurat->getOwner(['sbu_code2' => $post['letter_from']]);

		$result = [
			'message' => 'Proses simpan gagal, silahkan periksa isian anda kembali',
			'success' => false,
		];

		if ($post) {
			$post['letter_date'] = $post['letter_date'] ? date('Y-m-d', strtotime($post['letter_date'])) : '';
			$post['created_by'] = $this->session->userdata('identity')->id;
			$post['owner_letter_id'] = $list_owner->owner_letter_id;
			$post['letter_from'] = $list_owner->desc_sbu_code2;
			$post['unit_id'] = $this->session->userdata('detail_identity')->unit_id;
			$post['desc'] = $desc->letter_desc;


			$upload = true;
			if (!empty($_FILES['attachment']['name'])) {
				$path = './web/uploads/surat';

				if (!file_exists($path)) {
					mkdir($path, 0777, true);
				}

				$post['is_attach'] = 1;
				$config['upload_path'] = $path;
				$config['allowed_types'] = 'gif|jpg|png|jpeg|pdf';
				$config['max_size'] = '1000';
				$config['file_name'] = $file_name;

				$this->load->library('upload', $config);

				if ( ! $this->upload->do_upload('attachment')){
					$result = [
						'message' => $this->upload->display_errors(),
						'success' => false
					];
					$upload = false;
				} else {
					$post['attachment'] = $path .'/'. $this->upload->data("file_name");
					$upload = true;
				}
			}else{
				if($id){
					$cek_surat = $this->letter->get($id);

					if($cek_surat->attachment == null){
						$post['is_attach'] = 0;
					}else{
						$post['is_attach'] = 1;
					}				
				}else{
					$post['is_attach'] = 0;
				}
			}

			if ($upload) {
				if ($id) {
					$save = $this->letter->update($post, $id);
				} else {
					$save = $this->letter->insert($post);				
					$this->letterowner->getLetter($unit_sesi, true);
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


	public function actionListLetter()
	{

		$list_code = $this->kodesurat->getListCode($this->session->userdata('detail_identity')->unit_id,true);
		$list_type = $this->lettertype->getListType(true);

		$this->layout->title = 'List Letter';
		$this->layout->view_js = '_partial/sub_js';
		$this->layout->view_css = '_partial/sub_css';

		$this->layout->render('list_letter', [
			'list_code' => $list_code,
			'list_type' => $list_type
		]);
	}

	public function actionGetDataList()
	{
		if (!$this->input->is_ajax_request()) {
			show_error('Halaman tidak valid', 404);exit();
		}

		$model = new Letter;
		$unit_id = $this->session->userdata('detail_identity')->unit_id;
		$user_id = $this->session->userdata('identity')->id;

		if($unit_id == 2){
			$owner_letter_id = null;
		}else{
			$letcode = $this->kodesurat->get(['unit_id' => $unit_id]);
			$owner_letter_id = $letcode->owner_letter_id;
		}

		if($this->helpers->isSuperAdmin()){
			$model->owner_letter_id = null;
			$model->user_id = null;
			
			$param = null;
			$name_field = null;

		}elseif($this->helpers->isAdminDirektorat()){	
			$model->owner_letter_id = $owner_letter_id;
			
			$param = $model->owner_letter_id;
			$name_field = 'owner_letter_id';

		}elseif($this->helpers->isAdminCabang()){
			$model->owner_letter_id = $owner_letter_id;
			$model->unit_id = $unit_id;

			$param = $model->unit_id;
			$name_field = 'tbl_letter_reg.unit_id';

		}else{
			$model->owner_letter_id = $owner_letter_id;
			$model->user_id = $user_id;			
			
			$param = $model->user_id;
			$name_field = 'tbl_letter_reg.created_by';
		}


		$list = $model->get_datatables();
		$data = [];
		$no = $_POST['start'];

		foreach ($list as $field) {

			$no++;
			$row = [];
			$row[] = $no;
			$row[] = date('d F Y', strtotime($field->letter_date));
			$row[] = $field->letter_no;
			$row[] = $field->nama_depan;
			$row[] = $field->letter_from;
			$row[] = $field->letter_to;
			$row[] = $field->desc;

			if($field->is_attach == 1){
				$row[] = "
				<div class='text-center'>
				<button data-id='{$field->id}' class='btn btn-info btn-xs btn-view-list'><i class='fa fa-eye'></i></button>
				</div>";
			}else{
				$row[] = "
				<div class='text-center'>
				<button class='btn btn-danger btn-xs btn-view-list'><i class='fa fa-times'></i></button>
				</div>";
			}

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->letter->count_all($param,$name_field),
			"recordsFiltered" => $this->letter->count_filtered($param,$name_field),
			"data" => $data,
		);

         //output dalam format JSON
		echo json_encode($output);

	}

	public function actionDetailList($id)
	{	

		$model = $this->letter->get($id);

		return $this->output
			->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($model));
	    }

	}

	/* End of file LiveLetterController.php */
/* Location: ./application/controllers/LiveLetterController.php */