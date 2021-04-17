<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LetterTypeController extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			'master/lettertype'
		]);
	}

	public function actionIndex()
	{
		$list_letter = $this->lettertype->getAll();

		$this->layout->view_js = '_partial/sub_js';
		$this->layout->view_css = '_partial/sub_css';
		$this->layout->title = 'Letter Type';

		$this->layout->render('index', [
			
		]);
	}

	public function actionGetData()
	{
		if (!$this->input->is_ajax_request()) {
			show_error('Halaman tidak valid', 404);exit();
		}

		$list = $this->lettertype->get_datatables();
        $data = [];
        $no = $_POST['start'];

        foreach ($list as $field) {
            $no++;
            $row = [];
            $row[] = $no;
            $row[] = $field->letter_code;
            $row[] = $field->letter_desc;
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
            "recordsTotal" => $this->lettertype->count_all(),
            "recordsFiltered" => $this->lettertype->count_filtered(),
            "data" => $data,
        );

        //output dalam format JSON
        echo json_encode($output);
	}

	public function actionTambah()
	{
		$result = [
			'status' => 500,
			'message' => 'Gagal tambah Letter Type, silahkan cek kembalian isian anda'
		];

		$post = $this->input->post('Letter_type');

		if ($post) {
			$save_batch = $this->lettertype->insertBatch($post);
			
			if ($save_batch) {
				$result = [
					'message' => 'Penambahan data Letter Type berhasil'
				];
			} else {
				$result = [
					'message' => 'Proses simpan penambahan data Letter Type gagal, silahkan coba beberapa saat lagi'
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
		$model = $this->lettertype->get($id);

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($model));
	}

	public function actionEdit($id)
	{
		$result = [
			'status' => 500,
			'message' => 'Gagal edit Letter Type, silahkan cek kembalian isian anda'
		];

		$post = $this->input->post('Letter_type');

		if ($post) {
			$save = $this->lettertype->update($post, $id);

			if ($save) {
				$result = [
					'message' => 'Perubahan data Letter Type berhasil'
				];
			} else {
				$result = [
					'message' => 'Proses simpan perubahan data Letter Type gagal, silahkan coba beberapa saat lagi'
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
		$model = $this->lettertype->get($id);

		if ($model) {
			$save = $this->lettertype->delete($id);

			if ($save) {
				$result = [
					'message' => 'Penghapusan data Letter Type berhasil'
				];
			} else {
				$result = [
					'message' => 'Proses hapus data Letter Type gagal, silahkan coba beberapa saat lagi'
				];
			}
		} else {
			$result = [
				'message' => 'Proses hapus data Letter Type gagal, data tidak ditemukan'
			];
		}

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($result));
	}

}

/* End of file LetterTypeController.php */
/* Location: ./application/modules/master/controllers/LetterTypeController.php */