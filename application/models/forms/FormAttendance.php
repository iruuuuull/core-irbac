<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FormAttendance extends MY_BaseModel {

	public $type;
	public $location;
	public $catatan;
	public $coordinate;
	public $date;

	public $user_id;

	private $_calendar_id;
	private $_attendance;

	public $data_absen;
	public $attended;

	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			'transaksi/calendar',
			'transaksi/attendance',
		]);
	}

	public function rules()
	{
		return [
            [
                'field' => 'type',
                'rules' => 'required|callback_checkType',
                'errors' => [
                    'required' => 'Tipe absen wajib diisi.',
                ],
            ],
            [
                'field' => 'location',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Alamat lengkap absen tidak boleh kosong.',
                ],
            ],
            [
                'field' => 'coordinate',
                'rules' => 'callback_checkCoordinate',
            ],
            [
                'field' => 'date',
                'rules' => 'required|callback_checkDatetime',
                'errors' => [
                    'required' => 'Waktu absen tidak terbaca.',
                ],
            ],
        ];
	}

	public function checkType()
	{
		if (!in_array($this->type, ['in', 'out'])) {
			$this->form_validation->set_message('checkType', 'Tipe absen hanya bisa check-in dan check-out.');
			return false;
		}

		return true;
	}

	public function checkCoordinate()
	{
		if (!is_array($this->coordinate)) {
			$this->form_validation->set_message('checkCoordinate', 'Koordinat harus memiliki data longitude dan latitude.');
			return false;

		} if ($this->coordinate['lat'] < -90 || $this->coordinate['lat'] > 90) {
			$this->form_validation->set_message('checkCoordinate', 'Koordinat latitude harus diantara angka -90 dan 90 derajat.');
			return false;

        } else if ($this->coordinate['long'] < -180 || $this->coordinate['long'] > 180) {
            $this->form_validation->set_message('checkCoordinate', 'Koordinat latitude harus diantara angka -180 dan 180 derajat.');
			return false;

        } elseif ($this->coordinate['lat'] == "" || $this->coordinate['long'] == "") {
            $this->form_validation->set_message('checkCoordinate', 'Masukkan koordinat latitude dan longitude dengan benar.');
			return false;

        }

        return true;
	}

	public function checkDatetime()
	{
		if (strtotime($this->date)) {
			return true;
		}

		$this->form_validation->set_message('checkDatetime', 'Format waktu/tanggal tidak sesuai (Y-m-d H:i:s).');
		return false;
	}

	public function setCalendarId()
	{
		$this->_calendar_id = (new Calendar)->getTodaysKey($this->date);
		$this->_attendance = (new Attendance)->findOne(['calendar_id' => $this->_calendar_id]);
	}

	public function save()
	{
		$this->coordinate = json_encode($this->coordinate);
		$this->setCalendarId();

		if (empty($this->_attendance)) {
			$this->_attendance = new Attendance;
		}

		$this->_attendance->calendar_id = $this->_calendar_id;
		$this->_attendance->user_id = $this->user_id;
		$this->_attendance->{'check_' . $this->type} = date('H:i', strtotime($this->date));
		$this->_attendance->{'location_' . $this->type} = $this->location;
		$this->_attendance->{'catatan_' . $this->type} = $this->catatan;
		$this->_attendance->{'coordinate_' . $this->type} = $this->coordinate;

		if (
			$this->_attendance->check_in
			&& $this->_attendance->check_out
		) {
			$this->_attendance->daily_hours = $this->helpers->diffHours(
				$this->_attendance->check_in, 
				$this->_attendance->check_out
			);
		}

		if ($this->_attendance->save()) {
			$this->data_absen = $this->_attendance->getAttributes();
			$this->attended = [
				'checked_in' => !empty($this->data_absen->chek_in),
				'checked_out' => !empty($this->data_absen->chek_out),
			];

			return true;
		}

		return false;
	}

}

/* End of file FormAttendance.php */
/* Location: ./application/models/forms/FormAttendance.php */