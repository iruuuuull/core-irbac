<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Grade extends MY_Model {

	public $tableName = 'tbl_m_grade';

	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			'master/jabatan',
		]);
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

}

/* End of file Grade.php */
/* Location: ./application/models/master/Grade.php */