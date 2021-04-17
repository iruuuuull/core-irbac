<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class MyAttendance extends CI_Model {

	public $tableAttendance = 'tbl_attendance';
	public $tableCalender = 'tbl_calendar';

	public function	Dataget(){

		$query = $this->db->select([
			'tbl_calendar.date',
			'tbl_attendance.check_in',
			'tbl_attendance.location_in',
			'tbl_attendance.check_out',
			'tbl_attendance.daily_hours',
			'tbl_attendance.keterangan',
		])
		->join('tbl_calendar', 'tbl_calendar.id = tbl_attendance.calendar_id')
		->get($this->tableAttendance);

		return $query->result_array();
 	}

	public function	getData($columns='', $table = ''){
		if($columns != null){
			$this->db->select($columns);
		}else{
			$this->db->select('*');
		}
 		$query = $this->db->get($table);
 		return $query;
 	}
	public function FindAttendance($params = ''){							

 	 	$query = $this->db->select([
			'tbl_calendar.date',
			'tbl_calendar.is_holiday',
			'tbl_calendar.is_weekend',
			'tbl_attendance.id',
			'tbl_attendance.check_in',
			'tbl_attendance.location_in',
			'tbl_attendance.check_out',
			'tbl_attendance.location_out',
			'tbl_attendance.daily_hours',
			'tbl_attendance.keterangan',
		])
		->join('tbl_attendance', 'tbl_attendance.calendar_id = tbl_calendar.id','left')
		->where($params)
		->get($this->tableCalender);


 		return $query->result_array();
 	}

 	public function CountAttendance($params = ''){
 		$query = $this->db->select("Count('keterangan') as Hadir")			
		->join('tbl_attendance', 'tbl_attendance.calendar_id = tbl_calendar.id','left')
		->where($params)
		->get($this->tableCalender);

 		return $query->row_array();;
 	}



}
