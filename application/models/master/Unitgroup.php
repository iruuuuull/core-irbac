<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Unitgroup extends MY_Model {

	public $tableName = 'tbl_m_sbu';
	public $soft_delete = false;

	const HEAD_OFFICE = '01';
	const COLLEGE = '02';
	const POLITEKNIK = '03';
	const PTS = '04';

	public function getListGroupunit($dropdown = false)
	{
		$grades = $this->getAll();

		if ($dropdown) {
			$list_groupunit = ['' => '- Pilih Group Unit -'];
		} else {
			$list_groupunit = [];
		}

		foreach ($grades as $key => $Groupunit) {
			$list_groupunit[$Groupunit->id] = $Groupunit->sbu;
		}

		return $list_groupunit;
	}

	public function getListUnitManual()
	{
		return [
			self::HEAD_OFFICE => 'Direktorat',
			self::COLLEGE => 'College',
			self::POLITEKNIK => 'Politeknik',
			self::PTS => 'PTS',
		];
	}

	public function getUnit($id)
	{
		$lists = $this->getListUnitManual();

		return !empty($lists[$id]) ? $lists[$id] : '';
	}

}

/* End of file Grade.php */
/* Location: ./application/models/master/Grade.php */