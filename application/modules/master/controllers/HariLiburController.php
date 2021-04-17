<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class HariLiburController extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			'transaksi/calendar'
		]);
	}

	public function actionIndex()
	{
		$years = $this->calendar->getYears();

		$this->layout->view_js = '_partial/sub_js';
		$this->layout->view_css = '_partial/sub_css';
		$this->layout->title = 'Master Hari Libur';

		$this->layout->render('index', [
			'years' => $years,
			'min_date' => empty($years) ? 
							date('d-m-Y', strtotime('last day of January ' . date('Y'))) : 
							date('d-m-Y', strtotime('first day of January ' . $years[0])),
			'max_date' => empty($years) ? 
							date('d-m-Y', strtotime('last day of December ' . date('Y'))) : 
							date('d-m-Y', strtotime('last day of December ' . end($years))),
		]);
	}

	public function actionGetData()
	{
		if (!$this->input->is_ajax_request()) {
			show_error('Halaman tidak valid', 404);exit();
		}

		$list = $this->calendar->get_datatables();
        $data = [];
        $no = $_POST['start'];

        foreach ($list as $field) {
            $no++;
            $row = [];
            $row[] = $no;
            $row[] = $this->helpers->formatDateIndonesia($field->date);
            $row[] = $field->event ?? '-';
            $row[] = "
            	<div class='text-center'>
	            	<button data-id='{$field->id}' class='btn btn-info btn-xs btn-preview'><i class='fa fa-eye'></i></button> 
	            	<button data-id='{$field->id}' class='btn btn-warning btn-xs btn-edit'><i class='fa fa-pencil'></i></button> 
	            	<button data-id='{$field->id}' class='btn btn-danger btn-xs btn-delete'><i class='fa fa-trash-o'></i></button>
            	</div>
            ";
 
            $data[] = $row;
        }
 
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->calendar->count_all(),
            "recordsFiltered" => $this->calendar->count_filtered(),
            "data" => $data,
        );

        //output dalam format JSON
        echo json_encode($output);
	}

	public function actionTambah()
	{
		$result = [
			'status' => 500,
			'message' => 'Gagal tambah hari libur nasional, silahkan cek kembalian isian anda'
		];

		$post = $this->input->post('Calendar');

		if ($post) {
			$model = $this->calendar->findOne(['DATE(date)' => date('Y-m-d', strtotime($post['date']))]);

			if ($model && (intval($model->is_holiday) === 0 || !empty($this->input->post('validate')))) {
				$model->is_holiday = '1';
				$model->event = $post['event'];
				
				if ($model->save()) {
					$result = [
						'status' => 200,
						'message' => 'Penambahan data hari libur nasional berhasil',
						'is_validate' => false,
					];
				} else {
					$result = [
						'status' => 200,
						'message' => 'Proses simpan penambahan data hari libur nasional gagal, silahkan coba beberapa saat lagi',
						'is_validate' => false,
					];
				}
			} elseif ($model && intval($model->is_holiday) === 1) {
				$result = [
					'status' => 200,
					'message' => 'Sudah ada event hari libur nasional di tanggal ini, ubah dengan yang baru?',
					'is_validate' => true,
				];
			} else {
				$result = [
					'status' => 200,
					'message' => 'Data kalender belum dibuat.',
					'is_validate' => false,
				];
			}
		}

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($result));
	}

	public function actionDetail($id)
	{
		$model = $this->calendar->get($id);

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($model));
	}

	public function actionEdit($id)
	{
		$result = [
			'status' => 500,
			'message' => 'Gagal edit hari libur nasional, silahkan cek kembalian isian anda'
		];

		$post = $this->input->post('Calendar');

		if ($post) {
			$save = $this->calendar->update($post, $id);

			if ($save) {
				$result = [
					'message' => 'Perubahan data hari libur nasional berhasil'
				];
			} else {
				$result = [
					'message' => 'Proses simpan perubahan data hari libur nasional gagal, silahkan coba beberapa saat lagi'
				];
			}
		}

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($result));
	}

	public function actionHapus($id)
	{
		# Spot data before deleting
		$model = $this->calendar->findOne($id);

		if ($model) {
			$model->is_holiday = '0';
			$model->event = null;

			if ($model->save()) {
				$result = [
					'message' => 'Penghapusan data hari libur nasional berhasil'
				];
			} else {
				$result = [
					'message' => 'Proses hapus data hari libur nasional gagal, silahkan coba beberapa saat lagi'
				];
			}
		} else {
			$result = [
				'message' => 'Proses hapus data hari libur nasional gagal, data tidak ditemukan'
			];
		}

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($result));
	}

}

/* End of file HariLiburController.php */
/* Location: ./application/modules/master/controllers/HariLiburController.php */