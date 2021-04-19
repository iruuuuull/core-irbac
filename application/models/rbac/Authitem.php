<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Authitem extends MY_Model {
	public $tableName = 'tbl_auth_item';
	public $soft_delete = false;
    public $blameable = true;
    public $timestamps = true;

	const TYPE_ROUTE = 1;
    const TYPE_PERMISSION = 2;

    public function __construct()
    {
    	parent::__construct();
        $this->load->model([
            'rbac/routes',
            'rbac/authitemchild',
        ]);
    }

    /**
     * Get items
     * @return array
     */
    public function getItems($type)
    {
        $avaliable = [];

        foreach (array_keys($this->permission->getPermissions()) as $name) {
            $avaliable[$name] = $type == self::TYPE_ROUTE ? 'route' : 'permission';
        }

        $assigned = [];
        foreach ($manager->getChildren($this->_item->name) as $item) {
            $assigned[$item->name] = $item->type == 1 ? 'role' : ($item->name[0] == '/' ? 'route' : 'permission');
            unset($avaliable[$item->name]);
        }
        unset($avaliable[$this->name]);
        return[
            'avaliable' => $avaliable,
            'assigned' => $assigned
        ];
    }

    public function add($routes, $type)
    {
        if ($type === self::TYPE_ROUTE) {
            return $this->routes->addRoutes($routes);
        } elseif ($type === self::TYPE_PERMISSION) {
            return $this->authitemchild->addPermissions($routes, $this);
        }
    }

    public function remove($routes, $type)
    {
        if ($type === self::TYPE_ROUTE) {
            return $this->routes->removeRoutes($routes);
        } elseif ($type === self::TYPE_PERMISSION) {
            return $this->authitemchild->removePermissions($routes, $this);
        }
    }

    public function blameableBehavior()
    {
        return [
            'createdByAttribute' => 'created_by',
            'updatedByAttribute' => 'updated_by',
            'value' => $this->session->userdata('identity')->id
        ];
    }

    public function itemChild()
    {
        return $this->hasMany('authitemchild', 'parent', 'name');
    }
}

/* End of file AuthItem.php */
/* Location: ./application/models/rbac/AuthItem.php */