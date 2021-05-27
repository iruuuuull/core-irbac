<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Kabupaten extends MY_Model {

	public $tableName = 'wilayah_kabupaten';
	public $datatable_columns = ['id','provinsi_id', 'nama'];
	public $datatable_search = ['id','provinsi_id','nama'];
    public $blameable = true;
    public $timestamps = true;
    public $soft_delete = false;


     public function getListKabupaten($kabupatens = [], $dropdown = false)
	{
		if (empty($kabupatens)) {
			$kabupatens = $this->getAll();
		}

		if ($dropdown) {
			$list_kabupaten = ['' => '- Pilih Kabupaten -'];
		} else {
			$list_kabupaten = [];
		}

		foreach ($kabupatens as $key => $kabupaten) {
			$list_kabupaten[$kabupaten->id] = $kabupaten->nama;
		}

		return $list_kabupaten;
	}


}