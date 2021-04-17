<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Subgrade extends MY_Model {

	public $tableName = 'tbl_sub_grade';

	public function __construct()
	{
		parent::__construct();
	}

	public function getListGrade($dropdown = false)
	{
		$grades = $this->findAll();

		if ($dropdown) {
			$list_grade = ['' => '- Pilih Grade -'];
		} else {
			$list_grade = [];
		}

		foreach ($grades as $key => $grade) {
			$list_grade[$grade->id] = "[Level {$grade->level}] - " . $grade->jabatan->nama_jabatan;
		}

		return $list_grade;
	}

	public function jabatan()
	{
	    return $this->hasOne('jabatan', 'id', 'jabatan_id');
	}

	public static function listGolongan($grade = null, $dropdown = false)
	{
		$lists = [];

		if ($dropdown === true) {
			$lists[''] = '- Pilih Golongan -';
		}

		$golongan = [
			'A' => 'A',
			'B' => 'B',
			'C' => 'C',
			'D' => 'D',
		];

		if ($grade !== null && is_numeric($grade)) {
			$golongans = (new self)->find()
							->select('sub_grade')
							->where(['m_jabatan_id' => $grade])
							->get()->result();

			$new_golongan = [];
			foreach ($golongans as $key => $value) {
				$new_golongan[$value->sub_grade] = $value->sub_grade;
			}

			$golongan = $new_golongan;
		}

		$lists = array_merge($lists, $golongan);

		return $lists;
	}

	public function getKelasJabatan($grade, $golongan)
	{
		$grade = $this->findOne(['m_jabatan_id' => $grade, 'sub_grade' => $golongan]);
		$kelas_jabatan = '';

		if ($grade) {
			$kelas_jabatan = $grade->fungsional_dosen;
		}

		return $kelas_jabatan;
	}

}

/* End of file Subgrade.php */
/* Location: ./application/models/master/Subgrade.php */