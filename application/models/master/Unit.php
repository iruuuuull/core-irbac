<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Unit extends MY_Model {

	public $tableName = 'tbl_m_unit';
	public $soft_delete = false;
	public $datatable_columns = ['id', 'kode_unit', 'nama_unit','kode_induk'];
	public $datatable_search = ['kode_unit', 'nama_unit'];
	public $time_zone = ['Asia/Jakarta', 'Asia/Pontianak','Asia/Makassar','Asia/Jayapura'];
	public $unit_level = ['Pusat', 'Cabang','Kampus'];


	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			'master/user',
			'master/group',
		]);
	}

	public function getListUnits($units =[], $dropdown = false)
	{
		if (empty($units)) {
			$units = $this->getAll();
		}

		if ($dropdown) {
			$list_unit = ['' => '- Pilih Unit -'];
		} else {
			$list_unit = [];
		}

		foreach ($units as $key => $unit) {
			$list_unit[$unit->id] = "[{$unit->kode_unit}] - " . $unit->nama_unit;
		}

		return $list_unit;
	}

	public function getUnitByUserGroup($id, $id_only = false)
	{
		# Get user yg mau di edit
		$user = $this->user->findOne($id);
		$unit_level = [];
		$unit_group = [];

		if ($user->isHasGroup(Group::ADMIN)) {
			$unit_level = [1, 2, 3];
		} elseif ($user->isHasGroup(Group::ADMIN_DIR)) {
			$unit_level = [2, 3];
		} elseif ($user->isHasGroup(Group::ADMIN_CABANG)) {
			$unit_level = [3];
		}

		# Get user login
		$user_login = $this->user->findOne($this->session->userdata('identity')->id);
		$user_detail = $user_login->userDetail;

		if ($user_detail && $user_detail->unit_id && !$user->isHasGroup(Group::ADMIN)) {
			$user_unit = $this->findOne($user_detail->unit_id);
			$unit_groups = $this->find()
							->select('id')
							->or_where([
								'kode_unit' => $user_unit->kode_unit,
								'kode_induk' => $user_unit->kode_unit
							])->get()->result_array();

			foreach ($unit_groups as $key => $value) {
				$unit_group[] = $value['id'];
			}
		}

		$units = $this->find();

		if ($unit_level) {
			$units->where_in('unit_level', $unit_level);
		}

		if ($unit_group) {
			$units->where_in('id', $unit_group);
		}

		$result = $units->get()->result();

		if ($id_only) {
			$id_units = [];
			foreach ($result as $key => $value) {
				$id_units[] = $value->id;
			}

			return $id_units;
		} else {
			return $result;
		}

	}

	public function getDetailUnit($params)
	{
		$query = $this->db->select([
			'tbl_m_unit.id',
			'tbl_m_unit.kode_unit',
			'tbl_m_unit.nama_unit',
			'tbl_m_unit.unit_level',
			'tbl_m_unit.kode_induk',
			'tbl_m_unit.timezone',
			'tbl_m_unit.sbu_id',
			'tbl_m_unit.kode_owner',
			'tbl_m_sbu.sbu',
			'tbl_m_ownership.ownership',
		])
		->join('tbl_m_sbu', 'tbl_m_sbu.id = tbl_m_unit.sbu_id')
	 	->join('tbl_m_ownership', 'tbl_m_ownership.kode_owner = tbl_m_unit.kode_owner')
	 	->where([
	 				'tbl_m_unit.id' => $params
	 			])
		->get($this->tableName);

		return $query->row();
	}

		public function getListGroupUnit()
	{
		$query = $this->db->select('*')
						  ->where('unit_level', 1)
						  ->or_where('unit_level', 2)
						  ->get('tbl_m_unit');

		$row = $query->result();

		$list_unit = ['' => '- Pilih Kode Induk -'];

		foreach ($row as $key => $unit) {
			$list_unit[$unit->kode_unit] = "[{$unit->kode_unit}] - " . $unit->nama_unit;
		}

		return $list_unit;
	}

	public function getTimezone()
	{	

		$list_timezone = ['' => '- Pilih Time Zone -'];	
		
		for($i=0; $i < count($this->time_zone); $i++){
		   $list_timezone[$this->time_zone[$i]] = $this->time_zone[$i];
		}
		return $list_timezone;

	}

	public function getUnitLevel()
	{	

		$list_unit_level = ['' => '- Pilih Unit Level -'];	
		
		for($i=0; $i < count($this->unit_level); $i++){
		   $list_unit_level[$i+1] = $this->unit_level[$i];
		}
		return $list_unit_level;

	}

}

/* End of file Unit.php */
/* Location: ./application/models/master/Unit.php */