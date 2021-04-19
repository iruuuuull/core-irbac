<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Allowed extends MY_Model {
	public $tableName = 'tbl_allowed';
	public $soft_delete = false;

	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			'rbac/routes',
			'rbac/permission',
		]);
	}

	public function getRoutesAllowed()
    {
		$this->load->library('controllerlist');

        $routes = $this->controllerlist->getAppRoutes();
        $allows = Helpers::getAllowed();

        # Unsetting routes asigned
        foreach (array_keys($this->routes->getRoutes()) as $name) {
            if (($key = array_search($name, $routes)) !== false) {
            	unset($routes[$key]);
			}
        }

        # Deleting routes assigned to allowed
        foreach ($allows as $allow) {
            if (($key = array_search($allow, $routes)) !== false) {
            	unset($routes[$key]);
			}
        }

        return[
            'available' => $routes,
            'assigned' => $allows
        ];
    }

    public function getUnAllowedRoutes()
    {
        $this->load->library('controllerlist');

        $routes = $this->controllerlist->getAppRoutes();
        $exists = [];
        $allows = Helpers::getAllowed();

        # Unsetting routes asigned and put it in the assigned variable
        foreach (array_keys($this->routes->getRoutes()) as $name) {
            $exists[] = $name;

            if (($key = array_search($name, $routes)) !== false) {
                unset($routes[$key]);
            }
        }

        # Deleting routes assigned to allowed
        foreach ($allows as $allow) {
            if (($key = array_search($allow, $routes)) !== false) {
                unset($routes[$key]);
            }
        }

        return [
            'available' => $routes,
            'assigned' => $exists
        ];
    }

    public function add($routes)
    {
        if (!empty($routes) && is_array($routes)) {
            try {
            	$this->db->trans_begin();

            	foreach ($routes as $route) {
                    if (substr($route, 0, 1) == '/') {
                        $route = substr($route, 1);
                    }

                    $this->_add($route);

            	}

            	$this->db->trans_commit();

            } catch (Exception $exc) {
                $this->db->trans_rollback();
                return false;
            }

            return true;
        }
    }

    public function remove($routes)
    {
        if (!empty($routes) && is_array($routes)) {
            try {
            	$this->db->trans_begin();

	            foreach ($routes as $route) {
                	$this->_remove($route);
	            }

	            $this->db->trans_commit();

            } catch (Exception $exc) {
                $this->db->trans_rollback();
            	return false;
            }
        }
    }

    private function _add($route)
    {
        if (!empty($route)) {
            $model = new Allowed();
            $model->allowed = $route;
            return $model->save();
        }
        return false;
    }

    private function _remove($route)
    {
        if (!empty($route)) {
            return $this->delete(['allowed' => $route]);
        }
        return false;
    }

}

/* End of file Allowed.php */
/* Location: ./application/models/rbac/Allowed.php */