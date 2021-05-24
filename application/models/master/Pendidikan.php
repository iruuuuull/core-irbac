<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pendidikan extends MY_Model {

	public $tableName = 'm_pendidikan';
	public $datatable_columns = ['id', 'kode_pendidikan'];
	public $datatable_search = ['id', 'kode_pendidikan'];
	public $blameable = true;
	public $timestamps = true;
	public $soft_delete = false;

	 public  function getListPendidikan($dropdown = false)
    {
       $pendidikans = $this->findAll();

        if ($dropdown) {
			$list_pendidikan = ['' => '- Pilih Pendidikan -'];
		} else {
			$list_pendidikan = [];
		}

		foreach ($pendidikans as $key => $pendidikan) {
			$list_pendidikan[$pendidikan->id] = $pendidikan->kode_pendidikan;
		}

		return $list_pendidikan;
    }

}

/* End of file Pendidikan.php */
/* Location: ./application/models/master/Pendidikan.php */