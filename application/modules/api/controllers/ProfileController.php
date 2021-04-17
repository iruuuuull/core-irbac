<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ProfileController extends MY_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model([
			'master/user',
			'FormChangePassword',
		]);
	}

	public function actionChangePassword()
	{
		$model = new FormChangePassword;
		$model->_user = $this->user->findOne($this->_user->id);

		if (!$model->_user) {
			return $this->response->api([], [], ['message' => 'Data user tidak ditemukan.'], false, 404);
		}

		$model->old_password = $this->apiPost('old_password');
		$model->new_password = $this->apiPost('new_password');
		$model->conf_password = $this->apiPost('conf_password');

		if ($model->validate()) {
			if ($model->save()) {
				return $this->response->api([], [], ['message' => 'Proses simpan berhasil.']);
			} else {
				return $this->response->api([], [], ['message' => 'Proses simpan gagal, silahkan coba lagi.'], false);
			}

		} else {
			return $this->response->api($model->getErrors(), [], [
				'message' => 'Proses simpan gagal, mohon periksa isian anda.'
			], false, 400);
		}
	}

	public function actionChangePicture()
	{
		$model = $this->user->findOne($this->_user->id);

		if (!$model) {
			return $this->response->api([], [], ['message' => 'Data user tidak ditemukan.'], false, 404);
		}

		$model_detail = $model->userDetail;

		if (!$model_detail) {
			return $this->response->api([], [], ['message' => 'Silahkan lakukan update data pribadi terlebih dahulu.'], false, 403);
		}

		if (empty($_FILES['image'])) {
			return $this->response->api([], [], ['message' => 'File foto tidak boleh kosong.'], false, 404);
		}

		$path = './web/uploads/profile';

		if (!file_exists($path)) {
		    mkdir($path, 0777, true);
		}

		$config['upload_path']		= $path;
	    $config['allowed_types']	= 'png|jpeg|jpg';
	    $config['file_name']		= 'Foto_Profil_' . $model->id;
	    $config['overwrite']		= true;
	    $config['max_size']			= 500; // 500KB

	    $this->load->library('upload', $config);

	    if ($this->upload->do_upload('image')) {

	    	$model_detail->profile_pic = $path .'/'. $this->upload->data("file_name");

	    	if ($model_detail->save()) {
				return $this->response->api([
					'images' => base_url(str_replace('./', '', $model_detail->profile_pic), true),
				], [], ['message' => 'Ubah foto profil berhasil.']);

	    	} else {
	    		return $this->response->api([
					'images' => base_url(str_replace('./', '', $model_detail->profile_pic ? $model_detail->profile_pic : "/web/assets/pages/img/no_avatar.jpg"), true),
				], [], ['message' => 'Ubah foto profil gagal.'], false);
	    	}

	    } else {
	    	return $this->response->api([
				'images' => base_url(str_replace('./', '', $model_detail->profile_pic ? $model_detail->profile_pic : "/web/assets/pages/img/no_avatar.jpg"), true),
			], [], ['message' => $this->upload->display_errors()], false, 500);
	    }

	    return $this->response->api([], [], ['message' => 'Proses mengalami kendala.'], false, 500);
	}

}

/* End of file ProfileController.php */
/* Location: ./application/modules/api/controllers/ProfileController.php */