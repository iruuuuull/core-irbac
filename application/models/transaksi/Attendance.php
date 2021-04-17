<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attendance extends MY_Model {

	public $tableName = 'tbl_attendance';
	public $tableCalender = 'tbl_calendar';
	public $soft_delete = false;
	public $blameable = true;
	public $timestamps = true;

	const KET_HADIR = 'H';
	const KET_SAKIT = 'S';
	const KET_IZIN = 'I';
	const KET_TIDAK_HADIR = 'TH';
	const KET_CUTI = 'CT';

	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			'transaksi/calendar',
			'transaksi/cuti',
		]);
	}

	public function blameableBehavior()
    {
        return [
            'createdByAttribute' => 'created_by',
            'updatedByAttribute' => 'updated_by',
            'value' => $this->session->userdata('identity')->id
        ];
    }

    public function calendar()
    {
    	return $this->hasOne('calendar', 'id', 'calendar_id');
    }

    public function	Dataget($params){

		$query = $this->db->select([
			'tbl_calendar.id',
			'tbl_calendar.date',
			'tbl_attendance.user_id',
			'tbl_attendance.check_in',
			'tbl_attendance.location_in',
			'tbl_attendance.check_out',
			'tbl_attendance.daily_hours',
			'tbl_attendance.keterangan',
		])
		->join('tbl_attendance', 'tbl_attendance.calendar_id = tbl_calendar.id','left')
		->where($params)
		->get($this->tableCalender);

		return $query->row();
 	}

 	public function CountAttendance($params = ''){
 		$query = $this->db->select("Count('keterangan') as Hadir")			
		->join('tbl_attendance', 'tbl_attendance.calendar_id = tbl_calendar.id','left')
		->where($params)
		->get($this->tableCalender);

 		return $query->row_array();;
 	}

 	public function getKaryawan($query,$params)
	{
		$this->db->select([
			'tbl_user_detail.user_id',
			'tbl_user_detail.nama_depan',
			'tbl_user_detail.nama_tengah',
			'tbl_user_detail.nama_belakang',
		]);

		$this->db->where($params);
		$this->db->like('nama_depan', $query);
		$data = $this->db->get('tbl_user_detail');

		return $data->result_array();
	}

	public function setCuti($cuti_id)
	{
		$model = $this->cuti->findOne($cuti_id);

		if ($model) {
			$range = getWorkDatesFromRange($model->tanggal_mulai, $model->tanggal_akhir);

			$count_save = 0;
			foreach ($range as $key => $value) {
				$model_calendar = new Calendar;

				$calendar_id = $model_calendar->getTodaysKey($value);
				$user_id = $model->user_id;

				$model_attendance = new Attendance;
				$model_attendance->calendar_id = $calendar_id;
				$model_attendance->user_id = $user_id;
				$model_attendance->keterangan = self::KET_CUTI;

				if ($model_attendance->save()) {
					$count_save++;
				}
			}

			if ($count_save == count($range)) {
				return true;
			} else {
				return false;
			}
		}

		return false;
	}

	public function unsetCuti($cuti_id)
	{
		$model = $this->cuti->findOne($cuti_id);
		$model_pembatalan = $model->pembatalanCuti;

		if ($model && $model_pembatalan) {
			$range = getWorkDatesFromRange($model_pembatalan->mulai_batal, $model_pembatalan->akhir_batal);

			$count_save = 0;
			foreach ($range as $key => $value) {
				$model_calendar = new Calendar;

				$calendar_id = $model_calendar->getTodaysKey($value);
				$attendance = (new Attendance)->findOne([
					'calendar_id' => $calendar_id,
					'user_id' => $model->user_id
				]);

				if ($attendance && $attendance->delete($attendance->id)) {
					$count_save++;
				}
			}

			if ($count_save == count($range)) {
				return true;
			} else {
				return false;
			}
		}

		return false;
	}

	public function getListKeterangan()
	{
		return [
			self::KET_HADIR => 'Hadir',
			self::KET_SAKIT => 'Sakit',
			self::KET_IZIN => 'Izin',
			self::KET_TIDAK_HADIR => 'Tidak Hadir',
			self::KET_CUTI => 'Cuti',
		];
	}

	public function getKeteranganValue($ket = null)
	{
		if ($this->keterangan && empty($ket)) {
			$ket = $this->keterangan;
		}

		$keterangans = $this->getListKeterangan();

		return def($keterangans, $ket, '-');
	}

}

/* End of file Attendance.php */
/* Location: ./application/models/transaksi/Attendance.php */