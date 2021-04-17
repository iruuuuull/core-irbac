<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Userlampiran extends MY_Model {

	public $tableName = 'tbl_user_lampiran';
	public $blameable = true;
    public $timestamps = true;

    public function __construct()
    {
    	parent::__construct();
    	$this->load->model([
    		'master/jenisdokumen',
    	]);
    }

    public function getListOfLampiran()
    {
    	$model = $this->jenisdokumen->findAll(['is_lampiran' => 1]);

    	$files = [];
    	foreach ($model as $key => $value) {
    		$files[$value->kode_dokumen] = $value->label;
    	}

    	return $files;
    }

    public function getLampiranByUser($id)
    {
    	$model = $this->findAll(['user_id' => $id]);

    	$dokumens = [];

    	foreach ($model as $key => $value) {
    		$dokumens[$value->m_jenis_dokumen_id] = $value;
    	}

    	return $dokumens;
    }

    public function blameableBehavior()
    {
        return [
            'createdByAttribute' => 'created_by',
            'updatedByAttribute' => 'updated_by',
            'value' => $this->session->userdata('identity')->id
        ];
    }

}

/* End of file Userlampiran.php */
/* Location: ./application/models/transaksi/Userlampiran.php */