<?php

/**
 * Library untuk nyimpen method atau 
 * helper yg bisa terhubung dengan model
 *
 * @author Ilham D. Sofyan
 */
class Helpers
{
	public $CI;

    public $months = [
        1 => "Januari", 
        "Februari", 
        "Maret", 
        "April", 
        "Mei", 
        "Juni", 
        "Juli", 
        "Agustus", 
        "September", 
        "Oktober", 
        "November", 
        "Desember"
    ];

	public function __construct()
	{
		$this->CI =& get_instance();

        get_instance()->load->model([
            'master/user',
            'master/group',
            'rbac/allowed',
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

	public function getRoute()
	{
		$routes = array_reverse($this->CI->router->routes); // All routes as specified in config/routes.php, reserved because Codeigniter matched route from last element in array to first.
		foreach ($routes as $key => $val) {
			$route = $key; // Current route being checked.

			// Convert wildcards to RegEx
			$key = str_replace(array(':any', ':num'), array('[^/]+', '[0-9]+'), $key);

			// Does the RegEx match?
			if (preg_match('#^'.$key.'$#', $this->CI->uri->uri_string(), $matches)) break;
		}

		if ( ! $route) $route = $routes['default_route']; // If the route is blank, it can only be mathcing the default route.

		return $route; // We found our route
	}

	public function getQueryParams()
	{
		return $_GET;
	}

	/**
     * Removes an item from an array and returns the value. If the key does not exist in the array, the default value
     * will be returned instead.
     *
     * Usage examples,
     *
     * ```php
     * // $array = ['type' => 'A', 'options' => [1, 2]];
     * // working with array
     * $type = \yii\helpers\ArrayHelper::remove($array, 'type');
     * // $array content
     * // $array = ['options' => [1, 2]];
     * ```
     *
     * @param array $array the array to extract value from
     * @param string $key key name of the array element
     * @param mixed $default the default value to be returned if the specified key does not exist
     * @return mixed|null the value of the element if found, default value otherwise
     */
    public function arrayRemove(&$array, $key, $default = null)
    {
        if (is_array($array) && (isset($array[$key]) || array_key_exists($key, $array))) {
            $value = $array[$key];
            unset($array[$key]);

            return $value;
        }

        return $default;
    }

    public static function filterRoutes($routes = [], $action = true)
    {
        $res = $routes;

        if ($action)
            $res = self::filterAction($res);
        else
            $res = self::filterActionAllowed($res);

        $res = self::filterAllow($res);
        return $res;
    }

    public static function filterAction($routes)
    {
        $res = [];
        if (!empty($routes)) {
            foreach ($routes as $key => $value) {
                if (!empty($value)) {
                    foreach ($value as $k => $v) {
                        if (substr(trim($v), -1) !== '*') {
                            $res[$key][] = $v;
                        }
                    }
                } else {
                    $res[$key] = [];
                }
            }
        }
        return $res;
    }

    public static function filterActionAllowed($routes)
    {
        $res = [];
        if (!empty($routes)) {
            foreach ($routes as $key => $value) {
                if (!empty($value) && $key == 'avaliable') {
                    foreach ($value as $k => $v) {
                        if (substr(trim($v), -1) !== '*') {
                            $res[$key][] = $v;
                        }
                    }
                } else {
                    $res[$key] = $value;
                }
            }
        }
        return $res;
    }

    public static function filterAllow($routes)
    {
        $res = $tmp = [];
        if (!empty($routes)) {
            $allowed = self::getAllowed();
            foreach ($routes as $key => $value) {
                if (!empty($value) && $key == 'avaliable') {
                    $res[$key] = [];
                    foreach ($value as $k => $v) {
                        if (!empty($allowed)) {
                            foreach ($allowed as $k1 => $v1) {
                                if (substr($v1, -1) === '*') {
                                    $route = rtrim($v1, "*");
                                    if (strlen($route) === 0 || strpos($v, $route) === 1) {
                                        $tmp[] = $v;
                                    }
                                } else {
                                    if (ltrim($v1, "/") === ltrim($v, "/")) {
                                        $tmp[] = $v;
                                    }
                                }
                            }
                        }
                    }
                    $res[$key] = array_diff($value, $tmp);
                } else {
                    $res[$key] = $value;
                }
            }
        }
        return $res;
    }

    public static function getAllowed()
    {
        $allows = (new self)->CI->allowed->getAll();

        $allowed = [];
        foreach ($allows as $key => $allow) {
            $allowed[] = $allow->allowed;
        }

        return $allowed;
    }

    public function isSuperAdmin()
    {
        $model = $this->CI->user->findOne($this->CI->session->userdata('identity')->id);

        return $model->isHasGroup(Group::ADMIN);
    }

    public function isAdminDirektorat()
    {
        $model = $this->CI->user->findOne($this->CI->session->userdata('identity')->id);

        return $model->isHasGroup(Group::ADMIN_DIR);
    }

    public function isAdminCabang()
    {
        $model = $this->CI->user->findOne($this->CI->session->userdata('identity')->id);

        return $model->isHasGroup(Group::ADMIN_CABANG);
    }

    public function isUser()
    {
        $model = $this->CI->user->findOne($this->CI->session->userdata('identity')->id);

        return $model->isHasGroup(Group::USER);
    }

    public function isManajemenHo()
    {
        $model = $this->CI->user->findOne($this->CI->session->userdata('identity')->id);

        return $model->isHasGroup(Group::MANAJEMEN_HO);
    }

    public function isManajemenDirektorat()
    {
        $model = $this->CI->user->findOne($this->CI->session->userdata('identity')->id);

        return $model->isHasGroup(Group::MANAJEMEN_DIR);
    }

    public function isManajemenCabang()
    {
        $model = $this->CI->user->findOne($this->CI->session->userdata('identity')->id);

        return $model->isHasGroup(Group::MANAJEMEN_CABANG);
    }

    /**
     * [diffHours description]
     * @param  string $start [00:00]
     * @param  string $end   [00:00]
     * @return int
     */
    public function diffHours($start, $end) {
        $starttimestamp = strtotime(date('H:i', strtotime($start)));
        $endtimestamp = strtotime(date('H:i', strtotime($end)));
        $difference = abs($endtimestamp - $starttimestamp) / 3600;

        return $difference;
    }

    public function getMonthNum($name)
    {
        $months = array_reverse($this->months);
        $name = strtolower($name);

        return $months[$name] ? $months[$name] : null;
    }

    public function getMonthName($num)
    {
        $months = $this->months;

        return $months[$num] ? $months[$num] : null;
    }

    /**
     * [formatDateIndonesia description]
     * @param  [type] $date [Y-m-d]
     * @return [type]       [description]
     */
    public function formatDateIndonesia($date)
    {
        $result = '';

        if ($date) {
            $BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
            $tahun = substr($date, 0, 4);
            $bulan = substr($date, 5, 2);
            $tgl   = substr($date, 8, 2);

            if (array_key_exists((int)$bulan-1, $BulanIndo)) {
                $result = $tgl . " " . $BulanIndo[(int)$bulan-1] . " ". $tahun;
            } else {
                $result = "-";
            }
        }

        return $result;
    }

    public function configGoogle()
    {
        // google API Configuration
        $redirectUrl = base_url('/site/google-auth');
        
        //Call google API
        $client = new Google_Client();
        $client->setApplicationName("LP3I-ERS");
        $client->setClientId(getEnv('GOOGLE_ID'));
        $client->setClientSecret(getEnv('GOOGLE_SECRET'));
        $client->setRedirectUri($redirectUrl);
        $client->addScope("https://www.googleapis.com/auth/userinfo.email");

        return $client;
    }

    public function getUrlGoogle()
    {
        $client = $this->configGoogle();

        return $client->createAuthUrl();
    }

    public function getTimezonedDate($time, $format = 'Y-m-d H:i:s', $timezone = 'Asia/Jakarta') {
        $dt = new DateTime();
        $dt->setTimezone(new DateTimeZone($timezone));
        $dt->setTimestamp($time);
        
        return $dt->format($format);
    }


    public function formatGetRomawi($bulan){

        $result = '';

        if($bulan){
            $Romawi = array("I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII");
             if (array_key_exists((int)$bulan-1, $Romawi)) {
                $result = $Romawi[(int)$bulan-1];
            } else {
                $result = "-";
            }
        }

          return $result;
    }

    /** 
     * Get header Authorization
     * */
    public function getAuthorizationHeader(){
            $headers = null;
            if (isset($_SERVER['Authorization'])) {
                $headers = trim($_SERVER["Authorization"]);
            }
            else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
                $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
            } elseif (function_exists('apache_request_headers')) {
                $requestHeaders = apache_request_headers();
                // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
                $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
                //print_r($requestHeaders);
                if (isset($requestHeaders['Authorization'])) {
                    $headers = trim($requestHeaders['Authorization']);
                }
            }
            return $headers;
        }
    /**
     * get access token from header
     * */
    public function getBearerToken() {
        $headers = $this->getAuthorizationHeader();
        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }

}
