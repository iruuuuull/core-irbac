<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Kelurahan extends MY_Model {

	public $tableName = 'wilayah_desa';
	public $datatable_columns = ['id','kecamatan_id', 'nama'];
	public $datatable_search = ['id','kecamatan_id','nama'];
    public $blameable = true;
    public $timestamps = true;
    public $soft_delete = false;


     public function getListKelurahan($kelurahans = [], $dropdown = false)
	{
		if (empty($kelurahans)) {
			$kelurahans = $this->getAll();
		}

		if ($dropdown) {
			$list_kelurahan = ['' => '- Pilih Kelurahan -'];
		} else {
			$list_kelurahan = [];
		}

		foreach ($kelurahans as $key => $kecamatan) {
			$list_kelurahan[$kecamatan->id] = $kecamatan->nama;
		}

		return $list_kelurahan;
	}


}