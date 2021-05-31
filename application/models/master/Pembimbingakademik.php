<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pembimbingakademik extends MY_Model {

	public $tableName = 'Pembimbing_ak';
	public $datatable_columns = ['id','pa_id', 'pa_name'];
	public $datatable_search = ['id','pa_id','pa_name'];
    public $blameable = true;
    public $timestamps = true;
    public $soft_delete = false;


     public  function getListPa($dropdown = false)
    {
       $counselors = $this->findAll();

        if ($dropdown) {
			$list_pembimbing = ['' => '- Pilih Pembimbing Akademik -'];
		} else {
			$list_pembimbing = [];
		}

		foreach ($counselors as $key => $counselor) {
			$list_pembimbing[$counselor->id] = $counselor->pa_name;
		}

		return $list_pembimbing;
    }


}