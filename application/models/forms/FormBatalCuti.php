<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FormBatalCuti extends MY_BaseModel {

	public $start_cancel;
	public $end_cancel;
	public $note;
	public $agreement;
	public $approval;

	public $data_cuti;
	public $method = 'insert';

	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			'transaksi/cutipembatalan',
			'transaksi/userdetail',
			'transaksi/cuti',
			'transaksi/attendance',
			'notifikasi',
		]);
	}

	public function setData(Cutipembatalan $model)
	{
		if ($model) {
			$this->start_cancel = $model->mulai_batal;
			$this->end_cancel = $model->akhir_batal;
			$this->note = $model->note;
			$this->agreement = 1;

			$this->method = 'update';
		}
	}

	public function rules()
	{
		if ($this->method === 'insert') {
			$rules = [
	            [
	                'field' => 'start_cancel',
	                'rules' => 'required|callback_checkStart',
	                'errors' => [
	                    'required' => 'Tanggal Mulai wajib diisi.',
	                ],
	            ],
	            [
	                'field' => 'end_cancel',
	                'rules' => 'required|callback_compareDate|callback_checkEnd',
	                'errors' => [
	                    'required' => 'Tanggal Akhir wajib diisi.',
	                ],
	            ],
	            [
	                'field' => 'agreement',
	                'rules' => 'required',
	                'errors' => [
	                    'required' => 'Mohon centang persetujuan untuk menandakan anda setuju.',
	                ],
	            ],
	        ];
		} elseif ($this->method === 'update') {
			$rules = [
	            [
	                'field' => 'approval',
	                'rules' => 'required',
	                'errors' => [
	                    'required' => 'Mohon centang persetujuan untuk menandakan anda setuju.',
	                ],
	            ],
	        ];
		} else {
			$rules = [];
		}

		return $rules;

	}

	public function compareDate() {
		$tanggal_mulai = strtotime($this->start_cancel);
		$tanggal_akhir = strtotime($this->end_cancel);

		if ($tanggal_akhir < $tanggal_mulai) {
			$this->form_validation->set_message('compareDate', 'Tanggal Akhir harus lebih besar sama dengan dari Tanggal Mulai.');
			return false;
		}

		return true;
	}

	public function checkStart()
	{
		$mulai_batal = strtotime($this->start_cancel);
		$tanggal_mulai = strtotime($this->data_cuti->tanggal_mulai);

		if ($mulai_batal < $tanggal_mulai) {
			$this->form_validation->set_message('checkStart', 'Tanggal mulai pembatalan tidak boleh kurang dari tanggal mulai cuti.');
			return false;
		} elseif ($mulai_batal < time()) {
			$this->form_validation->set_message('checkStart', 'Tanggal mulai pembatalan tidak boleh kurang dari tanggal hari ini.');
			return false;
		}

		return true;
	}

	public function checkEnd()
	{
		$akhir_batal = strtotime($this->end_cancel);
		$tanggal_akhir = strtotime($this->data_cuti->tanggal_akhir);

		if ($akhir_batal > $tanggal_akhir) {
			$this->form_validation->set_message('checkEnd', 'Tanggal akhir pembatalan tidak boleh lebih dari tanggal akhir cuti.');
			return false;
		} elseif ($akhir_batal < time()) {
			$this->form_validation->set_message('checkEnd', 'Tanggal akhir pembatalan tidak boleh kurang dari tanggal hari ini.');
			return false;
		}

		return true;
	}

	public function save()
	{
		$model = new Cutipembatalan;

		$model->cuti_id = $this->data_cuti->id;
		$model->mulai_batal = date('Y-m-d', strtotime($this->start_cancel));
		$model->akhir_batal = date('Y-m-d', strtotime($this->end_cancel));
		$model->note = $this->note;

		return $model->save();
	}

	public function approving()
	{
		if ($this->method === 'update') {
			# Set Pembatalan cuti selesai
			$model = new Cutipembatalan;

			$model->status_proses = 2;
			$model->approved_at = date('Y-m-d H:i:s');
			# End Set Pembatalan cuti selesai
			
			# Set cuti dibatalkan
			$this->data_cuti->is_cancel = 1;
			$this->data_cuti->note_cancel = $model->note;
			$this->data_cuti->status = Cuti::STATUS_BATAL;
			# End Set cuti dibatalkan

			if (!$this->attendance->unsetCuti($this->data_cuti->id)) {
				throw new CustomException("Proses penghapusan absen cuti");
			}

			if (!$this->data_cuti->increaseQuota($this->data_cuti->id)) {
				throw new CustomException("Proses pengembalian jumlah cuti gagal");
			}

			if (!$this->data_cuti->save()) {
				throw new CustomException("Proses simpan ubah status cuti gagal");
			}

			if (!$model->save()) {
				throw new CustomException("Proses simpan ubah status cuti gagal");
			}

			return true;
		}

		return false;
	}
}
