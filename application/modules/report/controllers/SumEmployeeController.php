<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SumEmployeeController extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			'transaksi/Employee'
		]);
	}

	public function actionIndex()
	{
		$employee = $this->Employee->getData();

		$this->layout->view_js = '_partial/sub_js';
		$this->layout->view_css = '_partial/sub_css';
		$this->layout->title = 'Summary Employee';

		$this->layout->render('index', [
			'data_sum' => $employee
		]);
	}
}

/* End of file SumEmployeeController.php */
/* Location: ./application/modules/report/controllers/SumEmployeeController.php */