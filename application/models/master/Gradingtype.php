<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gradingtype extends MY_Model {

	public $tableName = 'tbl_grading_type';
	public $soft_delete = false;

	const JENIS_STRUKTURAL = 1;
	const JENIS_FUNGSIONAL = 2;

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
			$list_grade[$grade->id] = $grade->teamwork_name;
		}

		return $list_grade;
	}

	public static function listGrading($dropdown = false)
	{
		$lists = [];

		if ($dropdown === true) {
			$lists[''] = '- Pilih Jenis -';
		}

		$lists = $lists + [
			self::JENIS_STRUKTURAL => 'Struktural',
			self::JENIS_FUNGSIONAL => 'Fungsional',
		];

		return $lists;
	}

	public function listGradingType($type = '')
	{
		$lists = [];

		if ($type) {
			$grades = $this->findAll(['grading_type' => $type]);
		} else {
			$grades = $this->findAll();
		}

		foreach ($grades as $key => $value) {
			$lists[$value->id] = $value->teamwork_name;
		}

		return $lists;
	}

	public function jabatan()
	{
	    return $this->hasOne('jabatan', 'id', 'jabatan_id');
	}

}

/* End of file Gradingtype.php */
/* Location: ./application/models/master/Gradingtype.php */