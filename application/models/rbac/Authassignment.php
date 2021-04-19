<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Authassignment extends MY_Model {
	public $tableName = 'tbl_auth_assignment';
	public $soft_delete = false;

	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			'master/group',
			'rbac/permission',
		]);
	}

	public function getAssignment($id)
	{
		$group = $this->group->findOne($id);

		$list_permissions = $this->permission->getAll(['type' => Permission::TYPE_PERMISSION]);
		$assigned_permission = $group->assignedPermissions;
		$permissions = [];
        $exists = [];

		if ($list_permissions) {
			$permissions = array_column($list_permissions, 'name');
		}

        foreach ($assigned_permission as $keys => $model) {
        	$data_permission = $model->getPermission;

            if (($key = array_search($data_permission->name, $permissions)) !== false) {
            	$exists[] = $data_permission->name;
            	unset($permissions[$key]);
            }
        }

        return [
            'available' => array_values($permissions),
            'assigned' => $exists
        ];
	}

	public function getPermission()
	{
		return $this->hasOne('permission', 'id', 'auth_item_id');
	}

	public function getPermissionsByGroup($groups)
    {
        $assignments = $this->findAll(['group_id' => $groups]);
        $permissions = [];

        foreach ($assignments as $key => $assignment) {
        	$permissions[] = $assignment->getPermission;
        }

        return $permissions;
    }
}

/* End of file Authassignment.php */
/* Location: ./application/models/rbac/Authassignment.php */
