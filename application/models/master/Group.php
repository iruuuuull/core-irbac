<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Group extends MY_Model {

	public $tableName = 'tbl_group';
    public $datatable_columns = ['id', 'label', 'desc'];
    public $datatable_search = ['label', 'desc'];
	public $soft_delete = false;

	const ADMIN = 1;
	const USER = 2;
	const ADMIN_DIR = 3;
	const ADMIN_CABANG = 4;
	const MANAJEMEN_HO = 5;
	const MANAJEMEN_DIR = 6;
	const MANAJEMEN_CABANG = 7;

	public function getListGroup($parent = '')
	{
		$data_groups = $this->getAll();


		$groups = [];
		foreach ($data_groups as $key => $value) {
			$child_of = json_decode($value->parent_id, true);

			if (empty($child_of)) {
				$child_of = [];
			}

			if (!empty($parent) && !empty(array_intersect($parent, $child_of))) {
				$groups[] = $value;
			} elseif(empty($parent)) {
				$groups[] = $value;
			}
		}

		return $groups;
	}

	public function getGroups($parent = '')
	{
		$list_group = $this->getListGroup($parent);

		$groups = [];
		foreach ($list_group as $key => $value) {
			$groups[] = $value->id;
		}

		return array_unique($groups);
	}

	public function assignedPermissions()
	{
		return $this->hasMany('authassignment', 'group_id', 'id');
	}

	public function addAssignment($permissions)
    {
        if (!empty($permissions) && is_array($permissions)) {
            try {
                $this->db->trans_begin();

                foreach ($permissions as $permission) {
                    $this->_add($permission);
                }

                $this->db->trans_commit();

            } catch (Exception $exc) {
                $this->db->trans_rollback();
                return false;
            }

            return true;
        }
    }

    public function removeAssignment($permissions)
    {
    	if (!empty($permissions) && is_array($permissions)) {
            try {
            	$this->db->trans_begin();

	            foreach ($permissions as $permission) {
                	$this->_remove($permission);
	            }

	            $this->db->trans_commit();

            } catch (Exception $exc) {
                $this->db->trans_rollback();
            	return false;
            }
        }
    }

    private function _add($permission)
    {
    	$model_permission = $this->permission->findOne(['name' => $permission]);

        if (!empty($permission) && !empty($model_permission)) {
            $model = new Authassignment;
            $model->auth_item_id = $model_permission->id;
            $model->group_id = $this->id;
            return $model->save();
        }

        return false;
    }

    private function _remove($permission)
    {
        $model_permission = $this->permission->findOne(['name' => $permission]);

        if (!empty($permission) && !empty($model_permission)) {
            return $this->authassignment->delete([
            	'auth_item_id' => $model_permission->id, 
            	'group_id' => $this->id
            ]);
        }
        return false;
    }

}
