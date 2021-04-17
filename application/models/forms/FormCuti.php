<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FormCuti extends MY_BaseModel {

	public $user_id;
	public $tanggal_mulai;
	public $tanggal_akhir;
	public $note;
	public $cuti_type;
	public $type_taken;
	public $attachment;
	public $hr;

	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			'master/tipecuti',
			'transaksi/cuti',
			'transaksi/userdetail',
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
            [
                'field' => 'type_taken',
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
		$model = new Cuti;
		$current_request = $model->findAll([
			'user_id' => $this->user_id,
			'status' => [Cuti::STATUS_MENUNGGU, Cuti::STATUS_PROSES],
		]);

		if (empty($current_request)) {
			if ($cuti_id = $model->insert($this->getAttributes())) {
				$set_verificator = $this->cuti->setVerifikator($cuti_id);

				$message = 'Proses pengajuan cuti berhasil';
				if (!$set_verificator) {
					$user_hr = $this->userdetail->getHr($this->user_id);
					$message .= ', tetapi gagal mengirimkan notifikasi ke HRD. Silahkan informasikan secara manual.';

					$trigger_notif_hr = Notifikasi::sendNotif(
						$user_hr->user_id,
						"Pengajuan permohonan cuti {$this->session->userdata('detail_identity')->nik} gagal menentukan verifikator",
						site_url('/cuti/verifikasi'),
						'label-danger'
					);
				}

				return true;
			}

		} else {
			throw new CustomException("Permohonan cuti sebelumnya belum selesai");
			
		}

		return false;
	}

}

/* End of file FormCuti.php */
/* Location: ./application/models/forms/FormCuti.php */