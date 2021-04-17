<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Group extends MY_Model {

	public $tableName = 'tbl_group';
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

}
