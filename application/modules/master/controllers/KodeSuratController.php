<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class KodeSuratController extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			'master/kodesurat',
			'master/unit',
			'master/letterowner',
		]);
	}

	public function actionIndex()
	{
		$list_unit = $this->unit->getListUnits([], true);
		$list_owner_letter = $this->letterowner->getListOwners([], true);

		$this->layout->view_js = '_partial/sub_js';
		$this->layout->view_css = '_partial/sub_css';
		$this->layout->title = 'Master Kode Surat';

		$this->layout->render('index', [
			'list_unit' => $list_unit,
			'list_owner_letter' => $list_owner_letter,
		]);
	}

	public function actionGetData()
	{
		if (!$this->input->is_ajax_request()) {
			show_error('Halaman tidak valid', 404);exit();
		}

		$list = $this->kodesurat->get_datatables();
        $data = [];
        $no = $_POST['start'];

        foreach ($list as $field) {
            $no++;
            $row = [];
            $row[] = $no;
            $row[] = $field->nama_unit;
            $row[] = $field->desc;
            $row[] = $field->sbu_code .'; '. $field->sbu_code2;
            $row[] = $field->desc_sbu_code2;
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
            "recordsTotal" => $this->kodesurat->count_all(),
            "recordsFiltered" => $this->kodesurat->count_filtered(),
            "data" => $data,
        );

        //output dalam format JSON
        echo json_encode($output);
	}

	public function actionTambah()
	{
		$result = [
			'status' => 500,
			'message' => 'Gagal tambah jabatan, silahkan cek kembalian isian anda'
		];

		$post = $this->input->post('KodeSurat');

		if ($post) {
			$save_batch = $this->kodesurat->insert($post);
			
			if ($save_batch) {
				$result = [
					'message' => 'Penambahan data kode surat berhasil'
				];
			} else {
				$result = [
					'message' => 'Proses simpan penambahan data kode surat gagal, silahkan coba beberapa saat lagi'
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
		$model = $this->kodesurat->get($id);

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($model));
	}

	public function actionEdit($id)
	{
		$result = [
			'status' => 500,
			'message' => 'Gagal edit jabatan, silahkan cek kembalian isian anda'
		];

		$post = $this->input->post('KodeSurat');

		if ($post) {
			$save = $this->kodesurat->update($post, $id);

			if ($save) {
				$result = [
					'message' => 'Perubahan data kode surat berhasil'
				];
			} else {
				$result = [
					'message' => 'Proses simpan perubahan data kode surat gagal, silahkan coba beberapa saat lagi'
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
		$model = $this->kodesurat->get($id);

		if ($model) {
			$save = $this->kodesurat->delete($id);

			if ($save) {
				$result = [
					'message' => 'Penghapusan data kode surat berhasil'
				];
			} else {
				$result = [
					'message' => 'Proses hapus data kode surat gagal, silahkan coba beberapa saat lagi'
				];
			}
		} else {
			$result = [
				'message' => 'Proses hapus data kode surat gagal, data tidak ditemukan'
			];
		}

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($result));
	}

}

/* End of file KodeSuratController.php */
/* Location: ./application/modules/master/controllers/KodeSuratController.php */