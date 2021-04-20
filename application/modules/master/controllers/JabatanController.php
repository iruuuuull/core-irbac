<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class JabatanController extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			'master/jabatan'
		]);
	}

	/**
	 * [actionIndex description]
	 * @return [type] [description]
	 */
	public function actionIndex()
	{
		$list_jabatan = $this->jabatan->get();

		$this->layout->view_js = '_partial/sub_js';
		$this->layout->view_css = '_partial/sub_css';
		$this->layout->title = 'Master Jabatan';

		$this->layout->render('index', [
			'list_jabatan' => $list_jabatan,
		]);
	}

	/**
	 * Action method untuk mendapatkan list data
	 * yg dibutuhkan untuk datatable
	 * @return [type] [description]
	 */
	public function actionGetData()
	{
		if (!$this->input->is_ajax_request()) {
			show_error('Halaman tidak valid', 404);exit();
		}

		/**
		 * list data di-generate berdasarkan masing2 model
		 * yg merepresentasikan table pada database
		 * method datatable ada pada class MY_Model
		 */
		$list = $this->jabatan->get_datatables();
        $data = [];

        // Angka terusan berdasarkan tombol halaman
        $no = $_POST['start'];

        foreach ($list as $field) {
            $no++;
            $row = [];
            $row[] = $no;
            $row[] = $field->kode_jabatan;
            $row[] = $field->nama_jabatan;
            $row[] = "
            	<div class='text-center'>
	            	<button data-id='{$field->id}' class='btn btn-info btn-xs btn-preview'><i class='fa fa-eye'></i></button> 
	            	<button data-id='{$field->id}' class='btn btn-warning btn-xs btn-edit'><i class='fa fa-pencil-alt'></i></button> 
	            	<button data-id='{$field->id}' class='btn btn-danger btn-xs btn-delete'><i class='fa fa-trash-alt'></i></button>
            	</div>
            ";
 
            $data[] = $row;
        }
 
        $output = array(
            "draw" => $_POST['draw'], // Jumlah client-side request data (per-hit)
            "recordsTotal" => $this->jabatan->count_all(), // Total semua data
            "recordsFiltered" => $this->jabatan->count_filtered(), // Total data setelah di-filter
            "data" => $data,
        );

        //output dalam format JSON
        echo json_encode($output);
	}

	/**
	 * Proses simpan tambah data
	 * @return [type] [description]
	 */
	public function actionTambah()
	{
		// Set pesan default ketika kondisi proses tidak terpenuhi
		$result = [
			'status' => 500,
			'message' => 'Gagal tambah jabatan, silahkan cek kembalian isian anda'
		];

		/**
		 * Ambil hasil post dari form yg field-nya diberi alias
		 * seperti variabel array
		 */
		$post = $this->input->post('Jabatan');

		if ($post) {
			$save_batch = $this->jabatan->insertBatch($post);
			
			if ($save_batch) {
				$result = [
					'message' => 'Penambahan data jabatan berhasil'
				];
			} else {
				$result = [
					'message' => 'Proses simpan penambahan data jabatan gagal, silahkan coba beberapa saat lagi'
				];
			}
		}

		// Pakai class output dari CI untuk set hasil kembali jadi json
		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($result));
	}

	/**
	 * Method untuk get detail data berdasarkan primary key
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function actionDetail($id)
	{
		// method get ini ada di class MY_Model
		$model = $this->jabatan->get($id);

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($model));
	}

	/**
	 * Proses edit data
	 * @param  integer $id [description]
	 * @return json        [description]
	 */
	public function actionEdit($id)
	{
		$result = [
			'status' => 500,
			'message' => 'Gagal edit jabatan, silahkan cek kembalian isian anda'
		];

		$post = $this->input->post('Jabatan');

		if ($post) {
			// proses update yg dibuat di class MY_Model
			$save = $this->jabatan->update($post, $id);

			if ($save) {
				$result = [
					'message' => 'Perubahan data jabatan berhasil'
				];
			} else {
				$result = [
					'message' => 'Proses simpan perubahan data jabatan gagal, silahkan coba beberapa saat lagi'
				];
			}
		}

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($result));
	}

	/**
	 * Proses hapus data
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function actionHapus($id)
	{
		# Spot data before deleting
		$model = $this->jabatan->get($id);

		if ($model) {
			$save = $this->jabatan->delete($id);

			if ($save) {
				$result = [
					'message' => 'Penghapusan data jabatan berhasil'
				];
			} else {
				$result = [
					'message' => 'Proses hapus data jabatan gagal, silahkan coba beberapa saat lagi'
				];
			}
		} else {
			$result = [
				'message' => 'Proses hapus data jabatan gagal, data tidak ditemukan'
			];
		}

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($result));
	}

}

/* End of file JabatanController.php */
/* Location: ./application/modules/master/controllers/JabatanController.php */