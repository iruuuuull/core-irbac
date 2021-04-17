<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AttendanceController extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			'transaksi/calendar',
			'transaksi/userdetail',
			'transaksi/attendance',
		]);
	}

	public function actionIndex()
	{

		$this->layout->view_js = ['_partial/js_map_detail', '_partial/sub_js'];
		$this->layout->view_css = '_partial/sub_css';
		$this->layout->title = 'Attendance Report';

		$this->layout->render('index', [
		]);
	}

	public function getData(){
		$search = $this->input->get('search');
		$UnitSesi = $this->session->userdata('detail_identity')->unit_id;
		$query = $this->attendance->getKaryawan($search,array('tbl_user_detail.unit_id' => $UnitSesi));

		echo json_encode($query);
	}

	public function actionGetData(){
		$input = $this->input->post('cari');
		$values = (empty($input)) ? NULL : $input;


		$calendars = $this->calendar->findAll(['date >= ' =>  date("Y-m-d", strtotime($values["tanggal_awal"])),'date <= ' => date('Y-m-d', strtotime($values['tanggal_akhir'])) ]);
		if($values['id_karyawan'] == 'All'){

			$view = '<div class="data-attendance">';
			$view .='<div class="table-responsive"><table class="table table-bordered" id="table_report_atten">
			<thead>
			  <tr style="background-color:#031727;color:white;">
			   <th>No</th>
			   <th>Employee Name</th>
			   <th>Date</th>
			   <th>Check In</th>
			   <th>Location In</th>
			   <th>Check Out</th>
			   <th>Location Out</th>
			   <th>Daily Hours</th>
			   <th>Desc</th>
			  </tr>
			</thead><tbody>';
			$no=1;
			foreach ($calendars as $key => $model){
				$data_employee = $this->calendar->attendance_employee(array('calendar_id'=> $model->id , 'unit_id' => $this->session->userdata('detail_identity')->unit_id));		
				foreach ($data_employee as $key => $value) {
				$name = $value['nama_depan'].' '.$value['nama_tengah'].' '.$value['nama_belakang'];
				$getKet = Cek_Keterangan($value['keterangan']);

				$view .= '<tr ' . Cek_Weekend(def($model, 'is_weekend'), def($model, 'is_holiday')) . '>
				<td width="30" align="center">'. $no++ .'</td>
				<td width="250">'. $name .'</td>
				<td width="210">  '.  date('l,d F Y', strtotime($model->date)) .'</td>
				<td width="80" align="center"><a href="#" class="btn-detail-in" data-id="'. $value['id'] .'">'. $value['check_in'] .'</a></td>
				<td>'.$value['location_in'].'</td>
				<td width="90" align="center" ><a href="#" class="btn-detail-out" data-id="'. $value['id'] .'">'. $value['check_out'] .'</a></td>
				<td>'. $value['location_out'] .'</td>
				<td width="100" align="center">'. $value['daily_hours'] .'</td>
				<td><span '. $getKet['class'].' ><b> '.  $getKet['keterangan'] .'<b></span></td>
				</tr>';
				}
			}   
			$view .= '</tbody></table></div></div>';

		}else{
			$user = $this->user->findOne($values['id_karyawan']);
			$user_detail = $user->userDetail;
			$name = $user_detail->nama_depan.' '.$user_detail->nama_tengah.' '.$user_detail->nama_belakang;

			$view = '<div class="data-attendance">';

			$view .='<div class="row" style="margin-bottom: 30px; ">'; 
			$view .='<div class="col-sm-2">';
			if (!empty($user_detail->profile_pic)){
				$view .= '<img src="' .  base_url($user_detail->profile_pic) . '" id="img-profile" class="img-responsive img-circle" style="border: 5px solid; width: 200px;" />';
			}else{
				$view .= '<img src="' . base_url('/web/assets/pages/img/no_avatar.jpg') . '" id="img-profile" class="img-responsive img-circle" style="border: 5px solid; width: 200px;" />';
			}                      
			$view .='</div>';
			$view .='<div class="col-sm-2" style="margin-top: 40px;">';
			$view .='<h3><b>';
			if (!empty($user_detail->nama_depan)){
				$view .= '' . ucwords("{$user_detail->nama_depan} {$user_detail->nama_tengah} {$user_detail->nama_belakang}") . '';
			}else{ 
				$view .= '' . ucwords($this->session->userdata('identity')->username) . '';
			}
			$view .='</b></h3>';
			$view .='<h4>';
			if (!empty($user_detail->job_title)){ 
				$view .= '' . ucwords($user_detail->job_title) . '';
			}
			$view .='</h4>';
			$view .='</div>';
			$view .='</div><hr>';
			$view .='<div class="table-responsive"><table class="table table-bordered table-responsive"   id="table_report_atten">
			<div style="margin-top:70px;">
			</div>
			<thead>
			  <tr style="background-color:#031727;color:white;">
			   <th>No</th>
			   <th>Date</th>
			   <th>Check In</th>
			   <th>Location In</th>
			   <th>Check Out</th>
			   <th>Location Out</th>
			   <th>Daily Hours</th>
			   <th>Desc</th>
			  </tr>
			</thead><tbody>';
			foreach ($calendars as $key => $model){
				$attendee = $model->attendanceByUser($user->id)->get()->row();
				$getKet = Cek_Keterangan(def($attendee, 'keterangan'));

				$view .= '<tr ' . Cek_Weekend(def($model, 'is_weekend'), def($model, 'is_holiday')) . '>
				<td width="30" align="center">'. ($key + 1) .'</td>
				<td width="210">  '.  date('d F Y', strtotime($model->date)) .'</td>
				<td width="80" align="center"><a href="#" class="btn-detail-in" data-id="'. def($attendee, 'id') .'">'. def($attendee, 'check_in') .'</a></td>
				<td>'.def($attendee, 'location_in').'</td>
				<td width="90" align="center" ><a href="#" class="btn-detail-out" data-id="'. def($attendee, 'id') .'">'. def($attendee, 'check_out') .'</a></td>
				<td>'. def($attendee, 'location_out') .'</td>
				<td width="100" align="center">'. def($attendee, 'daily_hours') .'</td>
				<td><span '. $getKet['class'].' ><b> '.  $getKet['keterangan'] .'<b></span></td>
				</tr>';
			}          	        
			$view .= '</tbody></table></div></div>';


		}

		echo $view;
		
	}


}

/* End of file JabatanController.php */
/* Location: ./application/modules/report/controllers/AttendanceController.php */