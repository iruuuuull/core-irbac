<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cutipembatalan extends MY_Model {

	public $tableName = 'tbl_cuti_pembatalan';
	public $soft_delete = false;
	public $blameable = true;
	protected $timestamps = true;

	public function blameableBehavior()
    {
        return [
            'createdByAttribute' => 'created_by',
            'updatedByAttribute' => 'updated_by',
            'value' => $this->session->userdata('identity')->id
        ];
    }

}

/* End of file Cutipembatalan.php */
/* Location: ./application/models/transaksi/Cutipembatalan.php */