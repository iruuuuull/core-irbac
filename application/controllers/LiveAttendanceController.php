<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LiveAttendanceController extends CI_Controller {

	public $accept_check = ['in', 'out'];

	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			'transaksi/calendar',
			'transaksi/userdetail',
			'master/jabatan',
			'master/unit',
			'attendance/MyAttendance',
		]);
	}

	public function actionIndex()
	{
		
		$unit = $this->unit->get($this->session->userdata('detail_identity')->unit_id);

		if($unit->timezone){
			$tZ = $unit->timezone;
		}else{
			$tZ = 'Asia/Jakarta';
		}

		$attendance = $this->attendance->findOne([
			'calendar_id' => $this->calendar->getTodaysKey(),
			'user_id' => $this->session->userdata('identity')->id,
		]);

		$this->layout->title = 'Live Attendance';
		$this->layout->view_js = ['_partial/js_map_detail', '_partial/js_maps', '_partial/js_attendance'];
		$this->layout->view_css = '_partial/css_maps';
		$this->layout->render('index', [
			'attendance' => $attendance,
			'timezone' => $tZ,
		]);
	}

	public function actionCheck($type)
	{
		if (!in_array($type, $this->accept_check)) {
			show_error('Parameter tidak sesuai.', 404);exit;
		}

		$result = [
			'message' => 'Gagal melakukan absen, silahkan periksa data isian anda.',
			'check_in' => false,
			'check_out' => false,
			'attendances' => []
		];

		$unit = $this->unit->get($this->session->userdata('detail_identity')->unit_id);
		if($unit->timezone){
			$jam = $this->helpers->getTimezonedDate(time(), 'H:i', $unit->timezone);
		}else{
			$jam = $this->helpers->getTimezonedDate(time(), 'H:i', 'Asia/Jakarta');
		}


		if ($post = $this->input->post('Attendance')) {
			$calendar_id = $this->calendar->getTodaysKey();
			$user_id = $this->session->userdata('identity')->id;

			if ($calendar_id) {
				$attendance = $this->attendance->findOne([
					'calendar_id' => $calendar_id,
					'user_id' => $user_id,
				]);

				if (empty($attendance)) {
					$attendance = new Attendance;
				} elseif (
					!empty($attendance)
					&& ($attendance->check_in && $type == 'out')
				) {
					$attendance->daily_hours = $this->helpers->diffHours($attendance->check_in, $jam);
				}

				$attendance->calendar_id = $calendar_id;
				$attendance->user_id = $user_id;
				$attendance->{'check_' . $type} = $jam;
				$attendance->{'location_' . $type} = $post['location'];
				$attendance->{'catatan_' . $type} = $post['catatan'];
				$attendance->{'coordinate_' . $type} = $post['coordinate'];

				if ($attendance->save()) {
					$result = [
						'message' => 'Proses absen (Check '. ucfirst($type) .') berhasil dilakukan.',
						'check_in' => !empty($attendance->check_in),
						'check_out' => !empty($attendance->check_out) || empty($attendance->check_in),
						'attendances' => [
							'id' => $attendance->id,
							'check_in' => $attendance->check_in,
							'check_out' => $attendance->check_out,
						]
					];

				} else {
					$result['message'] = 'Proses absen (Check '. ucfirst($type) .') gagal dilakukan, silahkan coba beberapa saat lagi.';
				}

			} else {
				$result['message'] = 'Belum bisa melakukan absen untuk saat ini, dikarenakan data kalender belom di <i>generate</i>.';
			}
		}

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($result));
	}

	public function actionLogs()
	{
		$user = $this->user->findOne($this->session->userdata('identity')->id);
		$user_detail = $user->userDetail;
		$bln = date('m');
		$year = date('Y');

		if (isset($_POST['submit'])) {
			$bln = date($_POST['bulan']);

			if(!empty($bln)){
				$param_count = "user_id =". $user->id ." AND MONTH(date) =". $bln." AND keterangan='H'";	
			}

		} else {
			$param_count = "user_id =". $user->id ." AND MONTH(date) =". $bln." AND keterangan='H'";	
		}

		$checkData = $this->MyAttendance->CountAttendance($param_count);
		$calendars = $this->calendar->findAll(['MONTH(date)' => $bln, 'YEAR(date)' => $year]);

		$this->layout->view_js = ['_partial/js_map_detail', '_partial/sub_js'];
		$this->layout->view_css = '_partial/sub_css';
		$this->layout->title = 'My Attendance';

		$this->layout->render('logs', [
			'user_detail' => $user_detail,
			'bulan' => $bln,
			'count' => $checkData['Hadir'],
			'calendars' => $calendars,
			'user_id' => $user->id
		]);
	}

	public function actionDetail($id)
	{
		$type = $this->input->post('type');
		$model = $this->attendance->get($id);

		if (!in_array($type, ['in', 'out'])) {
			return false;
		}

		$result = [];

		if ($model) {
			$result = [
				'coordinate' => $model->{'coordinate_'.$type},
				'type' => 'Clock ' . ucfirst($type),
				'time' => date('H:i A', strtotime($model->{'check_'.$type})),
				'date' => date('d F Y', strtotime($model->created_at)),
				'location' => def($model, 'location_'.$type, '-'),
				'note' => def($model, 'catatan_'.$type, '-')
			];
		}

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($result));
	}

	public function actionLogsUpdate()
	{
		$user = $this->user->findOne($this->session->userdata('identity')->id);
		$user_detail = $user->userDetail;

		$this->layout->view_js = '_partial/sub_js';
		$this->layout->view_css = '_partial/sub_css';
		$this->layout->title = 'Update Attendance';

		$this->layout->render('Update_logs', [
			'user_detail' => $user_detail,
		]);
	}

	public function getData()
	{
		$search = $this->input->get('search');
		$UnitSesi = $this->session->userdata('detail_identity')->unit_id;
		$query = $this->attendance->getKaryawan($search,array('tbl_user_detail.unit_id' => $UnitSesi));

		echo json_encode($query);
	}

	public function actionGetData()
	{
		$input = $this->input->post('cari');
		$values = (empty($input)) ? NULL : $input;

		$user = $this->user->findOne($values['id_karyawan']);
		$user_detail = $user->userDetail;

		$calendars = $this->calendar->findAll(['date >= ' =>  date("Y-m-d", strtotime($values["tanggal_awal"])),'date <= ' => date('Y-m-d', strtotime($values['tanggal_akhir'])) ]);

		$view = '<div class="data-attendance">';
		$view .='<div class="row" style="margin-bottom: 30px; ">'; 
		$view .='<div class="col-sm-2">';

		if (!empty($user_detail->profile_pic)) {
			$view .= '<img src="' .  base_url($user_detail->profile_pic) . '" id="img-profile" class="img-responsive img-circle" style="border: 5px solid; width: 200px;" />';
		} else {
			$view .= '<img src="' . base_url('/web/assets/pages/img/no_avatar.jpg') . '" id="img-profile" class="img-responsive img-circle" style="border: 5px solid; width: 200px;" />';
		}

		$view .='</div>';
		$view .='<div class="col-sm-2" style="margin-top: 40px;">';
		$view .='<h3><b>';

		if (!empty($user_detail->nama_depan)) {
			$view .= '' . ucwords("{$user_detail->nama_depan} {$user_detail->nama_tengah} {$user_detail->nama_belakang}") . '';
		} else {
			$view .= '' . ucwords($this->session->userdata('identity')->username) . '';
		}

		$view .='</b></h3>';
		$view .='<h4>';

		if (!empty($user_detail->job_title)) {
			$view .= '' . ucwords($user_detail->job_title) . '';
		}

		$view .='</h4>';
		$view .='</div>';
		$view .='</div><hr>';
		$view .='<div class="table-responsive"><table class="table table-bordered table-responsive" id="table_update_atten">
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

		foreach ($calendars as $key => $model) {
			$attendee = $model->attendanceByUser($user->id)->get()->row();
			$getKet = Cek_Keterangan(def($attendee, 'keterangan'));
			$view .= '<tr ' . Cek_Weekend(def($model, 'is_weekend'), def($model, 'is_holiday')) . '>
			<td width="30" align="center">'. ($key + 1) .'</td>
			<td width="210"><a href="#" id="btn-update" data-id="'. $user->id .'" date="'. $model->date .'"  > '.  date('d F Y', strtotime($model->date)) .'</a></td>
			<td width="80" align="center">'. def($attendee, 'check_in') .'</td>
			<td>'.def($attendee, 'location_in').'</td>
			<td width="90" align="center" >'. def($attendee, 'check_out') .'</td>
			<td>'. def($attendee, 'location_out') .'</td>
			<td width="100" align="center">'. def($attendee, 'daily_hours') .'</td>
			<td><span '. $getKet['class'].' ><b> '.  $getKet['keterangan'] .'<b></span></td>
			</tr>';
		}

		$view .= '</tbody></table></div></div>';

		echo $view;
	}

	public function GetDataUpdate($id,$date)
	{	
		$calendars = $this->calendar->findAll(['date' => $date]);
		foreach ($calendars as $key => $value) {
			$attendee = $value->attendanceByUser($id)->get()->row();
			$check_in = def($attendee, 'check_in');
			$check_out = def($attendee, 'check_out');
			$keterangan = def($attendee, 'keterangan');
		}
		
		$user = $this->user->findOne($id);
		$user_detail = $user->userDetail;
		$name = $user_detail->nama_depan.' '.$user_detail->nama_tengah.' '.$user_detail->nama_belakang;

		$result = [];

		if ($calendars) {
			$result = [
				'id' => $id ?? "",
				'date' => $date ?? "",
				'name' => $name ?? "",
				'check_in' => $check_in ?? "",
				'check_out' => $check_out ?? "",
				'keterangan' => $keterangan ?? ""
			];
		}

		return $this->output
		->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($result));
    }

	public function actionUpdateLogs()
	{
		$post = $this->input->post('Attendance');

		if ($post) {
			$calendars = $this->calendar->findAll(['date' => $post['calendar_id']]);
			foreach ($calendars as $key => $model) {
				$attendee = $model->attendanceByUser($post['user_id'])->get()->row();
				$id = def($attendee, 'id');
				$check_in = def($attendee, 'check_in');
			}

			$post['calendar_id'] = $model->id;		

			if($id != null){
				$post['daily_hours'] = $this->helpers->diffHours($check_in, $post['check_out']);	# Reformat jam
				$save = $this->attendance->update($post,$id);
			}else{
				if($post['check_out'] == ""){
					$post['daily_hours'] = 0;	# Reformat jam
				}else{
					$post['daily_hours'] = $this->helpers->diffHours($post['check_in'], $post['check_out']);	# Reformat jam
				}
				$save = $this->attendance->insert($post);
			}

			if ($save) {
				$result = ['message' => 'Proses simpan berhasil', 'success' => true];
			} else {
				$result = ['message' => 'Proses simpan gagal', 'success' => false];
			}
		}

		return $this->output
		->set_content_type('application/json')
		        ->set_status_header(200) // Return status
		        ->set_output(json_encode($result));
    }

    public function actionEmployee()
    {
    	$date = $this->input->get('date');
    	$date = empty($date) ? date('Y-m-d') : date('Y-m-d', strtotime($date));

    	$detail_user = $this->session->userdata('detail_identity');
    	$model = $this->userdetail->findAll(['unit_id' => def($detail_user, 'unit_id')]);
    	$unit = !empty($detail_user->unit_id) ? $this->unit->findOne($detail_user->unit_id) : null;

    	$this->layout->title = 'Employee Attendance';
		$this->layout->view_js = ['_partial/js_map_detail', '_partial/sub_js'];
		$this->layout->view_css = '_partial/sub_css';
		$this->layout->render('logs_employee', [
			'model' => $model,
			'unit' => $unit,
			'date' => $date,
		]);
    }

}

/* End of file LiveAttendanceController.php */
/* Location: ./application/controllers/LiveAttendanceController.php */