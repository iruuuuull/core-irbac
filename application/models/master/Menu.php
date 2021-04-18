<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Menu extends MY_Model {

	public $tableName = 'tbl_menu';
	public $soft_delete = false;

	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			'master/menugroup',
            'master/menutype',
		]);
	}

	public function getAllMenu($type)
	{
        $criteria = $this->menu->find()
                    ->where(['menu_type' => $type])
                    ->order_by('menu_order');
        $result = $this->menu->findAll($criteria);

		return $result;
	}

	public function getMenu($menu_type = 'backend-menu')
	{
		$menu = [];
        $models = $this->getAllMenu($menu_type);

        foreach ($models as $key => $val) {
        	$menu_groups = $val->menuGroup;

            if (!$val->menu_parent) {
                $items = [
                    'url' => $val->menu_url,
                    'label' => $val->label,
                    'icon' => $val->class,
                    'content' => $val->description,
                    'assign' => isset($menu_groups) ? self::getMenuGroup($menu_groups) : [],
                ];

                $menu[] = $this->rebuildArray($models, $items, $val->id);
            }
        }
        return $menu;
	}

	private function rebuildArray($models, $data = [], $id = null)
	{
		if (!empty($models)) {
            foreach ($models as $key => $val) {
            	$menu_groups = $val->menuGroup;

                if ($val->menu_parent == $id) {
                    $items = [
                        'url' => $val->menu_url,
                        'label' => $val->label,
                        'icon' => $val->class,
                        'content' => $val->description,
                        'assign' => isset($menu_groups) ? self::getMenuGroup($menu_groups) : [],
                    ];
                    $data['items'][] = $this->rebuildArray($models, $items, $val->id);
                }
            }
        }

        return $data;
	}

	private static function getMenuGroup($groups)
	{
		$result = [];
        if (!empty($groups)) {
            foreach ($groups as $key => $value) {
                $result[] = $value->group_id;
            }
        }

        return $result;
	}

	public function getMenuGroups($menu_id)
	{
		return $this->menugroup->getAll(['menu_id' => $menu_id]);
	}

    public function menuGroup()
    {
        return $this->hasMany('menugroup', 'menu_id', 'id');
    }

    public function deleteMenus($list_id = [])
    {
        if (!empty($list_id)) {
            foreach ($list_id as $id) {
                (new Menugroup)->delete(['menu_id' => $id]);
                $this->menu->delete(['id' => $id]);
            }

            return true;
        }

        return false;
    }

    private function doSaveMenu($data, $id)
    {
        $menu_type = $this->menutype->get($id);
        $menugroup = new Menugroup;

        if (!empty($data['item'])) {
            $parent = [];
            $dummy_id = null;

            foreach ($data['item'] as $keys => $row) {
                if (empty($row['menu_id'])) {
                    $model = $this->menu;
                } else {
                    $model = $this->menu->findOne($row['menu_id']);
                }


                $group =  [];
                foreach ($row as $key => $value) {
                    // set $group value for save then
                    if ($key == "group") {
                        $group = $value;
                    } elseif($key == "dummy_id") {
                        $dummy_id = $value;
                    } else {
                        $model->{$key} = $value;
                    }

                    if ($key == "menu_parent") {
                        if (!is_numeric($value)) {
                            if (isset($parent[$value])) {
                                $model->$key = $parent[$value];
                            }
                        } else {
                            $model->$key = $value;
                        }
                    }
                }

                $model->menu_type = $menu_type->menu_type;

                if (!empty($model->menu_id)) {
                    $model->id = $model->menu_id;
                }

                unset($model->menu_id);

                if ($model->save()) {
                    // prepare array for parent
                    $parent[$dummy_id] = $model->id;
                    $menugroup->delete(['menu_id' => intval($model->id)]);

                    if (!empty($group)) {
                        foreach ($group as $groupId) {
                            self::saveMenuGroup($model->id, $groupId);
                        }
                    }
                }
            }

            return true;
        }
        return false;
    }

    public function saveMenu($post, $id)
    {
        if (!empty($post) && !empty($id)) {
            $this->db->trans_start();

            try {
                $list_id = !empty($post['Menu']['remove']) ? $post['Menu']['remove'] : [];
                $this->deleteMenus($list_id);

                if ($this->doSaveMenu($post['Menu'], $id)) {
                    $this->db->trans_commit();
                    return true;
                } else {
                    throw new \Exception("Gagal simpan data menu");
                }

            } catch (\Exception $e) {
                $this->db->trans_rollback();
                throw new \Exception($e);
            }

            return false;
        }
    }

    private static function saveMenuGroup($menuId, $groupId)
    {
        if (!empty($menuId) && !empty($groupId)) {
            $model = new Menugroup;
            $model->menu_id = intval($menuId);
            $model->group_id = $groupId;
            if ($model->validate() && $model->save()) {
                return true;
            }
        }
        return false;
    }

}
