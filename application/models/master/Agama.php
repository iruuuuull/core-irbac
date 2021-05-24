<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Agama extends MY_Model {

	public $tableName = 'm_agama';
	public $datatable_columns = ['id', 'nama_agama'];
	public $datatable_search = ['id', 'nama_agama'];
    public $blameable = true;
    public $timestamps = true;
    public $soft_delete = false;


     public  function getListAgama($dropdown = false)
    {
       $religions = $this->findAll();

        if ($dropdown) {
			$list_agama = ['' => '- Pilih Agama -'];
		} else {
			$list_agama = [];
		}

		foreach ($religions as $key => $religion) {
			$list_agama[$religion->id] = $religion->nama_agama;
		}

		return $list_agama;
    }


}