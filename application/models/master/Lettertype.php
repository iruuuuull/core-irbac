<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Lettertype extends MY_Model {

	public $tableName = 'tbl_m_letter_type';
	public $datatable_columns = ['id', 'letter_code', 'letter_desc'];
	public $datatable_search = ['letter_code', 'letter_desc'];
	public $soft_delete = false;


	public function getListType($dropdown = false)
	{
		$types = $this->getAll();

		if ($dropdown) {
			$list_type = ['' => '- Pilih Type -'];
		} else {
			$list_type = [];
		}

		foreach ($types as $key => $type) {
			$list_type[$type->letter_code] = $type->letter_desc;
		}

		return $list_type;
	}

}
