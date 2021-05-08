<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class JenisDokumenController extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			'master/jenisdokumen'
		]);
	}

	public function actionIndex()
	{
		$this->layout->view_js = '_partial/sub_js';
		$this->layout->view_css = '_partial/sub_css';
		$this->layout->title = 'Master Jenis Dokumen';

		$this->layout->render('index');
	}

	public function actionGetData()
	{
		if (!$this->input->is_ajax_request()) {
			show_error('Halaman tidak valid', 404);exit();
		}

		$list = $this->jenisdokumen->get_datatables();
        $data = [];
        $no = $_POST['start'];

        foreach ($list as $field) {
            $no++;
            $row = [];
            $row[] = $no;
            $row[] = $field->kode_dokumen;
            $row[] = $field->label;
            $row[] = $field->desc ?? '-';
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
            "recordsTotal" => $this->jenisdokumen->count_all(),
            "recordsFiltered" => $this->jenisdokumen->count_filtered(),
            "data" => $data,
        );

        //output dalam format JSON
        echo json_encode($output);
	}

	public function actionTambah()
	{
		$result = [
			'status' => 500,
			'message' => 'Gagal tambah jenis dokumen, silahkan cek kembalian isian anda'
		];

		$post = $this->input->post('JenisDokumen');

		if ($post) {
			$save_batch = $this->jenisdokumen->insertBatch($post);
			
			if ($save_batch) {
				$result = [
					'status' => 200,
					'message' => 'Penambahan data jenis dokumen berhasil'
				];
			} else {
				$result = [
					'status' => 200,
					'message' => 'Proses simpan penambahan data jenis dokumen gagal, silahkan coba beberapa saat lagi'
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
		$model = $this->jenisdokumen->get($id);

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($model));
	}

	public function actionEdit($id)
	{
		$result = [
			'status' => 500,
			'message' => 'Gagal edit jenis dokumen, silahkan cek kembalian isian anda'
		];

		$post = $this->input->post('JenisDokumen');

		if ($post) {
			$save = $this->jenisdokumen->update($post, $id);

			if ($save) {
				$result = [
					'message' => 'Perubahan data jenis dokumen berhasil'
				];
			} else {
				$result = [
					'message' => 'Proses simpan perubahan data jenis dokumen gagal, silahkan coba beberapa saat lagi'
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
		$model = $this->jenisdokumen->get($id);

		if ($model) {
			$save = $this->jenisdokumen->delete($id);

			if ($save) {
				$result = [
					'message' => 'Penghapusan data jenis dokumen berhasil'
				];
			} else {
				$result = [
					'message' => 'Proses hapus data jenis dokumen gagal, silahkan coba beberapa saat lagi'
				];
			}
		} else {
			$result = [
				'message' => 'Proses hapus data jenis dokumen gagal, data tidak ditemukan'
			];
		}

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($result));
	}

}

/* End of file JenisDokumenController.php */
/* Location: ./application/modules/master/controllers/JenisDokumenController.php */