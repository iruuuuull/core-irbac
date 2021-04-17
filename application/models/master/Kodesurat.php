<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Kodesurat extends MY_Model {

	public $tableName = 'tbl_m_letter_code';
	public $datatable_columns = ['lc.id', 'lc.sbu_code', 'lc.sbu_code2', 'lc.desc_sbu_code2', 'mu.nama_unit', 'lo.desc'];
	public $datatable_search = ['lc.id', 'lc.sbu_code', 'lc.sbu_code2', 'lc.desc_sbu_code2', 'mu.nama_unit', 'lo.desc'];
	public $soft_delete = false;

  

	protected function _get_datatables_query()
    {
        $this->setAlias('lc')->find()
            ->select($this->datatable_columns)
            ->join('tbl_m_unit as mu', 'mu.id = lc.unit_id')
            ->join('tbl_m_letter_owner as lo', 'lo.owner_letter_id = lc.owner_letter_id');

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

    public function getListCode($unit = [],$dropdown = false)
    {   
        if($unit){
            $sbus = $this->getAll(['unit_id' => $unit]);
        }else{
            $sbus = $this->findAll();
        }

        if ($dropdown) {
            $list_sbu = ['' => '- Pilih -'];
        } else {
            $list_sbu = [];
        }

        foreach ($sbus as $key => $sbu) {
            $list_sbu[$sbu->sbu_code2] = $sbu->desc_sbu_code2;
        }

        return $list_sbu;
 
    }

    public function getOwner($params){


        $query = $this->db->select([
            'tbl_m_letter_code.id',
            'tbl_m_letter_code.desc_sbu_code2',
            'tbl_m_letter_code.holding_code',
            'tbl_m_letter_code.sbu_code',
            'tbl_m_letter_code.sbu_code2',
            'last_letter_no',
            'tbl_m_letter_owner.owner_letter_id',
            'year',
        ])
        ->join('tbl_m_letter_owner', 'tbl_m_letter_owner.owner_letter_id = tbl_m_letter_code.owner_letter_id')
        ->where($params)
        ->get($this->tableName);

        return $query->row();
    }

   

}
