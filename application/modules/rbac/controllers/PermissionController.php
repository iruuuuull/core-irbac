<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PermissionController extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			'rbac/permission'
		]);
	}

	public function actionIndex()
	{
		$list_permission = $this->permission->get();

		$this->layout->view_js = '_partial/sub_js';
		$this->layout->view_css = '_partial/sub_css';
		$this->layout->title = 'Permission';

		$this->layout->render('index', [
			'list_permission' => $list_permission,
		]);
	}

	public function actionGetData()
	{
		if (!$this->input->is_ajax_request()) {
			show_error('Halaman tidak valid', 404);exit();
		}

		$list = $this->permission->get_datatables();
        $data = [];
        $no = $_POST['start'];

        foreach ($list as $field) {
            $no++;
            $row = [];
            $row[] = $no;
            $row[] = $field->name;
            $row[] = def($field, 'description', '-');
            $row[] = "
            	<div class='text-center'>
	            	". anchor("/rbac/permission/view/{$field->id}", "<i class='fa fa-eye'></i>", ['class' => 'btn btn-info btn-xs']) ."
	            	<button data-id='{$field->id}' class='btn btn-warning btn-xs btn-edit'><i class='fa fa-pencil-alt'></i></button> 
	            	<button data-id='{$field->id}' class='btn btn-danger btn-xs btn-delete'><i class='fa fa-trash-alt'></i></button>
            	</div>
            ";
 
            $data[] = $row;
        }
 
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->permission->count_all(),
            "recordsFiltered" => $this->permission->count_filtered(),
            "data" => $data,
        );

        //output dalam format JSON
        echo json_encode($output);
	}

	public function actionDetail($id)
	{
		$model = $this->permission->get($id);

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($model));
	}

	public function actionSimpan($id = null)
	{
		$result = [
			'status' => 500,
			'message' => 'Gagal edit permission, silahkan cek kembalian isian anda'
		];

		$model = null;
		if ($id) {
			$model = $this->permission->findOne($id);
		}

		if ($post = $this->input->post('Permission')) {
			$post['type'] = Permission::TYPE_PERMISSION;

			if ($model) {
				$save = $this->permission->update($post, $id);
			} else {
				$save = $this->permission->insert($post);
			}

			if ($save) {
				$result = [
					'message' => 'Perubahan data permission berhasil'
				];
			} else {
				$errors = 'Proses simpan perubahan data permission gagal, silahkan coba beberapa saat lagi';
				if ($this->permission->getErrors()) {
					$errors = $this->helpers->valueErrors($this->permission->getErrors(), true);
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
		$model = $this->permission->get($id);

		if ($model) {
			$save = $this->permission->delete($id);

			if ($save) {
				$result = [
					'message' => 'Penghapusan data permission berhasil'
				];
			} else {
				$result = [
					'message' => 'Proses hapus data permission gagal, silahkan coba beberapa saat lagi'
				];
			}
		} else {
			$result = [
				'message' => 'Proses hapus data permission gagal, data tidak ditemukan'
			];
		}

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($result));
	}

	public function actionView($id)
	{
		$model = $this->permission->findOne($id);

		if (empty($model)) {
			show_error('Halaman tidak valid', 404);exit();
		}

		$all_routes = $this->permission->getUnPermissions($id);
        $routes = $this->helpers->filterRoutes($all_routes, false);

        $this->layout->view_js = '_partial/js_permission';
        return $this->layout->render('view', [
        	'routes' => $routes,
        	'model' => $model
        ]);
	}

	/**
     * Assign routes
     * @return array
     */
    public function actionAssign($id)
    {
        $routes = $this->input->post('routes');
        $model = $this->permission->findOne($id);
        $model->add($routes, Authitem::TYPE_PERMISSION);

        $current_routes = $this->helpers->filterRoutes($this->permission->getUnPermissions($id), false);

        return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($current_routes));
    }

    /**
     * Remove routes
     * @return array
     */
    public function actionRemove($id)
    {
        $routes = $this->input->post('routes');
        $model = $this->permission->findOne($id);
        $model->remove($routes, Authitem::TYPE_PERMISSION);

        $current_routes = $this->helpers->filterRoutes($this->permission->getUnPermissions($id), false);

        return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($current_routes));
    }

    /**
     * Refresh cache
     * @return type
     */
    public function actionRefresh($id)
    {
        $model = new Authitem;
        $current_routes = $this->helpers->filterRoutes($this->permission->getUnPermissions($id), false);

        return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($current_routes));
    }

}

/* End of file PermissionController.php */
/* Location: ./application/modules/master/controllers/PermissionController.php */