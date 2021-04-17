<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class NotifikasiController extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			'notifikasi',
		]);
	}

	public function actionIndex()
	{
		echo 'Coming Soon';
	}

	public function actionRead($id)
	{
		$model = $this->notifikasi->findOne($id);

		if ($model) {
			$model->is_read = 1;

			return $model->save();
		}

		return false;
	}

}

/* End of file NotifikasiController.php */
/* Location: ./application/controllers/NotifikasiController.php */