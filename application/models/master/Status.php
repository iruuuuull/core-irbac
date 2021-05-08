<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Status extends MY_Model {

	public $tableName = 'tbl_m_status_kerja';
	public $soft_delete = false;

	const PERMANENT = '01';
	const CONTRACT = '02';
	const PROB = '03';
	const OTHER = '04';
	const MITRA = '05';

	public function getListStatus($dropdown = false)
	{
		$statuses = $this->getAll();

		if ($dropdown) {
			$list_status = ['' => '- Pilih Status -'];
		} else {
			$list_status = [];
		}

		foreach ($statuses as $key => $status) {
			$list_status[$status->id] = $status->status;
		}

		return $list_status;
	}

	public function getListStatusManual()
	{
		return [
			self::PERMANENT => 'Permanent',
			self::CONTRACT => 'Contract',
			self::PROB => 'Probation',
			self::OTHER => 'Perusahaan Lain',
			self::MITRA => 'Mitra',
		];
	}

	public function getStatus($id)
	{
		$lists = $this->getListStatusManual();

		return !empty($lists[$id]) ? $lists[$id] : '';
	}

}

/* End of file Status.php */
/* Location: ./application/models/master/Status.php */