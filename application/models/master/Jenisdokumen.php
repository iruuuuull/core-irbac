<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Jenisdokumen extends MY_Model {

	public $tableName = 'tbl_m_jenis_dokumen';
	public $datatable_columns = ['id', 'kode_dokumen', 'label', 'desc'];
	public $datatable_search = ['kode_dokumen', 'label'];

	public function getIdByKode($kode)
	{
		$model = $this->findOne(['kode_dokumen' => $kode]);

		return def($model, 'id');
	}

}
