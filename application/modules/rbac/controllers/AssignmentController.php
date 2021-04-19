<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AssignmentController extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			'rbac/authassignment',
			'master/group'
		]);
	}

	public function actionIndex()
	{
		$this->layout->view_js = '_partial/index_js';
		$this->layout->view_css = '_partial/index_css';
		$this->layout->title = 'Assignment';

		$this->layout->render('index');
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
            $row[] = "
            	<div class='text-center'>
            		". $this->html->a(
            			'<i class=\'fa fa-eye\'></i>', 
            			"/rbac/assignment/view/{$field->id}", [
            				'class' => 'btn btn-info btn-xs'
            			]) ."
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

	public function actionView($id)
	{
		$model = $this->group->findOne($id);

		if (empty($model)) {
			show_error('Halaman tidak valid', 404);exit();
		}

		$permissions = $this->authassignment->getAssignment($id);

		$this->layout->view_js = '_partial/assignment_js';
		$this->layout->title = "{$model->label}'s Assignment";

		$this->layout->render('view', [
			'model' => $model,
			'permissions' => $permissions,
		]);
	}

	/**
     * Assign permissions
     * @return array
     */
    public function actionAssign($id)
    {
        $permissions = $this->input->post('permissions');
        $model = $this->group->findOne($id);
        $model->addAssignment($permissions);

        $current_permissions = $this->authassignment->getAssignment($id);

        return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($current_permissions));
    }

    /**
     * Remove permissions
     * @return array
     */
    public function actionRemove($id)
    {
        $permissions = $this->input->post('permissions');
        $model = $this->group->findOne($id);
        $model->removeAssignment($permissions);

        $current_permissions = $this->authassignment->getAssignment($id);

        return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($current_permissions));
    }

    /**
     * Refresh cache
     * @return type
     */
    public function actionRefresh($id)
    {
        $model = new Authassignment;
        $current_permissions = $this->authassignment->getAssignment($id);

        return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($current_permissions));
    }

}

/* End of file AssignmentController.php */
/* Location: ./application/modules/rbac/controllers/AssignmentController.php */