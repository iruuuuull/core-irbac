<?php

class AuthHelper
{
	const STATUS_LOGIN = 'loggedIn';
	const STATUS_LOCKED = 'locked';

	protected $CI;

	public function __construct() {
		$this->CI =& get_instance();
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
			if ($this->isGuest()) {
		        if (uri_string() != 'login' && uri_string() != 'site/login' && uri_string() != 'site/register' && uri_string() != 'site/google-auth'){

		            $this->CI->session->set_flashdata('info', 'Silahkan login kedalam aplikasi');
		            return redirect('site/login');
		        }
		    } elseif ($status === self::STATUS_LOCKED) {
		    	if (
		    		uri_string() != 'site/lock' 
		    		&& uri_string() != 'lock' 
		    		&& uri_string() != 'site/logout'
		    	) {

		            $this->CI->session->set_flashdata('info', 'Halaman terkunci, silahkan login');
		            return redirect('site/lock');
		        }
		    }
		}

	}
}
