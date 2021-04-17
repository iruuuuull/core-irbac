<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Userdetail extends MY_Model {

	public $tableName = 'tbl_user_detail';

	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			'master/status',
			'master/user',
			'master/unitgroup',
			'master/unit',
			'master/jabatan',
			'master/department',
			'master/grade',
			'master/gradingtype',
			'master/designation',
			'transaksi/attendance',
		]);
	}

	public function SelData($params = '',$table = ''){
		$query = $this->db->select('*')
		->where($params)
		->get($table);
		return $query;
	}

	public function CariSBU($params = ''){
 		$query = $this->db->select([
 						'tbl_m_sbu.kode_sbu',
 						'tbl_m_unit.kode_unit' 						
 					])
 					->join('tbl_m_unit', 'tbl_m_unit.sbu_id = tbl_m_sbu.id')
					->where($params)
					->get('tbl_m_sbu');
 		return $query->result_array();
 	}

 	public function CariStatus($params = ''){
 		$query = $this->db->select([
 						'tbl_m_status_kerja.kd_status'
 					])
 					->join('tbl_m_status_kerja', 'tbl_m_status_kerja.id = tbl_user_detail.status_id')
					->where($params)
					->get('tbl_user_detail');
 		return $query->result_array();
 	}

 	/**
 	 * [getNumEmployeeByStatus description]
 	 * @param  array/string $status [description]
 	 * @return objects               [description]
 	 */
 	public function getNumEmployeeByStatus($status = null)
 	{
 		$query_sum = $this->db->select([
 							'sk.kd_status',
 							'COUNT(ud.id) as jumlah_karyawan'
 						])
 						->from('tbl_m_status_kerja as sk')
 						->join('tbl_user_detail as ud', 'sk.id = ud.status_id', 'left')
 						->where([
 							'ud.deleted_at' => null,
 							'ud.deleted_by' => null
 						])
 						// ->group_start()
	 					// 	->where('DATE(ud.tanggal_selesai) >= ', date('Y-m-d'))
	 					// 	->or_where('ud.tanggal_selesai', null)
 						// ->group_end()
 						->group_by('sk.kd_status');

 		if (!empty($status)) {
 			if (is_array($status)) {
 				$query_sum->where_in('sk.kd_status', $status);
 			} else {
 				return $query_sum->where('sk.kd_status', $status)
 						  ->get()->row();
 			}
 		}

 		return $query_sum->get()->result();
 	}

 	public function getNumEmployeeByUnit($unit_group = null)
 	{
 		$query_sum = $this->db->select([
 							'sbu.kode_sbu',
 							'COUNT(ud.id) as jumlah_karyawan'
 						])
 						->from('tbl_m_sbu as sbu')
 						->join('tbl_m_unit as unit', 'sbu.id = unit.sbu_id', 'left')
 						->join('tbl_user_detail as ud', 'unit.id = ud.unit_id', 'left')
 						->where([
 							'ud.deleted_at' => null,
 							'ud.deleted_by' => null
 						])
 						// ->group_start()
	 					// 	->where('DATE(ud.tanggal_selesai) >= ', date('Y-m-d'))
	 					// 	->or_where('ud.tanggal_selesai', null)
 						// ->group_end()
 						->group_by('sbu.kode_sbu');

 		if (!empty($unit_group)) {
 			if (is_array($unit_group)) {
 				$query_sum->where_in('sbu.kode_sbu', $unit_group);
 			} else {
 				return $query_sum->where('sbu.kode_sbu', $unit_group)
 						  ->get()->row();
 			}
 		}

 		return $query_sum->get()->result();
 	}

 	public function getSumEmployeeStatus($status = null)
 	{
 		$summary = $this->getNumEmployeeByStatus($status);
 		$summaries = [];

 		if (!empty($summary)) {
 			$total = 0;
	 		foreach ($summary as $key => $value) {
	 			$status = $this->status->getStatus($value->kd_status);
	 			$status = 'status_' . str_replace(' ', '_', strtolower($status));

	 			$summaries[$status] = (int)$value->jumlah_karyawan;

	 			$total += $value->jumlah_karyawan;
	 		}

	 		$summaries['status_total'] = $total;
 		}

 		return $summaries;
 	}

 	public function getSumEmployeeUnit($unit_group = null)
 	{
 		$summary = $this->getNumEmployeeByUnit($unit_group);
 		$summaries = [];

 		if (!empty($summary)) {
 			$total = 0;
	 		foreach ($summary as $key => $value) {
	 			$unit_group = $this->unitgroup->getUnit($value->kode_sbu);
	 			$unit_group = 'unit_' . str_replace(' ', '_', strtolower($unit_group));

	 			$summaries[$unit_group] = (int)$value->jumlah_karyawan;

	 			$total += $value->jumlah_karyawan;
	 		}

	 		$summaries['unit_total'] = $total;
 		}

 		return $summaries;
 	}

 	public function attendanceByDate($date)
 	{
 		return $this->attendance->setAlias('ta')->find()
 					->select('ta.*')
 					->join('tbl_calendar as tc', 'tc.id = ta.calendar_id', 'left')
 					->where([
 						'tc.date' => date('Y-m-d', strtotime($date)),
 						'ta.user_id' => $this->user_id
 					])
 					->get()->row();
 	}

 	public function grade()
 	{
 		return $this->hasOne('grade', 'id', 'grade_id');
 	}

 	/**
 	 * [getListAtasans description]
 	 * @param  int     $id       [id dari table user detail]
 	 * @param  boolean $dropdown [description]
 	 * @return [type]            [description]
 	 */
 	public function getListAtasanByIdDetail($id, $dropdown = false)
	{
		$user_detail = $this->findOne(['id' => $id]);

		if ($user_detail && $user_detail->golongan) {
			return $this->getListAtasans(
				$user_detail->unit_id,
				$user_detail->department_id,
				$user_detail->grade_id,
				$user_detail->golongan
			);
		}

		return [];
	}

	/**
	 * [getListAtasans description]
	 * @param  int 			$unit_id		[description]
	 * @param  int 			$department_id	[description]
	 * @param  array|object $grade			[description]
	 * @param  array|object $jabatan 		[description]
	 * @return array 						[description]
	 */
 	public function getListAtasans($unit_id, $department_id, $jabatan, $golongan, $dropdown = false)
	{
		$atasans = [];

		if ($dropdown) {
			$list_atasan = ['' => '- Pilih Atasan -'];
		} else {
			$list_atasan = [];
		}

		// $model_jabatan = new Jabatan;

		// if (
		// 	$jabatan && in_array($jabatan->kode_jabatan, $model_jabatan->getRange(Jabatan::RANGE_STAFF))
		// ) {
		// 	$atasans = $this->setAlias('ud')->find()
		// 					->join('tbl_m_jabatan as mj', 'mj.id = ud.grade_id')
		// 					->where([
		// 						'ud.department_id' => $department_id,
		// 						'ud.unit_id' => $unit_id,
		// 						'mj.id > ' => $jabatan->id
		// 					])->get()->result();
		// } elseif (
		// 	$jabatan && in_array($jabatan->kode_jabatan, $model_jabatan->getRange(Jabatan::RANGE_HEAD))
		// ) {
		// 	$atasans = $this->setAlias('ud')->find()
		// 					->join('tbl_m_jabatan as mj', 'mj.id = ud.grade_id')
		// 					->where([
		// 						'ud.unit_id' => $unit_id,
		// 						'mj.id > ' => $jabatan->id
		// 					])->get()->result();
		// } elseif (
		// 	$jabatan && in_array($jabatan->kode_jabatan, $model_jabatan->getRange(Jabatan::RANGE_MANAGER))
		// ) {
		// 	$atasans = $this->setAlias('ud')->find()
		// 					->join('tbl_m_jabatan as mj', 'mj.id = ud.grade_id')
		// 					->where(['mj.id > ' => $jabatan->id])->get()->result();
		// }

		$atasans = $this->setAlias('ud')->find()
						->select('count(*), nik, nama_depan, nama_tengah, nama_belakang, user_id')
						->join('tbl_m_jabatan as mj', 'mj.id = ud.grade_id')
						->join('tbl_sub_grade as sg', 'sg.m_jabatan_id = mj.id')
						->where([
							'sg.sub_grade > ' => $golongan
						])
						->group_start()
						->or_where([
							'ud.department_id' => $department_id,
							'ud.unit_id' => $unit_id,
							'mj.id' => $jabatan
						])
						->group_end()
						->group_by('ud.id')
						->get()->result();

		foreach ($atasans as $key => $atasan) {
			$list_atasan[$atasan->user_id] = "[{$atasan->nik}] - " . $this->mergeFullName($atasan);
		}

		return $list_atasan;
	}

	public function mergeFullName($user_detail = [])
	{
		if (!empty($this->nama_depan) && empty($user_detail)) {
			$user_detail = $this;
		}

		$nama = '';

		if (!empty($user_detail->nama_depan) && $user_detail->nama_depan != '-') {
			$nama .= $user_detail->nama_depan;
		}

		if (!empty($user_detail->nama_tengah) && $user_detail->nama_tengah != '-') {
			$nama .= ' '. $user_detail->nama_tengah;
		}

		if (!empty($user_detail->nama_belakang) && $user_detail->nama_belakang != '-') {
			$nama .= ' '. $user_detail->nama_belakang;
		}

		return $nama;
	}

	public function getCutiCount()
	{
		return $this->current_leave + $this->last_leave;
	}

	public function getHr($id = null)
	{
		if (empty($id)) {
			$id = $this->user_id;
		}

		$userdetail = $this->findOne(['user_id' => $id]);

		if (empty($userdetail)) {
			return null;
		}

		$hr = (new self)->setAlias('ud')->find()
				->select('ud.*')
				->where([
					'unit_id' => $userdetail->unit_id, 
					'kode_dep' => Department::KODEP_HR
				])
				->join('tbl_m_department as md', 'md.id = ud.department_id')
				->get()->row();

		return $hr;
	}

	public function getAtasan($id = null)
	{
		if (empty($id)) {
			$id = $this->user_id;
		}

		$userdetail = $this->findOne(['user_id' => $id]);

		if (!empty($userdetail) && $userdetail->atasan_id) {
			return (new self)->findOne(['user_id' => $userdetail->atasan_id]);
		}

		return null;
	}

	public function getHead($id = null)
	{
		$atasan = $this->getAtasan($id);

		if ($atasan) {
			return $this->getAtasan($atasan->user_id);
		}

		return null;
	}

	public function getCeo()
	{
		return $this->findOne(['user_id' => User::ID_CEO]);
	}

	public function getImage()
	{
		$default = '/web/assets/pages/img/no_avatar.jpg';
		$img = $default;

		if ($this->profile_pic) {
			$img = $this->profile_pic;
		}

		if (!file_exists($img)) {
			$img = $default;
		}

		return str_replace('./', '', base_url($img));
	}

	public function jabatan()
	{
		return $this->hasOne('jabatan', 'id', 'grade_id');
	}

	public function mStatus()
	{
		return $this->hasOne('status', 'id', 'status_id');
	}

	public function unit()
	{
		return $this->hasOne('unit', 'id', 'unit_id');
	}

	public function department()
	{
		return $this->hasOne('department', 'id', 'department_id');
	}

	public function atasan()
	{
		return $this->hasOne('user', 'id', 'atasan_id');
	}

	public function gradingType()
	{
		return $this->hasOne('gradingtype', 'id', 'grading_type_id');
	}

	public function designation()
	{
		return $this->hasOne('designation', 'id', 'designation_id');
	}

	public function getAllRelations()
	{
		$details = $this->getAttributes();

		$details['m_status'] = [
			'kd_status' => def($this->mStatus, 'kd_status'),
			'status' => def($this->mStatus, 'status'),
		];

		$details['m_jabatan'] = [
			'grade' => def($this->jabatan, 'grade'),
			'nama_jabatan' => def($this->jabatan, 'nama_jabatan'),
			'kelas_jabatan' => def($this->jabatan, 'kelas_jabatan'),
			'desc' => def($this->jabatan, 'desc'),
		];

		$details['m_unit'] = [
			'kode_unit' => def($this->unit, 'kode_unit'),
			'nama_unit' => def($this->unit, 'nama_unit'),
		];

		$details['m_department'] = [
			'kode_dep' => def($this->department, 'kode_dep'),
			'department' => def($this->department, 'department'),
		];

		$details['m_grading_type'] = [
			'grading_type' => def($this->gradingType, 'grading_type'),
			'teamwork_name' => def($this->gradingType, 'teamwork_name'),
		];

		$details['m_designation'] = [
			'designation' => def($this->designation, 'designation'),
			'combine_label' => def($this->designation, 'combine_label'),
		];

		$model_atasan = $this->atasan;
		$detail_atasan = def($model_atasan, 'userDetail');

		if ($detail_atasan) {
			$details['atasan'] = [
				'user_id' => $detail_atasan->user_id,
				'nik' => $detail_atasan->nik,
				'nama_lengkap' => $detail_atasan->mergeFullName(),
			];
		}

		return $details;
	}

}
/* End of file modelName.php */
/* Location: ./application/models/modelName.php */