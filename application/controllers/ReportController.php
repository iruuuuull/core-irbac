<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ReportController extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			'master/group',
			'report/Reportkaryawan',
		]);
	}

	public function actionIndex()
	{
		$admin_cab = $this->helpers->isAdminCabang();
		$admin_dir = $this->helpers->isAdminDirektorat();
		$super_admin = $this->helpers->isSuperAdmin();

		$UnitSesi = $this->session->userdata('detail_identity')->unit_id;

		if ($super_admin) {
			$getKaryawan = $this->Reportkaryawan->Dataget();
			$data_admin = 'admin';
		}elseif ($admin_dir) {
			$unit = $this->unit->get($UnitSesi);
			$params = "(tbl_m_unit.kode_unit = ".$unit->kode_unit ." OR tbl_m_unit.kode_induk = ".$unit->kode_unit.")";
			$getKaryawan = $this->Reportkaryawan->FindKaryawan($params);
			$data_admin = 'admin';
		}elseif ($admin_cab) {
			$getKaryawan = $this->Reportkaryawan->FindKaryawan(array('tbl_user_detail.unit_id' => $UnitSesi));
			$data_admin = 'admin';
		}else {
			$data_admin = '';
			$getKaryawan = '';
		}

		$this->layout->view_js = '_partial/sub_js';
		$this->layout->view_css = '_partial/sub_css';
		$this->layout->title = 'Report Karyawan';
		
		$data_group = $this->Reportkaryawan->getListGroupUnit();

		$this->layout->render('karyawan/index', [
			'data_group' => $data_group,
			'admin' => $data_admin,
			'Karyawans' => $getKaryawan,
		]);

	}

	public function getUnit()
	{
		$KodeUnit = explode('@', $this->input->post('group'));
		// var_dump($KodeUnit[0]);

		if($KodeUnit[0] == 10000){
			$setParam = array('kode_induk' => $KodeUnit[0]);
		}else{
			$setParam = array('kode_induk' => $KodeUnit[0],'unit_level' => 3);
		}

		$getDataUnit = $this->Reportkaryawan->getUnit($setParam);
		foreach ($getDataUnit as $key => $value){
			$DataUnit[$key]['id'] = $value['id'];
			$DataUnit[$key]['sbu_id'] = $value['sbu_id'];
			$DataUnit[$key]['unit_level'] = $value['unit_level'];
			$DataUnit[$key]['kode_unit'] = $value['kode_unit'];
			$DataUnit[$key]['nama_unit'] = $value['nama_unit'];

			// $DataUnit[$key]['kode_group_dept'] = $value['kode_group_dept'];
		}
		echo json_encode($DataUnit);
	}

	public function getBagian()
	{
		// $groupBagian = $this->input->post('group');
		$groupBagian = explode('@', $this->input->post('group'));
		// var_dump($groupBagian);
		if($groupBagian[3] == 20000){
			$setParam = array('sbu_id' => 2);
		}else{
			$setParam = array('sbu_id' => $groupBagian[1]);
		}

		// $setParam = array('sbu_id' => $groupBagian[1]);
		
		$getDataBagian = $this->Reportkaryawan->getBagianData($setParam);
		foreach ($getDataBagian as $key => $value){
			$DataBagian[$key]['id'] = $value['id'];
			$DataBagian[$key]['department'] = $value['department'];
		}
		echo json_encode($DataBagian);
	}

	public function getDataKaryawan()
	{
		$input = $this->input->post('cari');
		$values = (empty($input)) ? NULL : $input;

		$var_group = explode('@', $values['v_group']);
		$var_unit = explode('@', $values['v_unit']);
		$kode_unit_group = $var_group[0];
		$sbu_id = $var_group[1];
		$unit_level = $var_unit[2];
		$id_unit_group = $var_group[2];
		$id_unit_sub = $var_unit[0];
		$kode_unit_sub = $var_unit[3];

		// JIKA PILIH DATA HO : HO
		if($id_unit_group == 1 && $unit_level == 1){
			// CEK JIKA PILIH KESELURUHAN
			if($values['v_bagian']=='all'){
				$params = array('tbl_user_detail.unit_id' => $id_unit_sub);
			}else{
				$params = array('tbl_user_detail.unit_id' => $id_unit_sub, 'tbl_user_detail.department_id' => $values['v_bagian']);
			}
		// JIKA PILIH DATA HO : GROUP UNIT <> HO
		}elseif($id_unit_group == 1 && $unit_level <> 1){
			// CEK JIKA PILIH KESELURUHAN
			if($values['v_bagian']=='all'){
				$params = "(tbl_m_unit.kode_unit = ".$kode_unit_sub ." OR tbl_m_unit.kode_induk = ".$kode_unit_sub.")";
			}else{
				$params = "(tbl_m_unit.kode_unit = ".$kode_unit_sub ." OR tbl_m_unit.kode_induk = ".$kode_unit_sub. " AND tbl_user_detail.department_id = " .$values['v_bagian'].")";
			}
		}
		// JIKA DATA NON HO / CABANG
		else{
			// CEK JIKA PILIH KESELURUHAN
			if($values['v_bagian']=='all'){
				$params = array('tbl_user_detail.unit_id' => $id_unit_sub);
			}else{
				$params = array('tbl_user_detail.unit_id' => $id_unit_sub, 'tbl_user_detail.department_id' => $values['v_bagian']);
			}
		}

		$checkData = $this->Reportkaryawan->CountKaryawan($params);
		$countData = $checkData->num_rows();
		// print_r($countData);exit;

		$view = '<div class="table-data">';
		if ($countData == 0){
			// $view .= '<h4>Tidak ada data yang ditampilkan.</h4>';
			?>

			<script type="text/javascript">
				swalert({
                        title: 'Gagal ambil data',
                        message: 'Tidak ada data yang ditampilkan',
                        type: 'warning'
                    });
			</script>

			<?php
		}
		else {

				$Karyawans = $this->Reportkaryawan->FindKaryawan($params);
				// var_dump($getClass);
				$view .='<div class="table-responsive"><table class="table table-bordered table-hover table-responsive" id="admin_2">
				<div style="margin-top:70px;">
				</div>
				<thead>
				<tr style="background-color:#031727;color:white;">
				<th>No</th>
				<th>Nama</th>
				<th>Bagian</th>
				<th>Jabatan</th>
				<th>Email</th>
				<th>Unit</th>
				<th>Status</th>
				<th>Tanggal Selesai</th>
				</tr>
				</thead><tbody>';
		        $i = 1 ;
		        foreach ($Karyawans as $key => $value){
		        	$nama_lengkap = $value['nama_depan'].' '.$value['nama_tengah'].' '.$value['nama_belakang'];
		        	$tanggal_selesai = (int)strtotime($value['tanggal_selesai']) > 0 ? date('d-F-Y', strtotime($value['tanggal_selesai'])) : '-';

		        	$view .= '<tr>
		            	            <td>' . $i++ . '</td>
		            	            <td width="300">' . anchor('/profil?id='. $value['user_id'], $nama_lengkap, ['target' => '_blank']) . '</td>
		            	            <td>' . $value['department'] . '</td>
		            	            <td width="200">' . $value['job_title'] . '</td>
		            	            <td width="250">' . $value['email'] . '</td>
		            	            <td>' . $value['nama_unit'] . '</td>
		            	            <td>' . $value['status'] . '</td>
		            	            <td>' . $tanggal_selesai . '</td>
		           			</tr>';
		    }
		            	        
		    $view .= '</tbody></table></div>';

		}
		
		echo $view;
	}


}
