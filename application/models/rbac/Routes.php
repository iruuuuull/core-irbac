<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once 'AuthItem.php';

class Routes extends Authitem {

	public function getRoutes()
	{
		$model = $this->findAll(['type' => self::TYPE_ROUTE]);
		$routes = [];

		foreach ($model as $key => $value) {
			$routes[$value->name] = $value;
		}

		return $routes;
	}

	/**
     * Get avaliable and assigned routes
     * @return array
     */
    public function getUnAllowedRoutes()
    {
        $manager = Yii::$app->getAuthManager();
        $routes = $this->getAppRoutes();
        $exists = [];
        foreach (array_keys($manager->getPermissions()) as $name) {
            if ($name[0] !== '/') {
                continue;
            }
            $exists[] = $name;
            unset($routes[$name]);
        }
        return[
            'avaliable' => array_keys($routes),
            'assigned' => $exists
        ];
    }

    public function addRoutes($routes)
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

    public function removeRoutes($routes)
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
            $model = new Routes;
            $model->name = $route;
            $model->type = self::TYPE_ROUTE;
            return $model->save();
        }

        return false;
    }

    private function _remove($route)
    {
        if (!empty($route)) {
            return $this->delete(['name' => $route]);
        }
        return false;
    }

}

/* End of file Routes.php */
/* Location: ./application/models/rbac/Routes.php */