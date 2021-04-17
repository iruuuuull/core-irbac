<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DesignationController extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			'master/designation',
			'master/gradingtype',
		]);
	}

	public function actionIndex()
	{
		$this->layout->view_js = '_partial/js';
		$this->layout->view_css = '_partial/css';
		$this->layout->title = 'Master Designation';

		$list_grading_type = $this->gradingtype->getListGrade(true);

		$this->layout->render('index', [
			'list_grading_type' => $list_grading_type,
		]);
	}

	public function actionGetData()
	{
		if (!$this->input->is_ajax_request()) {
			show_error('Halaman tidak valid', 404);exit();
		}

		$list = $this->designation->get_datatables();
        $data = [];
        $no = $_POST['start'];

        foreach ($list as $field) {
            $no++;
            $row = [];
            $row[] = $no;
            $row[] = def($field->gradingType, 'teamwork_name');
            $row[] = $field->designation;
            $row[] = $field->combine_label == 1 ? '<span class="label label-success">Ya</span>' : '<span class="label label-warning">Tidak</span>';
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
            "recordsTotal" => $this->designation->count_all(),
            "recordsFiltered" => $this->designation->count_filtered(),
            "data" => $data,
        );

        //output dalam format JSON
        echo json_encode($output);
	}

	public function actionTambah()
	{
		$result = [
			'status' => false,
			'message' => 'Gagal tambah designation, silahkan cek kembalian isian anda'
		];

		$post = $this->input->post('Designation');

		if ($post) {
			$save_batch = $this->designation->insert($post);
			
			if ($save_batch) {
				$result = [
					'status' => true,
					'message' => 'Penambahan data designation berhasil'
				];
			} else {
				$result = [
					'status' => false,
					'message' => 'Proses simpan penambahan data designation gagal, silahkan coba beberapa saat lagi'
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
		$model = $this->designation->get($id);

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($model));
	}

	public function actionEdit($id)
	{
		$result = [
			'status' => false,
			'message' => 'Gagal edit designation, silahkan cek kembalian isian anda'
		];

		$post = $this->input->post('Designation');

		if ($post) {
			$save = $this->designation->update($post, $id);

			if ($save) {
				$result = [
					'status' => true,
					'message' => 'Perubahan data designation berhasil'
				];
			} else {
				$result = [
					'status' => false,
					'message' => 'Proses simpan perubahan data designation gagal, silahkan coba beberapa saat lagi'
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
		$model = $this->designation->get($id);

		if ($model) {
			$save = $this->designation->delete($id);

			if ($save) {
				$result = [
					'message' => 'Penghapusan data designation berhasil'
				];
			} else {
				$result = [
					'message' => 'Proses hapus data designation gagal, silahkan coba beberapa saat lagi'
				];
			}
		} else {
			$result = [
				'message' => 'Proses hapus data designation gagal, data tidak ditemukan'
			];
		}

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($result));
	}

}

/* End of file DesignationController.php */
/* Location: ./application/modules/master/controllers/DesignationController.php */