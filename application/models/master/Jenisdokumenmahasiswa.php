<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Jenisdokumenmahasiswa extends MY_Model {

	public $tableName = 'm_jenis_dokumen_mahasiswa';
	public $datatable_columns = ['id', 'kode_dokumen', 'label'];
	public $datatable_search = ['kode_dokumen', 'label'];


}
