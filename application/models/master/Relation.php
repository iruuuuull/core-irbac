<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Relation extends MY_Model {

	public $tableName = 'tbl_m_relation';

	public function getListRelation($relations = [], $dropdown = false)
	{
		if (empty($relations)) {
			$relations = $this->getAll();
		}

		if ($dropdown) {
			$list_relation = ['' => '- Pilih Hubungan -'];
		} else {
			$list_relation = [];
		}

		foreach ($relations as $key => $relation) {
			$list_relation[$relation->id] = $relation->jenis_hubungan;
		}

		return $list_relation;
	}

}

/* End of file Relation.php */
/* Location: ./application/models/master/Relation.php */