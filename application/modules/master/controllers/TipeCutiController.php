<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TipeCutiController extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			'master/tipecuti',
		]);
	}

	public function actionIndex()
	{
		$list_verifikator = $this->tipecuti->getListVerifikator();

		$this->layout->view_js = '_partial/sub_js';
		$this->layout->view_css = '_partial/sub_css';
		$this->layout->title = 'Master Tipe Cuti';

		$this->layout->render('index', [
			'list_verifikator' => $list_verifikator,
		]);
	}

	public function actionGetData()
	{
		if (!$this->input->is_ajax_request()) {
			show_error('Halaman tidak valid', 404);exit();
		}

		$list = $this->tipecuti->get_datatables();
        $data = [];
        $no = $_POST['start'];

        foreach ($list as $field) {
            $no++;
            $row = [];
            $row[] = $no;
            $row[] = $field->name;
            $row[] = $field->desc;
            $row[] = strtoupper(str_replace('_', ' ', implode("; ", json_decode($field->rule, true))));
            $row[] = $field->is_limited == 1 ? 'Ya' : 'Tidak';
            $row[] = "
            	<div class='text-center'>
	            	<button data-id='{$field->id}' class='btn btn-info btn-xs btn-preview'><i class='fa fa-eye'></i></button> 
	            	<button data-id='{$field->id}' class='btn btn-warning btn-xs btn-edit'><i class='fa fa-pencil'></i></button> 
	            	<button data-id='{$field->id}' class='btn btn-danger btn-xs btn-delete'><i class='fa fa-trash-o'></i></button>
            	</div>
            ";
 
            $data[] = $row;
        }
 
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->tipecuti->count_all(),
            "recordsFiltered" => $this->tipecuti->count_filtered(),
            "data" => $data,
        );

        //output dalam format JSON
        echo json_encode($output);
	}

	public function actionTambah()
	{
		$result = [
			'status' => 500,
			'message' => 'Gagal tambah tipe cuti, silahkan cek kembalian isian anda'
		];

		$post = $this->input->post('TipeCuti');

		if ($post) {
			$post['rule'] = json_encode($post['rule']);
			$save_batch = $this->tipecuti->insert($post);
			
			if ($save_batch) {
				$result = [
					'message' => 'Penambahan data tipe cuti berhasil'
				];
			} else {
				$result = [
					'message' => 'Proses simpan penambahan data tipe cuti gagal, silahkan coba beberapa saat lagi'
				];
			}
		}

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($result));
	}

	public function actionDetail($id)
	{
		$model = $this->tipecuti->get($id);

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($model));
	}

	public function actionEdit($id)
	{
		$result = [
			'status' => 500,
			'message' => 'Gagal edit tipe cuti, silahkan cek kembalian isian anda'
		];

		$post = $this->input->post('TipeCuti');

		if ($post) {
			$post['rule'] = json_encode($post['rule']);
			$save = $this->tipecuti->update($post, $id);

			if ($save) {
				$result = [
					'message' => 'Perubahan data tipe cuti berhasil'
				];
			} else {
				$result = [
					'message' => 'Proses simpan perubahan data tipe cuti gagal, silahkan coba beberapa saat lagi'
				];
			}
		}

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($result));
	}

	public function actionHapus($id)
	{
		# Spot data before deleting
		$model = $this->tipecuti->get($id);

		if ($model) {
			$save = $this->tipecuti->delete($id);

			if ($save) {
				$result = [
					'message' => 'Penghapusan data tipe cuti berhasil'
				];
			} else {
				$result = [
					'message' => 'Proses hapus data tipe cuti gagal, silahkan coba beberapa saat lagi'
				];
			}
		} else {
			$result = [
				'message' => 'Proses hapus data tipe cuti gagal, data tidak ditemukan'
			];
		}

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($result));
	}

}

/* End of file TipeCutiController.php */
/* Location: ./application/modules/master/controllers/TipeCutiController.php */