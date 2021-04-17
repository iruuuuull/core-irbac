<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AttendanceApi extends MY_BaseModel {

	public $nik;
	public $user_id;

	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			'transaksi/attendance',
			'transaksi/calendar',
		]);
	}

	public function search($params)
	{
		$model = new Calendar;
		$search = $model->setAlias('tc')->find()->select('id, date, is_weekend, is_holiday');
					// ->join('tbl_attendanc ta', 'tc.id = ta.calendar_id', 'left')
					// ->select('tc.date, ta.check_in, ta.location_in, ta.check_out, ta.location_out, ta.daily_hours, ta.keterangan');

		// if ($this->nik) {
		// 	$search->join('tbl_user_detail ud', 'ud.user_id = ta.user_id', 'left');
		// 	$search->or_where([
		// 		'ud.nik' => $this->nik,
		// 		'ud.nik !=' => $this->nik,
		// 	]);
		// }

		# Filter by month
		if ($params['month']) {
			$month = $params['month'];

			if (!is_numeric($month)) {
				$month = $this->helpers->getMonthNum($params['month']);
			}

			if (!empty($month)) {
				$search->where(['MONTH(tc.date)' => $month]);
			}

		} else {
			$search->where(['MONTH(tc.date)' => date('m')]);
		}

		# Filter by year
		if ($params['year'] && is_numeric($params['year'])) {
			$search->where(['YEAR(tc.date)' => $params['year']]);
		} else {
			$search->where(['YEAR(tc.date)' => date('Y')]);
		}

		# change query builder to custom ORM
		$calendars = $model->queryAll($search);
		$attendances = [];

		foreach ($calendars as $key => $calendar) {
			$attendee = $calendar->attendance()->join('tbl_user_detail ud', 'ud.user_id = tbl_attendance.user_id', 'left');

			if ($this->nik) {
				$attendee->where(['ud.nik' => $this->nik]);
			}

			if ($this->user_id) {
				$attendee->where(['ud.user_id' => $this->user_id]);
			}

			# change query builder to custom ORM
			$attendee = (new Attendance)->queryOne($attendee);

			$attendances[] = [
				'id' => def($attendee, 'id'),
				'date' => $calendar->date,
				'is_weekend' => $calendar->is_weekend,
				'is_holiday' => $calendar->is_holiday,
				'check_in' => def($attendee, 'check_in'),
				'location_in' => def($attendee, 'location_in'),
				'check_out' => def($attendee, 'check_out'),
				'location_out' => def($attendee, 'location_out'),
				'daily_hours' => def($attendee, 'daily_hours'),
				'keterangan' => $attendee ? $attendee->getKeteranganValue() : '-',
			];

		}

		return $attendances;
	}

}

/* End of file AttendanceApi.php */
/* Location: ./application/models/search/AttendanceApi.php */