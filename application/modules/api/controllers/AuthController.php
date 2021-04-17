<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/libraries/php-jwt-master/JWT.php';

use \Firebase\JWT\JWT;

class AuthController extends MY_Controller {

	protected $action_exception = [
		'login',
		'refresh',
		'unauthorized',
	];

	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			'master/user',
			'transaksi/userdetail',
		]);
	}

	public function actionLogin()
	{
		$user = $this->user->findOne(['username' => $this->apiPost('username')]);

		if ($user) {
			$check_password = password_verify($this->apiPost('password'), $user->password);

			if ($check_password) {
				$issuedAt   = new DateTimeImmutable();
				$expired = $issuedAt->modify('+60 minutes')->getTimestamp();

				$payload = array(
		            "iss" => $_ENV['JWT_SERVER_NAME'],
		            "aud" => $_ENV['JWT_SERVER_NAME'],
		            "iat" => $issuedAt->getTimestamp(),
		            "nbf" => $issuedAt->getTimestamp(),
		            "exp" => $expired, // 60 Menit
		            "data" => [
		                'user' => [
		                    'id' => $user->id,
		                    'username' => $user->username,
		                    'email' => $user->email,
		                    'status' => $user->status,
		                    'group_ids' => array_column($this->user->getUserGroups($user->id), 'group_id')
		                ]
		            ]
		        );

				$jwt = JWT::encode($payload, $_ENV['JWT_ACCESS_TOKEN'], 'HS512');

				$payload['exp'] = $issuedAt->modify('+120 minutes')->getTimestamp(); // 2 jam
				$jwt_refresh = JWT::encode($payload, $_ENV['JWT_REFRESH_TOKEN'], 'HS512');

				return $this->response->api([
					'accessToken' => $jwt,
					'refreshToken' => $jwt_refresh,
					'expiry' => date(DATE_ISO8601, $expired),
				], $payload['data'], 'Proses login berhasil', true);
			}

		}

		return $this->response->api([], [], 'Proses login gagal, username atau password salah', false);
	}

	public function actionRefresh()
	{
		$check = $this->validateJwt(true);

		if ($check['status'] === false) {
			return $this->response->api([], [], $check['message'], false);
		}

        $now = new DateTimeImmutable;
        $expired = $now->modify('+60 minutes')->getTimestamp();

        $this->jwt->iat = $now->getTimestamp();
        $this->jwt->nbf = $now->getTimestamp();
        $this->jwt->exp = $now->modify('+120 minutes')->getTimestamp();

        $jwt_refresh = JWT::encode($this->jwt, $_ENV['JWT_REFRESH_TOKEN'], 'HS512');

        $this->jwt->exp = $expired;
        $jwt = JWT::encode($this->jwt, $_ENV['JWT_ACCESS_TOKEN'], 'HS512');

		return $this->response->api([
			'accessToken' => $jwt,
			'refreshToken' => $jwt_refresh,
			'expiry' => date(DATE_ISO8601, $expired),
		], [], 'Proses refresh token berhasil');
	}

	public function actionDetailUser($id = null)
	{
		if ($id === null) {
			$id = $this->_user->id;
		}

		$user = $this->user->findOne($id);

		$model_user_detail = (new Userdetail)->queryOne($user->userDetail());
		$user_detail = $model_user_detail->getAllRelations();

		# Unsetting unnecessary data
		$user_detail = multipleUnset($user_detail, [
			'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by', 'id', 'user_id'
		]);

		# Set additional data for user detail
		$user_detail['profile_pic'] = $user->userDetail->getImage();
		$user_detail['nama_lengkap'] = $user->userDetail->mergeFullName();

		return $this->response->api([], $user_detail);
	}

}

/* End of file LoginController.php */
/* Location: ./application/modules/api/controllers/LoginController.php */