<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Calendar extends MY_Model {

	public $tableName = 'tbl_calendar';
	public $soft_delete = false;
	public $blameable = true;
	const CREATED_AT = null;
	protected $timestamps = true;

	public $datatable_columns = ['id', 'date', 'event'];
	public $datatable_search = ['date', 'event'];

	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			'transaksi/attendance',
		]);
	}

	public function getTodaysKey($date = null)
	{
		if ($date) {
			$date = date('Y-m-d', strtotime($date));
		} else {
			$date = date('Y-m-d');
		}

		$model = $this->findOne(['DATE(date)' => $date]);

		$id = null;
		if ($model) {
			$id = $model->id;
		}

		return $id;
	}

	public function attendance()
	{
		return $this->hasOne('attendance', 'calendar_id', 'id');
	}

	public function attendances()
	{
		return $this->hasMany('attendance', 'calendar_id', 'id')->order_by('user_id', 'DESC');
	}

	public function attendanceByUser($id)
	{
		return $this->hasOne('attendance', 'calendar_id', 'id')->where(['user_id' => $id]);
	}

	public function blameableBehavior()
	{
		return [
			'createdByAttribute' => null,
			'updatedByAttribute' => 'updated_by',
			'value' => $this->session->userdata('identity')->id
		];
	}

	protected function _get_datatables_query()
	{
		$this->db->from($this->tableName);
		$this->db->select($this->datatable_columns);
		$this->db->where(['is_holiday' => '1']);

		$i = 0;
		foreach ($this->datatable_search as $item) {
			# jika datatable mengirimkan pencarian dengan metode POST
			if($_POST['search']['value']) {

				if ($i === 0) {
					$this->db->group_start(); 
					$this->db->like($item, $_POST['search']['value']);

				} else {
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if (count($this->datatable_search) - 1 == $i) {
					$this->db->group_end(); 
				}
			}

			$i++;
		}

		if(isset($_POST['order'])) {
			$this->db->order_by($this->datatable_columns[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);

		} elseif(isset($this->datatable_order)) {
			$order = $this->datatable_order;
			$this->db->order_by(key($order), $order[key($order)]);

		}
	}

	public function getYears()
	{
		$years = $this->find()
			->select('YEAR(date) AS year')
			->order_by('date')
			->group_by('YEAR(date)')
			->get()->result();

		$list_year = [];
		if ($years) {
			foreach ($years as $key => $year) {
				$list_year[] = $year->year;
			}
		}

		return $list_year;
	}

	public function attendance_employee($params)
	{
		$query = $this->db->select([
			  'tbl_attendance.id' ,
			  'tbl_attendance.check_in', 
			  'tbl_attendance.location_in',
			  'tbl_attendance.check_out',
			  'tbl_attendance.location_out',
			  'tbl_attendance.daily_hours',
			  'tbl_attendance.keterangan', 
			  'tbl_user_detail.nama_depan',
			  'tbl_user_detail.nama_tengah',
			  'tbl_user_detail.nama_belakang'
		])
		 ->join('tbl_user_detail', 'tbl_user_detail.user_id = tbl_attendance.user_id','left')
		 ->where($params)
		 ->get('tbl_attendance');

		 return $query->result_array();
	 }

}

/* End of file Calendar.php */
/* Location: ./application/models/transaksi/Calendar.php */