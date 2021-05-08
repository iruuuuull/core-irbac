<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee extends MY_Model {

	public $tableName = 'tbl_user_detail';
	public $datatable_columns = ['tbl_m_unit.nama_unit as Unit', " sum(case when tbl_user_detail.status_id='1' then 1 else 0 end) as Permanent,
	sum(case when tbl_user_detail.status_id='2' then 1 else 0 end) as Kontrak,
	sum(case when tbl_user_detail.status_id='1' then 1 when tbl_user_detail.status_id='2' then 1 else 0 end) as Total"];
	// public $datatable_search = ['tbl_m_unit.nama_unit as Unit'];
	public $soft_delete = false;

	protected function _get_datatables_query()
	{
		$this->db->from($this->tableName)
		->select($this->datatable_columns)
		->join('tbl_m_unit', 'tbl_m_unit.id = tbl_user_detail.unit_id')
		->join('tbl_m_status_kerja', 'tbl_m_status_kerja.id = tbl_user_detail.status_id')
		->group_by('tbl_m_unit.nama_unit');
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
	}

	public function getData(){
		$query = $this->db->select(["tbl_m_unit.nama_unit as Unit" ,  "sum(case when tbl_user_detail.status_id='1' then 1 else 0 end) as Permanent,
			sum(case when tbl_user_detail.status_id='2' then 1 when tbl_user_detail.status_id='3' then 1 else 0 end) as Kontrak,
			sum(case when tbl_user_detail.status_id='1' then 1 when tbl_user_detail.status_id='2' then 1 when tbl_user_detail.status_id='3' then 1 else 0 end) as Total"])
		->join('tbl_m_unit', 'tbl_m_unit.id = tbl_user_detail.unit_id')
		->join('tbl_m_status_kerja', 'tbl_m_status_kerja.id = tbl_user_detail.status_id')
		->group_by('tbl_m_unit.nama_unit')
		->get($this->tableName);


		return $query->result_array();
	}

}

/* End of file Attendance.php */
/* Location: ./application/models/transaksi/Attendance.php */