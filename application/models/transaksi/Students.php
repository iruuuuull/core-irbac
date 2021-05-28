<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Students extends MY_Model {

	public $tableName = 'students';
	public $datatable_columns = ['id', 'student_id', 'student_name', 'unit_id', 'unit_parent_id', 'student_ta', 'product_id','student_nim','student_photo','student_status'];
	public $datatable_search = ['id', 'student_id', 'student_name', 'unit_id', 'unit_parent_id', 'student_ta', 'product_id','student_nim','student_photo','student_status'];
    public $blameable = true;
    public $timestamps = true;
    public $soft_delete = false;

    const STATUS_AKTIF = 0;
    const STATUS_CUTI = 1;
    const STATUS_NON_AKTIF = 2;


    // public function rules()
    // {
    //     return [
    //         [
    //             'field' => 'student_nik',
    //             'rules' => 'required',
    //         ],
    //         [
    //             'field' => 'student_name',
    //             'rules' => 'required',
    //         ],
    //         [
    //             'field' => 'student_date_birth',
    //             'rules' => 'integer',
    //         ],
    //         [
    //             'field' => 'student_place_birth',
    //             'rules' => 'required'
    //         ],
    //         [
    //             'field' => 'student_sex',
    //             'rules' => 'required'
    //         ],
    //         [
    //             'field' => 'student_agama',
    //             'rules' => 'required'
    //         ],
    //         [
    //             'field' => 'student_email',
    //             'rules' => 'required'
    //         ],
    //         [
    //             'field' => 'student_agama',
    //             'rules' => 'required'
    //         ],
    //         [
    //             'field' => 'student_phone',
    //             'rules' => 'integer'
    //         ],
    //         [
    //             'field' => 'student_parent_email',
    //             'rules' => 'required'
    //         ],
    //         [
    //             'field' => 'student_parent_phone',
    //             'rules' => 'integer'
    //         ],
    //         [
    //             'field' => 'student_ta',
    //             'rules' => 'required'
    //         ],
    //         [
    //             'field' => 'student_alamat',
    //             'rules' => 'required'
    //         ],
    //         [
    //             'field' => 'student_provinsi',
    //             'rules' => 'required'
    //         ],
    //         [
    //             'field' => 'student_kabupaten',
    //             'rules' => 'required'
    //         ],
    //         [
    //             'field' => 'student_kecamatan',
    //             'rules' => 'required'
    //         ],
    //         [
    //             'field' => 'student_kota',
    //             'rules' => 'required'
    //         ],
    //          [
    //             'field' => 'student_kodepos',
    //             'rules' => 'required'
    //         ],
    //         [
    //             'field' => 'pa_id',
    //             'rules' => 'required'
    //         ],
    //         [
    //             'field' => 'student_status',
    //             'rules' => 'required'
    //         ],
    //         [
    //             'field' => 'student_nik_ayah',
    //             'rules' => 'required'
    //         ],
    //         [
    //             'field' => 'student_nama_ayah',
    //             'rules' => 'required'
    //         ],
    //          [
    //             'field' => 'student_pddkn_ayah',
    //             'rules' => 'required'
    //         ],
    //         [
    //             'field' => 'student_pekerjaan_ayah',
    //             'rules' => 'required'
    //         ],
    //         [
    //             'field' => 'student_penghasilan_ayah',
    //             'rules' => 'required'
    //         ],
    //           [
    //             'field' => 'student_nik_ibu',
    //             'rules' => 'required'
    //         ],
    //         [
    //             'field' => 'student_nama_ibu',
    //             'rules' => 'required'
    //         ],
    //          [
    //             'field' => 'student_pddkn_ibu',
    //             'rules' => 'required'
    //         ],
    //         [
    //             'field' => 'student_pekerjaan_ibu',
    //             'rules' => 'required'
    //         ],
    //         [
    //             'field' => 'student_penghasilan_ibu',
    //             'rules' => 'required'
    //         ],
    //         [
    //             'field' => 'student_tgl_lahir_ibu',
    //             'rules' => 'required'
    //         ],
    //         [
    //             'field' => 'student_tgl_lahir_ayah',
    //             'rules' => 'required'
    //         ]
    //     ];
    // }

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

    public function getLastId(){
        $this->db->select('student_id');
        $this->db->order_by('student_id','DESC');
        $this->db->limit(1);
        $query = $this->db->get('students');

        $row = $query->row();

        return $row;
    }

}