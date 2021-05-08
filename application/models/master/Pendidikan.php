<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pendidikan extends MY_Model {

	public $tableName = 'tbl_m_pendidikan';

	public function getListPendidikan()
	{
		$this->db->select('id, kode_pendidikan');
		$query = $this->db->get($this->tableName);

		$pendidikan = [];
		foreach ($query->result() as $key => $value) {
			$pendidikan[$value->id] = $value->kode_pendidikan;
		}

		return $pendidikan;
	}

}

/* End of file Pendidikan.php */
/* Location: ./application/models/master/Pendidikan.php */