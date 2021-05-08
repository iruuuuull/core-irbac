<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class UnitCampus extends MY_Model {

	public $tableName = 'unit_campus';
	public $datatable_columns = ['unit_id', 'unit_name', 'unit_parent_id', 'unit_level', 'unit_status', 'unit_kerjasama', 'unit_type'];
	public $datatable_search = ['unit_id', 'unit_name', 'unit_parent_id', 'unit_level', 'unit_status', 'unit_kerjasama', 'unit_type'];
    public $blameable = true;
    public $timestamps = true;
    public $soft_delete = false;

    const STATUS_NONAKTIF = 0;
    const STATUS_AKTIF = 1;

    public function rules()
    {
        return [
            [
                'field' => 'unit_id',
                'rules' => 'required|max_length[4]|is_unique[unit_campus.unit_id]',
                'errors' => [
                    'required' => 'ID Unit harus diisi',
                    'is_unique' => 'ID Unit sudah terdaftar',
                    'max_length' => 'ID Unit tidak boleh melebihi 4 karakter',
                ],
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
                'field' => 'unit_parent',
                'rules' => 'integer',
            ],
            [
                'field' => 'unit_level',
                'ruels' => 'integer'
            ],
            [
                'field' => 'unit_status',
                'ruels' => 'integer'
            ],
            [
                'field' => 'unit_kerjasama',
                'ruels' => 'integer'
            ],
            [
                'field' => 'unit_type',
                'ruels' => 'integer'
            ]
        ];
    }

    public static function getListLevel($dropdown = false)
    {
        $levels = [
            1 => 1,
            2 => 2,
            3 => 3,
        ];

        if ($dropdown === true) {
            $levels = array_merge(['' => '- Pilih Level -'], $levels);
        }

        return $levels;
    }

    public static function getListKerjasama($dropdown = false)
    {
        $teamwork = [
            1 => 1,
            3 => 3,
        ];

        if ($dropdown === true) {
            $teamwork = array_merge(['' => '- Pilih Kerjasama -'], $teamwork);
        }

        return $teamwork;
    }

    public static function getListType($dropdown = false)
    {
        $teamwork = [
            0 => 0,
            1 => 1,
            2 => 2,
        ];

        if ($dropdown === true) {
            $teamwork = array_merge(['' => '- Pilih Jenis -'], $teamwork);
        }

        return $teamwork;
    }

    public static function getListUnitParent($dropdown = false)
    {
        $parent = [
            1 => 1,
            2 => 2,
        ];

        if ($dropdown === true) {
            $parent = array_merge(['' => '- Pilih Parent -'], $parent);
        }

        return $parent;
    }

    public static function getListStatus($dropdown = false)
    {
        $statuses = [
            self::STATUS_NONAKTIF => 'Non-Aktif',
            self::STATUS_AKTIF => 'Aktif'
        ];

        if ($dropdown === true) {
            $statuses = array_merge(['' => '- Pilih Status -'], $statuses);
        }

        return $statuses;
    }

    public function getStatusValue($status = null)
    {
        $list_status = self::getListStatus();

        if ($this->unit_status !== null && $status === null) {
            $status = $this->unit_status;
        }

        return def($list_status, $status, '-');
    }

    public function getListParent($ignore_self = false, $dropdown = false)
    {
        $query = $this->find()->select(['id', 'unit_id', 'unit_name']);

        if (!empty($this->id) && $ignore_self === true) {
            $query->where(['id != ' => $this->id]);
        }

        $models = $this->queryAll($query);

        $parents = [];
        foreach ($models as $key => $model) {
            $parents[$model->unit_id] = "[{$model->unit_id}] - {$model->unit_name}";
        }

        if ($dropdown === true) {
            $parents = ['' => '- Pilih Parent ID -'] + $parents;
        }

        return $parents;
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