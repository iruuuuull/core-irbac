<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Kecamatan extends MY_Model {

	public $tableName = 'wilayah_kecamatan';
	public $datatable_columns = ['id','kabupaten_id', 'nama'];
	public $datatable_search = ['id','kabupaten_id','nama'];
    public $blameable = true;
    public $timestamps = true;
    public $soft_delete = false;


     public function getListKecamatan($kecamatans = [], $dropdown = false)
	{
		if (empty($kecamatans)) {
			$kecamatans = $this->getAll();
		}

		if ($dropdown) {
			$list_kecamatan = ['' => '- Pilih Kecamatan -'];
		} else {
			$list_kecamatan = [];
		}

		foreach ($kecamatans as $key => $kecamatan) {
			$list_kecamatan[$kecamatan->id] = $kecamatan->nama;
		}

		return $list_kecamatan;
	}


}