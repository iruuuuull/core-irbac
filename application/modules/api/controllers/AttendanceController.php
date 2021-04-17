<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AttendanceController extends MY_Controller {

	private $allowed_type = ['in', 'out'];

	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			'forms/FormAttendance',
			'search/AttendanceApi',
			'transaksi/userdetail',
		]);
	}

	public function actionCheckIn()
	{
		return $this->check('in');
	}

	public function actionCheckOut()
	{
		return $this->check('out');
	}

	private function check($type)
	{
		if (!in_array($type, $this->allowed_type)) {
			return $this->response->api([], [], 'Parameter yang dikirim salah', false, 404);
		}

		$model = new FormAttendance;
		$model->type = $type;
		$model->user_id = $this->_user->id;
		$model->setAttributes($this->apiPost());

		if ($model->validate()) {
			if ($model->save()) {
				return $this->response->api($model->attended, $model->data_absen, "Proses check-{$type} berhasil.");
			}
		}

		return $this->response->api($model->getErrors(), [], "Proses check-{$type} gagal, mohon periksa isian anda.", false, 400);
	}

	public function actionList($id = null)
	{
		$model = new AttendanceApi;
		$model->nik = $this->apiPost('nik');

		$modelSearch = $model->search($this->apiPost('filter'));

		if ($model->nik) {
			$userdetail = $this->userdetail->findOne(['nik' => $model->nik]);
		} elseif ($id) {
			$userdetail = $this->userdetail->findOne(['user_id' => $id]);
		}

		return $this->response->api([
			'nama_lengkap' => $userdetail->mergeFullName(),
			'foto' => $userdetail->getImage()
		], $modelSearch);
	}

	public function actionDetailCheckIn($id)
	{
		return $this->detail('in', $id);
	}

	public function actionDetailCheckOut($id)
	{
		return $this->detail('out', $id);
	}

	private function detail($type, $id)
	{
		$model = $this->attendance->findOne($id);

		if (!$model) {
			return $this->response->api([], [], 'Data absen tidak ditemukan.', false, 404);
		}

		$result = [];

		if ($model) {
			$result = [
				'coordinate' => $model->{'coordinate_'.$type},
				'type' => 'Clock ' . ucfirst($type),
				'time' => date('H:i A', strtotime($model->{'check_'.$type})),
				'date' => date('d F Y', strtotime($model->created_at)),
				'location' => def($model, 'location_'.$type, '-'),
				'note' => def($model, 'catatan_'.$type, '-')
			];
		}

		return $this->response->api($result);
	}

}

/* End of file AttendanceController.php */
/* Location: ./application/modules/api/controllers/AttendanceController.php */