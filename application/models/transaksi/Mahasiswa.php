<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mahasiswa extends MY_Model {

	public $tableName = 'students';
	public $datatable_columns = ['id', 'student_name', 'unit_id', 'student_ta', 'product_id','student_nim'];
	public $datatable_search = ['id', 'student_name', 'unit_id', 'student_ta', 'product_id','student_nim'];
    public $blameable = true;
    public $timestamps = true;
    public $soft_delete = false;

    const STATUS_AKTIF = 0;
    const STATUS_CUTI = 1;
    const STATUS_NON_AKTIF = 2;


    public function rules()
    {
        return [
            [
                'field' => 'unit_id',
                'rules' => 'required',
            ],
            [
                'field' => 'unit_name',
                'rules' => 'required',
            ],
            [
                'field' => 'unit_parent',
                'rules' => 'integer',
            ],
            [
                'field' => 'unit_level',
                'rules' => 'integer'
            ],
            [
                'field' => 'unit_status',
                'rules' => 'integer'
            ],
            [
                'field' => 'unit_kerjasama',
                'rules' => 'integer'
            ],
            [
                'field' => 'unit_type',
                'rules' => 'integer'
            ]
        ];
    }

    public static function getListStatusMahasiswa($dropdown = false)
    {
        $statuses = [
            self::STATUS_AKTIF => 'Aktif',
            self::STATUS_CUTI => 'Cuti',
            self::STATUS_NON_AKTIF => 'Non-Aktif',
        ];

        if ($dropdown === true) {
            $statuses = array_merge(['' => '- Pilih Status Mahasiswa -'], $statuses);
        }

        return $statuses;
    }

    public function getStatusValue($status = null)
    {
        $list_status = self::getListStatusMahasiswa();

        if ($this->student_status !== null && $status === null) {
            $status = $this->student_status;
        }

        return def($list_status, $status, '-');
    }

    public function blameableBehavior()
    {
        return [
            'createdByAttribute' => 'created_by',
            'updatedByAttribute' => 'updated_by',
            'value' => def($this->session->userdata('identity'), 'id')
        ];
    }

}