<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FormCutiBersama extends MY_BaseModel {

	public $tanggal_mulai;
	public $tanggal_akhir;
	public $note;
	public $cuti_type;
	public $attachment;

	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			'transaksi/userdetail',
			'transaksi/cuti',
			'transaksi/attendance',
			'transaksi/calendar',
			'master/user',
			'notifikasi',
		]);
	}

	public function rules()
	{
		return [
            [
                'field' => 'tanggal_mulai',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tanggal Mulai wajib diisi.',
                ],
            ],
            [
                'field' => 'tanggal_akhir',
                'rules' => 'required|callback_compareDate',
                'errors' => [
                    'required' => 'Tanggal Akhir wajib diisi.',
                ],
            ],
            [
                'field' => 'cuti_type',
                'rules' => 'required|callback_typeInArray',
                'errors' => [
                    'required' => 'Tipe Cuti wajib diisi.',
                ],
            ],
        ];
	}

	public function compareDate() {
		$tanggal_mulai = strtotime($this->tanggal_mulai);
		$tanggal_akhir = strtotime($this->tanggal_akhir);

		if ($tanggal_akhir < $tanggal_mulai) {
			$this->form_validation->set_message('compareDate', 'Tanggal Akhir harus lebih besar sama dengan dari Tanggal Mulai.');
			return false;
		}

		return true;
	}

	public function typeInArray($input)
	{
		$type = (new Tipecuti)->findOne($input);

		if (empty($type)) {
			$this->form_validation->set_message('typeInArray', 'Tipe Cuti tidak ada di dalam database.');
			return false;
		}

		return true;
	}

	public function save()
	{
		$model_cuti = new Cuti;
		$model_detail = new Userdetail;
		$model_attendance = new Attendance;
		$employees = (new User)->findAll(['status' => User::AKUN_AKTIF]);

		$data_cuti = [];
		$data_detail = [];
		$data_absen = [];

		foreach ($employees as $key => $employee) {

			if (empty($employee->userDetail)) continue;

			$user_detail = $employee->userDetail;
			$hr = $user_detail->getHr();

			$data_cuti[] = [
				'user_id' => $employee->id,
				'tanggal_mulai' => date('Y-m-d', strtotime($this->tanggal_mulai)),
				'tanggal_akhir' => date('Y-m-d', strtotime($this->tanggal_akhir)),
				'note' => $this->note,
				'cuti_type' => $this->cuti_type,
				'type_taken' => $this->cuti_type,
				'status' => Cuti::STATUS_SETUJU,
				'attachment' => $this->attachment,
				'created_at' => date('YmdHis'),
				'hr' => def($hr, 'user_id'),
			];

			// Pengurangan Jumlah Cuti
			$amount = diffWorkDay($this->tanggal_mulai, $this->tanggal_akhir);
			$user_detail->last_leave -= $amount;
    		
    		if ($user_detail->last_leave < 0) {
    			$user_detail->current_leave += $user_detail->last_leave;
    			$user_detail->last_leave = 0;
    		}
			// End Pengurangan Jumlah Cuti

			$data_detail[] = [
				'user_id' => $employee->id,
				'last_leave' => $user_detail->last_leave,
				'current_leave' => $user_detail->current_leave,
			];

			$range = getWorkDatesFromRange($this->tanggal_mulai, $this->tanggal_akhir);

			foreach ($range as $key => $value) {
				$model_calendar = new Calendar;

				$calendar_id = $model_calendar->getTodaysKey($value);

				$data_absen[] = [
					'calendar_id' => $calendar_id,
					'user_id' => $employee->id,
					'keterangan' => Attendance::KET_CUTI,
					'created_at' => date('YmdHis'),
				];
			}

		}

		$save_attendance = $model_attendance->db->insert_batch(
			$model_attendance->tableName,
			$data_absen
		);
		if (!$save_attendance) {
			throw new CustomException("Proses penambahan data absen cuti gagal");
		}

		$save_detail = $model_detail->db->update_batch(
			$model_detail->tableName,
			$data_detail,
			'user_id'
		);
		if (!$save_detail) {
			throw new CustomException("Proses pengurangan jumlah cuti gagal");
		}

		$save_cuti = $model_cuti->db->insert_batch(
			$model_cuti->tableName,
			$data_cuti
		);
		if (!$save_cuti) {
			throw new CustomException("Proses simpan data cuti gagal");
		}

		return true;
	}
}
