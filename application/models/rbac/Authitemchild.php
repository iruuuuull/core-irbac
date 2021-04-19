<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Authitemchild extends MY_Model {
	public $tableName = 'tbl_auth_item_child';
	public $soft_delete = false;

	private $_parent;

    public function __construct()
    {
    	parent::__construct();
        $this->load->model([
            'rbac/authitem',
        ]);
    }

    public function addPermissions($routes, Authitem $parent)
    {
    	$this->_parent = $parent;

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

    public function removePermissions($routes, Authitem $parent)
    {
    	$this->_parent = $parent;

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
            $model = new Authitemchild;
            $model->parent = $this->_parent->name;
            $model->child = $route;
            return $model->save();
        }

        return false;
    }

    private function _remove($route)
    {
        if (!empty($route)) {
            return $this->delete([
            	'parent' => $this->_parent->name, 
            	'child' => $route
            ]);
        }
        return false;
    }

}