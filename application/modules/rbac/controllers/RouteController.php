<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RouteController extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			'rbac/allowed',
			'rbac/authitem',
		]);
	}

	public function actionIndex()
	{
		$all_routes = $this->allowed->getUnAllowedRoutes();
        $routes = $this->helpers->filterRoutes($all_routes, false);

        $this->layout->view_js = 'js_route';
        return $this->layout->render('index', [
        	'routes' => $routes
        ]);
	}

	/**
     * Assign routes
     * @return array
     */
    public function actionAssign()
    {
        $routes = $this->input->post('routes');
        $model = new Authitem;
        $model->add($routes, Authitem::TYPE_ROUTE);

        $current_routes = $this->helpers->filterRoutes($this->allowed->getUnAllowedRoutes(), false);

        return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($current_routes));
    }

    /**
     * Remove routes
     * @return array
     */
    public function actionRemove()
    {
        $routes = $this->input->post('routes');
        $model = new Authitem;
        $model->remove($routes, Authitem::TYPE_ROUTE);

        $current_routes = $this->helpers->filterRoutes($this->allowed->getUnAllowedRoutes(), false);

        return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($current_routes));
    }

    /**
     * Refresh cache
     * @return type
     */
    public function actionRefresh()
    {
        $model = new Authitem;
        $current_routes = $this->helpers->filterRoutes($this->allowed->getUnAllowedRoutes(), false);

        return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($current_routes));
    }

}

/* End of file RouteController.php */
/* Location: ./application/modules/rbac/controllers/RouteController.php */