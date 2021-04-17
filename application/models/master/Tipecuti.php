<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tipecuti extends MY_Model {

	public $tableName = 'tbl_m_cuti_type';
	public $datatable_columns = ['name', 'desc', 'rule', 'range_day', 'is_limited'];
	public $datatable_search = ['name', 'desc', 'rule'];
    public $blameable = true;
    public $timestamps = true;
    const DELETED_AT = 'deleted_at';

    const VERIF_ATASAN = 'atasan';
    const VERIF_HEAD = 'head';
    const VERIF_HRD = 'hrd';
    const VERIF_CEO = 'ceo';

    const ANNUAL_LEAVE = 1;

    public function __construct()
    {
    	parent::__construct();
    	$this->load->model([
    	]);
    }

    public function rules()
    {
        return [
            [
                'field' => 'range_day',
                'rules' => 'numeric',
                'errors' => [
                    'required' => 'Jumlah Hari Cuti harus berupa angka',
                ],
            ],
        ];
    }

    public function getListType($dropdown = false, $parent_only = false)
    {
        if ($parent_only === true) {
            $types = $this->getAll(['parent' => 0]);
        } else {
            $types = $this->getAll();
        }

        if ($dropdown) {
            $list_type = ['' => '- Pilih Tipe Cuti -'];
        } else {
            $list_type = [];
        }

        foreach ($types as $key => $type) {
            if ($dropdown === false) {
                $list_type[$type->id] = [
                    'name' => $type->name,
                    'is_limited' => $type->is_limited,
                    'range_day' => $type->range_day,
                ];
            } else {
                $list_type[$type->id] = $type->name;
            }
        }

        return $list_type;
    }

    public function getListTypeValue($id = null)
    {
        $types = $this->getListType();
        $id = $this->id ?? $id;

        return !empty($types[$id]) ? $types[$id]['name'] : null;
    }

    public function getChildListType($dropdown = false)
    {
        $types = $this->findAll(['parent' => $this->id]);

        if ($dropdown) {
            $list_type = ['' => '- Pilih Tipe Diambil -'];
        } else {
            $list_type = [];
        }

        if ($types) {
            foreach ($types as $key => $type) {
                $subs = $type->getChildListType();

                if (!isset($subs[$type->id])) {
                    foreach ($subs as $index => $sub) {

                        if ($dropdown === false) {
                            $list_type[$type->name][$index] = [
                                'name' => $sub['name'],
                                'is_limited' => $sub['is_limited'],
                                'range_day' => $sub['range_day'],
                            ];
                        } else {
                            $list_type[$type->name][$index] = $sub;
                        }
                    }
                } else {
                    if ($dropdown === false) {
                        $list_type[$type->id] = [
                            'name' => $type->name,
                            'is_limited' => $type->is_limited,
                            'range_day' => $type->range_day,
                        ];
                    } else {
                        $list_type[$type->id] = $type->name;
                    }
                }

            }
        } else {
            if ($dropdown === false) {
                $list_type[$this->id] = [
                    'name' => $this->name,
                    'is_limited' => $this->is_limited,
                    'range_day' => $this->range_day,
                ];
            } else {
                $list_type[$this->id] = $this->name;
            }
        }

        return $list_type;
    }

    public function blameableBehavior()
    {
        return [
            'createdByAttribute' => 'created_by',
            'updatedByAttribute' => 'updated_by',
            'value' => $this->session->userdata('identity')->id
        ];
    }

    public function getListVerifikator()
    {
        return [
            self::VERIF_ATASAN => 'Direct Report',
            self::VERIF_HEAD => 'Atasan Direct Report',
            self::VERIF_HRD => 'HRD/Admin',
            self::VERIF_CEO => 'Direktur Utama',
        ];
    }

}

/* End of file Tipecuti.php */
/* Location: ./application/models/master/Tipecuti.php */