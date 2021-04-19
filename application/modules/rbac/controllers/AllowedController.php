<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AllowedController extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			'rbac/allowed',
			'rbac/routes',
		]);
	}

	/**
     * Lists all Route models.
     * @return mixed
     */
    public function actionIndex()
    {
        $all_routes = $this->allowed->getRoutesAllowed();
        $routes = $this->helpers->filterRoutes($all_routes, false);

        $this->layout->view_js = 'js_allowed';
        return $this->layout->render('index', [
        	'routes' => $routes
        ]);
    }

    /**
     * Creates a new AuthItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    // public function actionCreate()
    // {
    //     Yii::$app->getResponse()->format = 'json';
    //     $routes = Yii::$app->getRequest()->post('route', '');
    //     $routes = preg_split('/\s*,\s*/', trim($routes), -1, PREG_SPLIT_NO_EMPTY);
    //     $this->allowedService->add($routes);
    //     $model = $this->allowedService->routeInstance();
    //     return $this->helpers->filterRoutes($model->getRoutesAllowed(), false);
    // }

    /**
     * Assign routes
     * @return array
     */
    public function actionAssign()
    {
        $routes = $this->input->post('routes');
        $model = new Allowed;
        $model->add($routes);

        $current_routes = $this->helpers->filterRoutes($model->getRoutesAllowed(), false);

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
        $model = new Allowed;
        $model->remove($routes);

        $current_routes = $this->helpers->filterRoutes($model->getRoutesAllowed(), false);

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
        $model = new Allowed;
        $current_routes = $this->helpers->filterRoutes($model->getRoutesAllowed(), false);

        return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($current_routes));
    }

}

/* End of file AllowedController.php */
/* Location: ./application/modules/rbac/controllers/AllowedController.php */