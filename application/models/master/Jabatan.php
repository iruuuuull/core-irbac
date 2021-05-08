<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Jabatan extends MY_Model {

	public $tableName = 'tbl_m_jabatan';
	public $datatable_columns = ['id', 'kode_jabatan', 'nama_jabatan'];
	public $datatable_search = ['kode_jabatan', 'nama_jabatan'];

	# KODE UNTUK RANGE TIAP JABATAN
	const RANGE_STAFF = '1-4';
	const RANGE_HEAD = '5';
	const RANGE_MANAGER = '6-12';

	public function getRange($code_range)
	{
		$codes = [$code_range];

		if (strpos($code_range, '-') !== false) {
			$code_explode = explode('-', $code_range);
			$codes = range($code_explode[0], $code_explode[1]);
		}

		return $codes;
	}

	public static function listGrade($dropdown = false)
	{
		$lists = [];

		if ($dropdown === true) {
			$lists[''] = '- Pilih Grade -';
		}

		$grades = (new self)->findAll();

		foreach ($grades as $key => $value) {
			$lists[$value->id] = $value->grade;
		}

		return $lists;
	}

	public function getKelasJabatan($grade)
	{
		$jabatan = $this->findOne($grade);
		$kelas_jabatan = '';

		if ($jabatan) {
			$kelas_jabatan = $jabatan->nama_jabatan .' '. $jabatan->kelas_jabatan;
		}

		return $kelas_jabatan;
	}

}
