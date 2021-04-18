<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UnitController extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			'master/unit'
		]);
	}

	public function actionIndex()
	{
		$data_Groupunit = $this->unitgroup->getListGroupunit(true);
		$data_group = $this->unit->getListGroupUnit();
		$data_timezone = $this->unit->getTimezone();
		$data_unit_level = $this->unit->getUnitLevel();

		$this->layout->view_js = '_partial/sub_js';
		$this->layout->view_css = '_partial/sub_css';
		$this->layout->title = 'Master Unit';

		$this->layout->render('index', [
			'data_Groupunit' => $data_Groupunit,
			'data_group' => $data_group,
			'data_timezone' => $data_timezone,
			'data_unit_level' => $data_unit_level,
		]);
	}

	public function actionGetData()
	{
		if (!$this->input->is_ajax_request()) {
			show_error('Halaman tidak valid', 404);exit();
		}

		$list = $this->unit->get_datatables();
        $data = [];
        $no = $_POST['start'];

        foreach ($list as $field) {
            $no++;
            $row = [];
            $row[] = $no;
            $row[] = $field->kode_induk;
            $row[] = '['.$field->kode_unit.'] '.$field->nama_unit;
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
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->unit->count_all(),
            "recordsFiltered" => $this->unit->count_filtered(),
            "data" => $data,
        );

        //output dalam format JSON
        echo json_encode($output);
	}

	public function actionTambah()
	{
		$result = [
			'status' => 500,
			'message' => 'Gagal tambah Unit, silahkan cek kembalian isian anda'
		];

		$post = $this->input->post('Unit');

		if ($post) {
			$save_batch = $this->unit->insert($post);
			
			if ($save_batch) {
				$result = [
					'message' => 'Penambahan data Unit berhasil'
				];
			} else {
				$result = [
					'message' => 'Proses simpan penambahan data Unit gagal, silahkan coba beberapa saat lagi'
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
		$model = $this->unit->getDetailUnit($id);

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($model));
	}

	public function actionEdit($id)
	{
		$result = [
			'status' => 500,
			'message' => 'Gagal edit Unit, silahkan cek kembalian isian anda'
		];

		$post = $this->input->post('Unit');

		if ($post) {
			$save = $this->unit->update($post, $id);

			if ($save) {
				$result = [
					'message' => 'Perubahan data Unit berhasil'
				];
			} else {
				$result = [
					'message' => 'Proses simpan perubahan data Unit gagal, silahkan coba beberapa saat lagi'
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
		$model = $this->unit->get($id);

		if ($model) {
			$save = $this->unit->delete($id);

			if ($save) {
				$result = [
					'message' => 'Penghapusan data unit berhasil'
				];
			} else {
				$result = [
					'message' => 'Proses hapus data unit gagal, silahkan coba beberapa saat lagi'
				];
			}
		} else {
			$result = [
				'message' => 'Proses hapus data unit gagal, data tidak ditemukan'
			];
		}

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($result));
	}

}

/* End of file UnitController.php */
/* Location: ./application/modules/master/controllers/UnitController.php */