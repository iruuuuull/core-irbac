<?php

class AuthHelper
{
	const STATUS_LOGIN = 'loggedIn';
	const STATUS_LOCKED = 'locked';

	protected $CI;
	private $allowed;

	public function __construct() {
		$this->CI =& get_instance();
		$this->setAllowedRoutes();

		get_instance()->load->model([
			'rbac/allowed',
			'rbac/permission',
			'rbac/authassignment',
			'rbac/authitem',
		]);
	}

	/**
	 * [isGuest description]
	 * @return boolean [description]
	 */
	public function isGuest()
	{
		return empty($this->CI->session->userdata('status_login'));
	}

	/**
	 * [checkLogin description]
	 * @param  string $value [description]
	 * @return void
	 */
	public function checkLogin()
	{
		$status = $this->CI->session->userdata('status_login');

		if (strpos(uri_string(), 'api/') === false ) {
			if ($this->isGuest() && !in_array(uri_string(), $this->allowed)) {
	            $this->CI->session->set_flashdata('info', 'Silahkan login kedalam aplikasi');
	            return redirect('site/login');
		    } elseif ($status === self::STATUS_LOCKED) {
		    	if (!in_array(uri_string(), $this->allowed)) {
		            $this->CI->session->set_flashdata('info', 'Halaman terkunci, silahkan login');
		            return redirect('site/lock');
		        }
		    }
		    # Hapus komentar pada kondisi dibawah ini untuk mengaktifkan fitur RBAC
		    // elseif (in_array(uri_string(), $this->allowed) || $this->checkPermission()) {
		    // 	return true;
		    // }
		}

		return true;
	}

	public function checkPermission()
	{
		$session = $this->CI->session->userdata();
		$current_route = $this->CI->helpers->getCurrentSite();

		if (substr($current_route, 0, 1) == '/') {
            $current_route = substr($current_route, 1);
        }

		if (in_array($current_route, $this->allowed)) {
			return true;
		}


		if (!empty($session['status_login']) && $session['status_login'] === self::STATUS_LOGIN) {
			$groups = $session['group_id'];
			$routes = $this->CI->helpers->getRoutesByGroup($groups);

			if (in_array($current_route, $routes)) {
				return true;
			} else {
				return $this->redirectUnPermissioned($current_route);
			}
		}
	}

	private function setAllowedRoutes()
	{
		$allowed = $this->CI->allowed->getAll();
		$allowed_routes = [];

		if ($allowed) {
			$allowed_routes = array_column($allowed, 'allowed');
		}

		$this->allowed = $allowed_routes;
	}

	/**
     * Denies the access of the user.
     * The default implementation will redirect the user to the login page if he is a guest;
     * if the user is already logged, a 403 HTTP exception will be thrown.
     * @param  User $user the current user
     * @throws ForbiddenHttpException if the user is already logged in.
     */
    protected function denyAccess()
    {
        if ($this->isGuest()) {
			return redirect('site/login');
        } else {
        	return show_error('Anda tidak memiliki hak akses ke halaman ini', 401);
        	// return redirect('site/401-unauthorized');
        }
    }

    protected function redirectUnPermissioned($route)
    {
    	$model = $this->CI->authitem->findOne(['name' => $route]);
    	if ($model) {
    		return $this->denyAccess();
    	}

    	return show_error('Halaman tidak ditemukan dong ah pun.', 404);
        // return redirect('site/404-not-found');
    }
}
