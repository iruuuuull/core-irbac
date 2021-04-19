<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GroupController extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			'master/group'
		]);
	}

	public function actionIndex()
	{
		$list_group = $this->group->get();

		$this->layout->view_js = '_partial/sub_js';
		$this->layout->view_css = '_partial/sub_css';
		$this->layout->title = 'Master Group';

		$this->layout->render('index', [
			'list_group' => $list_group,
		]);
	}

	public function actionGetData()
	{
		if (!$this->input->is_ajax_request()) {
			show_error('Halaman tidak valid', 404);exit();
		}

		$list = $this->group->get_datatables();
        $data = [];
        $no = $_POST['start'];

        foreach ($list as $field) {
            $no++;
            $row = [];
            $row[] = $no;
            $row[] = $field->label;
            $row[] = def($field, 'desc', '-');
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
            "recordsTotal" => $this->group->count_all(),
            "recordsFiltered" => $this->group->count_filtered(),
            "data" => $data,
        );

        //output dalam format JSON
        echo json_encode($output);
	}

	public function actionDetail($id)
	{
		$model = $this->group->get($id);

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($model));
	}

	public function actionSimpan($id = null)
	{
		$result = [
			'status' => 500,
			'message' => 'Gagal edit group, silahkan cek kembalian isian anda'
		];

		$model = null;
		if ($id) {
			$model = $this->group->findOne($id);
		}

		if ($post = $this->input->post('Group')) {
			$post['group_code'] = str_replace(' ', '-', strtolower($post['label']));

			if ($model) {
				$save = $this->group->update($post, $id);
			} else {
				$save = $this->group->insert($post);
			}

			if ($save) {
				$result = [
					'message' => 'Perubahan data group berhasil'
				];
			} else {
				$errors = 'Proses simpan perubahan data group gagal, silahkan coba beberapa saat lagi';
				if ($this->group->getErrors()) {
					$errors = $this->helpers->valueErrors($this->group->getErrors(), true);
				}

				$result = [
					'message' => $errors
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
		$model = $this->group->get($id);

		if ($model) {
			$save = $this->group->delete($id);

			if ($save) {
				$result = [
					'message' => 'Penghapusan data group berhasil'
				];
			} else {
				$result = [
					'message' => 'Proses hapus data group gagal, silahkan coba beberapa saat lagi'
				];
			}
		} else {
			$result = [
				'message' => 'Proses hapus data group gagal, data tidak ditemukan'
			];
		}

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($result));
	}

}

/* End of file GroupController.php */
/* Location: ./application/modules/master/controllers/GroupController.php */