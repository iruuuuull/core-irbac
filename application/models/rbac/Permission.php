<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once 'AuthItem.php';

class Permission extends Authitem {

	public $datatable_columns = ['name', 'description'];
	public $datatable_search = ['name', 'description'];

    public function __construct()
    {
        parent::__construct();
        $this->load->model([
            'rbac/routes',
            'rbac/authitemchild',
        ]);
    }

	public function rules()
    {
        return [
            [
                'field' => 'name',
                'rules' => 'required|regex_match[/^[a-z\-]+$/]',
                'errors' => [
                    'required' => 'Nama Permission wajib diisi',
                    'regex_match' => 'Hanya mengizinkan huruf dan strip',
                ],
            ],
        ];
    }

	public function getPermissions()
	{
		$model = $this->findAll(['type' => self::TYPE_PERMISSION]);
		$permissions = [];

		foreach ($model as $key => $value) {
			$permissions[$value->name] = $value;
		}

		return $permissions;
	}

	protected function _get_datatables_query()
    {
        $this->db->from($this->tableName)->where(['type' => self::TYPE_PERMISSION]);
 
        $i = 0;
        foreach ($this->datatable_search as $item) {
        	# jika datatable mengirimkan pencarian dengan metode POST
            if($_POST['search']['value']) {
                 
                if ($i === 0) {
                    $this->db->group_start(); 
                    $this->db->like($item, $_POST['search']['value']);

                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
 
                if (count($this->datatable_search) - 1 == $i) {
                    $this->db->group_end(); 
                }
            }

            $i++;
        }

        if(isset($_POST['order'])) {
            $this->db->order_by($this->datatable_columns[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);

        } elseif(isset($this->datatable_order)) {
            $order = $this->datatable_order;
            $this->db->order_by(key($order), $order[key($order)]);

        }
    }

    public function getUnPermissions($id)
    {
        $routes = $this->routes->getRoutes();
        $exists = $this->getChild($id);

        # Unsetting routes asigned and put it in the assigned variable
        foreach ($exists as $name) {
            unset($routes[$name]);
        }

        return [
            'available' => array_keys($routes),
            'assigned' => $exists
        ];
    }

    public function getChild($id)
    {
        $model = $this->permission->findOne($id);
        $childs = [];

        foreach ($model->child as $key => $child) {
            $childs[] = $child->child;
        }

        return $childs;
    }

    public function child()
    {
        return $this->hasMany('authitemchild', 'parent', 'name');
    }

}

/* End of file Routes.php */
/* Location: ./application/models/rbac/Routes.php */