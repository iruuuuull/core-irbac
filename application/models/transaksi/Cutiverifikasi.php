<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cutiverifikasi extends MY_Model {

	public $tableName = 'tbl_cuti_verifikasi';
	public $soft_delete = false;
	public $blameable = true;
	protected $timestamps = true;

    const STATUS_TOLAK = 0;
    const STATUS_TERIMA = 1;

    public function __construct()
    {
        parent::__construct();
        $this->load->model([
            'transaksi/cuti',
            'transaksi/attendance',
            'transaksi/userdetail',
        ]);
    }

    public function getListStatus()
    {
        return [
            self::STATUS_TOLAK => 'Tolak',
            self::STATUS_TERIMA => 'Terima'
        ];
    }

    public function getStatusValue($status)
    {
        $statuses = $this->getListStatus();

        return isset($statuses[$status]) ? $statuses[$status] : 'Belum Verifikasi';
    }

    public function cuti()
    {
        return $this->hasOne('cuti', 'id', 'cuti_id');
    }

    public function userDetail()
    {
        return $this->hasOne('userdetail', 'user_id', 'verifikator_id');
    }

	public function blameableBehavior()
    {
        return [
            'createdByAttribute' => 'created_by',
            'updatedByAttribute' => 'updated_by',
            'value' => $this->session->userdata('identity')->id
        ];
    }

    public function getCurrent($verifikators = [])
    {
        if ($verifikators) {
            foreach ($verifikators as $key => $verifikator) {
                if (empty($verifikator->status)) {
                    return $verifikator;
                }
            }
        }

        return [];
    }

    public function followUp($cuti_id)
    {
        $model_cuti = new Cuti;
        $model = $model_cuti->findOne($cuti_id);


        if ($model) {
            $verifikasis = $model->cutiVerifikasi;
            $terima = 0;
            $tolak = 0;

            foreach ($verifikasis as $key => $value) {
                if ($value->status >= 0 && $value->status != null) {
                    if ($value->status == self::STATUS_TERIMA) {
                        $terima++;
                    } elseif ($value->status == self::STATUS_TOLAK) {
                        $tolak++;
                    }
                }
            }

            if ($terima === count($verifikasis)) {
                $pengurangan = $model_cuti->decreaseQuota($cuti_id);
                $model->status = Cuti::STATUS_SETUJU;

                if ($pengurangan && $model->save()) {
                    $set_jadwal_cuti = $this->attendance->setCuti($cuti_id);

                    if ($set_jadwal_cuti) {
                        $send_notif = Notifikasi::sendNotif(
                            $model->user_id,
                            "Permohonan cuti anda diterima",
                            site_url('/cuti/permohonan')
                        );
                    } else {
                        $send_notif = Notifikasi::sendNotif(
                            $model->hr,
                            "Mohon lakukan follow-up ulang permohonan cuti {$model->user->userDetail->nik}",
                            site_url('/cuti/permohonan')
                        );
                    }

                } else {
                    $send_notif = Notifikasi::sendNotif(
                        $model->hr,
                        "Mohon lakukan follow-up permohonan cuti {$model->user->userDetail->nik}",
                        site_url('/cuti/permohonan')
                    );
                }

            } elseif ($tolak > 0) {
                $model->status = Cuti::STATUS_TOLAK;
                $model->save();
            }

            return true;
        }

        return false;
    }

}

/* End of file Cutiverifikasi.php */
/* Location: ./application/models/transaksi/Cutiverifikasi.php */