<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Designation extends MY_Model {

	public $tableName = 'tbl_designation';
	public $datatable_columns = ['td.id', 'td.grading_type_id', 'td.designation', 'td.combine_label', 'gt.teamwork_name'];
	public $datatable_search = ['td.designation', 'td.combine_label', 'gt.teamwork_name'];
	public $blameable = true;
    public $timestamps = true;
    const DELETED_AT = 'deleted_at';

	public function __construct()
	{
		parent::__construct();
	}

	protected function _get_datatables_query()
    {
        $this->db->select($this->datatable_columns)
        	->from($this->tableName .' as td')
        	->join('tbl_grading_type as gt', 'gt.id = td.grading_type_id', 'left');

        if ($this->soft_delete === true) {
            $this->db->where(['deleted_at' => null]);
        }
 
        $i = 0;
     
        foreach ($this->datatable_search as $item) {
        	# jika datatable mengirimkan pencarian dengan metode POST
            if($_POST['search']['value']) {
                 
                if ($i === 0) {
                    $this->db->group_start(); 
                    $this->db->like($item, $_POST['search']['value']);

                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
 
                if (count($this->datatable_search) - 1 == $i) {
                    $this->db->group_end(); 
                }
            }

            $i++;
        }

        if(isset($_POST['order'])) {
            $this->db->order_by($this->datatable_columns[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);

        } elseif(isset($this->datatable_order)) {
            $order = $this->datatable_order;
            $this->db->order_by(key($order), $order[key($order)]);

        }
    }

	public function getListDesignation($kelompok = '')
	{
		$lists = [];

		if ($kelompok) {
			$designations = $this->findAll(['grading_type_id' => $kelompok]);
		} else {
			$designations = $this->findAll();
		}

		foreach ($designations as $key => $value) {
			$lists[$value->id] = [
				'name' => $value->designation,
				'combined' => $value->combine_label
			];
		}

		return $lists;
	}

	public function jabatan()
	{
	    return $this->hasOne('jabatan', 'id', 'jabatan_id');
	}

	public function gradingType()
	{
	    return $this->hasOne('gradingtype', 'id', 'grading_type_id');
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

/* End of file Designation.php */
/* Location: ./application/models/master/Designation.php */