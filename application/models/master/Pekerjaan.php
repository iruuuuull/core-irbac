<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pekerjaan extends MY_Model {

	public $tableName = 'm_pekerjaan';
	public $datatable_columns = ['id', 'kode_pekerjaan','label'];
	public $datatable_search = ['id', 'kode_pekerjaan','label'];
    public $blameable = true;
    public $timestamps = true;
    public $soft_delete = false;


     public  function getListPekerjaan($dropdown = false)
    {
       $works = $this->findAll();

        if ($dropdown) {
			$list_pekerjaan = ['' => '- Pilih Pekerjaan -'];
		} else {
			$list_pekerjaan = [];
		}

		foreach ($works as $key => $work) {
			$list_pekerjaan[$work->id] = $work->label;
		}

		return $list_pekerjaan;
    }


}