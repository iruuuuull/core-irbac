<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Department extends MY_Model {

	public $tableName = 'tbl_m_department';
	public $soft_delete = false;

	const KODEP_HR = 102;
	const IDEP_HR = 2;

	public function getListDepartment($departments = [], $dropdown = false)
	{
		if (empty($departments)) {
			$departments = $this->getAll();
		}

		if ($dropdown) {
			$list_department = ['' => '- Pilih Department -'];
		} else {
			$list_department = [];
		}

		foreach ($departments as $key => $department) {
			$list_department[$department->id] = "[{$department->kode_dep}] - " . $department->department;
		}

		return $list_department;
	}

}

/* End of file Department.php */
/* Location: ./application/models/master/Department.php */