<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Provinsi extends MY_Model {

	public $tableName = 'wilayah_provinsi';
	public $datatable_columns = ['id', 'nama'];
	public $datatable_search = ['id', 'nama'];
    public $blameable = true;
    public $timestamps = true;
    public $soft_delete = false;


     public  function getListProvinsi($dropdown = false)
    {
       $provinsis = $this->findAll();

        if ($dropdown) {
			$list_provinsi = ['' => '- Pilih Provinsi -'];
		} else {
			$list_provinsi = [];
		}

		foreach ($provinsis as $key => $provinsi) {
			$list_provinsi[$provinsi->id] = $provinsi->nama;
		}

		return $list_provinsi;
    }


}