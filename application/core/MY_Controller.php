<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . '/libraries/php-jwt-master/JWT.php';
use \Firebase\JWT\JWT;

class MY_Controller extends CI_Controller
{
	public $CI;

    /**
     * List of route action that will be 
     * except to validate auth
     *
     * $action_exception = ['route1', 'route2', ..., 'route_n'];
     * 
     * @var array
     */
    protected $action_exception = [];

    protected $jwt;

    /**
     * Data user from jwt decode
     * @var [type]
     */
    protected $_user;

    public function __construct()
    {
        parent::__construct();

        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

        $this->load->library('Response');

        $action = camelToSlug(str_replace('action', '', $this->router->fetch_method()), '-');

        if (!in_array($action, $this->action_exception)) {
            $check = $this->validateJwt();

            if ($check['status'] === false) {
                header('HTTP/1.1 401 Unauthorized', true, 401);
                echo $this->response->apiString([], [], $check['message'], false);
                exit;
            }
        }
    }

    public function apiPost($name = "")
    {
    	if (empty($name)) {
    		return json_decode(file_get_contents('php://input'), true);
    	} else {
    		$post = json_decode(file_get_contents('php://input'), true);

    		return !empty($post[$name]) ? $post[$name] : null;
    	}
    }

    public function validateJwt($is_refresh = false)
    {
        $token = $this->helpers->getBearerToken();
        $key = $is_refresh === false ? $_ENV['JWT_ACCESS_TOKEN'] : $_ENV['JWT_REFRESH_TOKEN'];

        try {
            if (empty($token)) {
                throw new Exception('Token tidak ditemukan pada request');
            }

            $jwt = JWT::decode($token, $key, ['HS512']);

            if (is_object($jwt)) {
                $now = new DateTimeImmutable();
                $serverName = $_ENV['JWT_SERVER_NAME'];

                if (
                    $jwt->iss !== $serverName ||
                    $jwt->nbf > $now->getTimestamp() ||
                    $jwt->exp < $now->getTimestamp()
                ) {
                    throw new Exception('Token kedaluwarsa');

                } else {
                    $this->jwt = $jwt;

                    # Set session untuk blameable jika diperlukan
                    $data = def($jwt, 'data');

                    if ($data) {
                        $this->_user = def($data, 'user');
                        $this->session->set_userdata(['identity' => (object)['id' => def($this->_user, 'id')]]);
                    }
                    # End Set session untuk blameable jika diperlukan

                    if (empty($this->_user)) {
                        throw new Exception('Gagal mengurai data user pada token');
                    }

                    return [
                        'status' => true,
                        'message' => ''
                    ];
                }

            } else {
                throw new Exception('Token tidak valid');
            }

        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }

        return [
            'status' => false,
            'message' => ''
        ];
    }
}
