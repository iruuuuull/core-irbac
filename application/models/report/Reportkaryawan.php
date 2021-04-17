<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Reportkaryawan extends CI_Model {

	public $tableUser = 'tbl_user_detail';
	public $tableUser_head = 'tbl_user';
	public $tableInstitusi = 'tbl_m_sbu';
	public $tableUnit = 'tbl_m_unit';
	public $tableBagian = 'tbl_m_department';
	public $tableJabatan = 'tbl_m_jabatan';
	public $tableStatusKerja = 'tbl_m_status_kerja';



	public function	Dataget(){

		$query = $this->db->select([
			'tbl_user_detail.user_id',
			'tbl_user_detail.nik',
			'tbl_user_detail.nama_depan',
			'tbl_user.email',
			'tbl_user_detail.nama_tengah',
			'tbl_user_detail.nama_belakang',
			'tbl_m_department.department',
			'tbl_user_detail.job_title',
			'tbl_m_unit.nama_unit',
			'tbl_m_status_kerja.status',
			'tbl_user_detail.tanggal_selesai',
		])
		->join('tbl_m_department', 'tbl_m_department.id = tbl_user_detail.department_id')
		->join('tbl_user', 'tbl_user.id = tbl_user_detail.user_id')
		->join('tbl_m_unit', 'tbl_m_unit.id = tbl_user_detail.unit_id')
		->join('tbl_m_status_kerja', 'tbl_m_status_kerja.id = tbl_user_detail.status_id')
		->get($this->tableUser);

		return $query->result_array();
 	}

	public function	getData($columns='', $table = ''){
		if($columns != null){
			$this->db->select($columns);
		}else{
			$this->db->select('*');
		}
 		$query = $this->db->get($table);
 		return $query;
 	}

	public function getListGroupUnit()
	{
		$query = $this->db->select('*')
						  ->where('unit_level', 1)
						  ->or_where('unit_level', 2)
						  ->get('tbl_m_unit');
		return $query;
	}

	public function getUnit($param = '')
	{
		$query = $this->db->get_where('tbl_m_unit', $param);
		return $query->result_array();
	}

	public function getBagianData($param = '')
	{
		$query = $this->db->get_where('tbl_m_department', $param);
		return $query->result_array();
	}

	public function FindKaryawan($params = ''){							

 	 	$query = $this->db->select([
 						'tbl_user_detail.user_id',
 						'tbl_user.email',
 						'tbl_user_detail.nik',
 						'tbl_user_detail.nama_depan',
 						'tbl_user_detail.nama_tengah',
 						'tbl_user_detail.nama_belakang',
 						'tbl_m_department.department',
 						'tbl_user_detail.job_title',
						'tbl_m_unit.nama_unit',
						'tbl_m_unit.sbu_id',
						'tbl_m_status_kerja.status',
						'tbl_user_detail.tanggal_selesai',
 					])
 					->join('tbl_m_department', 'tbl_m_department.id = tbl_user_detail.department_id')
 					->join('tbl_user', 'tbl_user.id = tbl_user_detail.user_id')
 					->join('tbl_m_unit', 'tbl_m_unit.id = tbl_user_detail.unit_id')
 					->join('tbl_m_status_kerja', 'tbl_m_status_kerja.id = tbl_user_detail.status_id')
					->where($params)
		    		->get($this->tableUser);

 		return $query->result_array();
 	}

 	public function CountKaryawan($params = ''){
 		$query = $this->db->select([
				'tbl_user_detail.nama_depan',
				'tbl_user_detail.nama_tengah',
				'tbl_user_detail.nama_belakang',
				'tbl_m_department.department',
				'tbl_user_detail.job_title',
				'tbl_m_unit.nama_unit',
				'tbl_m_status_kerja.status',
				'tbl_user_detail.tanggal_selesai',
			])
			->join('tbl_m_department', 'tbl_m_department.id = tbl_user_detail.department_id')
			->join('tbl_m_unit', 'tbl_m_unit.id = tbl_user_detail.unit_id')
			->join('tbl_m_status_kerja', 'tbl_m_status_kerja.id = tbl_user_detail.status_id')
			->where($params)
			->get($this->tableUser);

 		return $query;
 	}

}
