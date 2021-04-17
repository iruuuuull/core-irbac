<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PermohonanController extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			'transaksi/cuti',
			'transaksi/userdetail',
			'transaksi/cutipembatalan',
			'master/tipecuti',
			'master/user',
			'notifikasi',
			'forms/FormCuti',
			'forms/FormBatalCuti',
			'forms/FormCutiBersama',
		]);
	}

	public function actionIndex()
	{
		$tipe_cuti = $this->tipecuti->getListType(true, true);
		$model_user = $this->user->findOne($this->session->userdata('identity')->id);

		$this->layout->title = "Permohonan Cuti";
		$this->layout->view_js = ["js_cuti", "js_action_cuti"];
		$this->layout->view_css = "css_cuti";

		$this->layout->render('index', [
			'tipe_cuti' => $tipe_cuti,
			'model_user' => $model_user,
		]);
	}

	public function actionGetData()
	{
		if (!$this->input->is_ajax_request()) {
			show_error('Halaman tidak valid', 404);exit();
		}

		$model = new Cuti;
		$model->id_user = $this->session->userdata('identity')->id;
		$list = $model->get_datatables();
        $data = [];
        $no = $_POST['start'];

        foreach ($list as $field) {
        	$cuti = $this->cuti->findOne($field->id);
        	$amount = diffWorkDay($field->tanggal_mulai, $field->tanggal_akhir);

			$btn_cancel = in_array($field->status, [Cuti::STATUS_PROSES, Cuti::STATUS_MENUNGGU]) ? 'btn-warning' : 'btn-default';
			$count_cancel = $this->cutipembatalan->find()->where(['cuti_id' => $field->id])->count_all_results();

			if (
				$field->status == Cuti::STATUS_SETUJU
				&& time() < strtotime($field->tanggal_akhir)
				&& $count_cancel === 0
			) {
				$btn_cancel = 'btn-danger';
			}

            $no++;
            $row = [];
            $row[] = $no;
            $row[] = date('d-m-Y', strtotime($field->created_at));
            $row[] = date('d-m-Y', strtotime($field->tanggal_mulai));
            $row[] = date('d-m-Y', strtotime($field->tanggal_akhir));
            $row[] = "<a href='javascript:;' data-note='{$field->note}' class='show-note'>Show</a>";
            $row[] = $cuti->getStatusValue();
            $row[] = $amount . ' Hari';
            $row[] = "<a href='javascript:;' data-id='{$field->id}' class='show-tracking' title='Tracking'><i class='fa fa-bars'></i></a>";
            $row[] = !empty($field->attachment) ? $this->html->button('<i class="fa fa-file-pdf-o"></i>', [
            				'class' => 'btn btn-danger btn-xs',
            				'onclick' => 'return previewPDF(\''. base_url($field->attachment) .'\')',
            			]) : null;

            if ($model->id_user === $field->user_id) {
	            $row[] = "
	            	<div class='text-center'>".
		            	$this->html->button('Batalkan', [
	        				'class' => $btn_cancel . ' btn btn-xs btn-block btn-cancel',
	        				'data-id' => $field->id,
	        			])
	            	."</div>
	            ";
            } else {
            	$row[] = null;
            }
 
            $data[] = $row;
        }
 

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->cuti->count_all(['tbl_cuti.user_id' => $model->id_user]),
            "recordsFiltered" => $this->cuti->count_filtered(['tbl_cuti.user_id' => $model->id_user]),
            "data" => $data,
        );

        //output dalam format JSON
        echo json_encode($output);
	}

	public function actionAjukan()
	{
		$post = $this->input->post('Cuti');
		$user_id = $this->session->userdata('identity')->id;

		$result = [
			'message' => 'Proses pengajuan gagal, mohon periksa isian anda.',
			'type' => 'error'
		];

		if ($post) {
			# Reformat Date
			$post['tanggal_mulai'] = $post['tanggal_mulai'] ? date('Y-m-d', strtotime($post['tanggal_mulai'])) : null;
			$post['tanggal_akhir'] = $post['tanggal_mulai'] ? date('Y-m-d', strtotime($post['tanggal_akhir'])) : null;
			$post['user_id'] = $user_id;

			try {
				$this->db->trans_begin();

				$upload = true;
				if (!empty($_FILES['attachment']['name'])) {
					$path = './web/uploads/attachment/';

					if (!file_exists($path)) {
					    mkdir($path, 0777, true);
					}

					$config['upload_path'] = $path;
					$config['allowed_types'] = 'pdf';
					$config['max_size'] = '1024';
					$config['file_name'] = 'Cuti_'. date('YmdHis') .'_'. $user_id;
					
					$this->load->library('upload', $config);

					if ( ! $this->upload->do_upload('attachment')){
						$result = ['message' => $this->upload->display_errors()];
						$upload = false;
					} else {
						$post['attachment'] = $path .'/'. $this->upload->data("file_name");
						$upload = true;
					}
				}

				if ($upload) {
					$user_hr = $this->userdetail->getHr($user_id);
					$post['hr'] = $user_hr->user_id;

					$model = new FormCuti;
					$model->setAttributes($post);

					if ($model->validate()) {
						if ($model->save()) {
							$result = [
								'message' => 'Permohonan pengajuan cuti berhasil diajukan',
								'type' => 'success'
							];

							$this->db->trans_commit();
						} else {
							if ($post['attachement']) {
								unlink($post['attachement']);
							}

							throw new CustomException('Proses pengajuan cuti gagal, silahkan coba beberapa saat lagi.');
						}
					} else {
						throw new CustomException(implode('<br/>', array_values($model->getErrors())));
					}

				}
			} catch (CustomException $e) {
				$result = [
					'message' => $e->getMessage(),
					'type' => 'warning'
				];

				$this->db->trans_rollback();
			} catch (Exception $e) {
				$result = [
					'message' => 'Proses simpan gagal, silahkan coba beberapa saat lagi. Jika masih gagal, silahkan hubungi Administrator',
					'type' => 'error'
				];

				$this->db->trans_rollback();
			}
		}

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($result));
	}

	public function actionGetForm($type)
	{
		$post = $this->input->post();
		$id = $post['id'];

		$model = new FormBatalCuti;
		$model_cuti = $this->cuti->findOne($id);
		$model_user = $this->user->findOne($this->session->userdata('identity')->id);
		$form = '';

		if ($model_cuti) {
			if ($type == 'normal_cancel') {
				$form = $this->layout->renderPartial("_partial/{$type}", [
					'id' => $id
				]);
			} elseif ($type == 'agreed_cancel') {
				$form = $this->layout->renderPartial("_partial/{$type}", [
					'id' => $id, 
					'model_cuti' => $model_cuti,
					'model_user' => $model_user,
					'model' => $model,
					'action' => "/cuti/permohonan/batalkan-setuju/{$id}"
				]);
			}
		}

		return $form;
	}

	public function actionBatalkan($id)
	{
		$model = $this->cuti->findOne($id);
		$post = $this->input->post('Cuti');

		$result = [
			'message' => 'Proses pembatalan gagal, mohon periksa isian anda.',
			'type' => 'error'
		];

		if ($model && $post) {
			$model->is_cancel = 1;
			$model->status = 4;
			$model->note_cancel = $post['note_cancel'];

			if ($model->save()) {
				$result = [
					'message' => 'Proses pembatalan cuti berhasil',
					'type' => 'success'
				];
			} else {
				$result = [
					'message' => 'Proses pembatalan cuti gagal, mohon coba beberapa saat lagi.',
					'type' => 'warning'
				];
			}
		}

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($result));
	}

	public function actionBatalkanSetuju($id)
	{
		$cuti = $this->cuti->findOne(['id' => $id, 'status' => Cuti::STATUS_SETUJU]);
		$post = $this->input->post('FormBatalCuti');
		$model = new FormBatalCuti;

		$result = [
			'message' => 'Proses pembatalan gagal, mohon periksa isian anda.',
			'type' => 'error'
		];

		if ($cuti) {
			$model->setAttributes($post);
			$model->data_cuti = $cuti;

			try {
				$this->db->trans_begin();

				if ($model->validate()) {
					if ($model->save()) {
						$result = [
							'message' => 'Permohonan pembatalan cuti berhasil diajukan',
							'type' => 'success'
						];

						$user_hr = $this->userdetail->getHr($cuti->user_id);
						$trigger_notif_hr = Notifikasi::sendNotif(
							$user_hr->user_id,
							"Pengajuan permohonan pembatalan cuti {$this->session->userdata('detail_identity')->nik}",
							site_url('/cuti/verifikasi')
						);

						if (!$trigger_notif_hr) {
							$result['message'] .= ', tetapi gagal mengirimkan notifikasi ke HRD. Silahkan informasikan secara manual.';
						}

						$this->db->trans_commit();
					} else {
						throw new CustomException('Proses pembatalan cuti gagal, silahkan coba beberapa saat lagi.');
					}
				} else {
					throw new CustomException(implode('<br/>', array_values($model->getErrors())));
				}

			} catch (CustomException $e) {
				$result = [
					'message' => $e->getMessage(),
					'type' => 'warning'
				];

				$this->db->trans_rollback();
			} catch (Exception $e) {
				$result = [
					'message' => 'Proses simpan gagal, silahkan coba beberapa saat lagi. Jika masih gagal, silahkan hubungi Administrator',
					'type' => 'error'
				];

				$this->db->trans_rollback();
			}

		} else {
			$result['message'] = 'Data cuti yang akan dibatalkan tidak ditemukan';
		}

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($result));
	}

	public function actionGetExisting($id=null)
	{
		$id_user = $this->session->userdata('identity')->id;
		if ($id) {
			$id_user = $id;
		}

		$data = $this->cuti->get(['user_id' => $id_user, 'status' => [Cuti::STATUS_MENUNGGU, Cuti::STATUS_PROSES]]);

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode(['status' => true, 'data' => $data]));
	}

	public function actionGetTracking($id)
	{
		$model = $this->cuti->findOne($id);

		$result = [
			'status' => 'error',
			'message' => 'Data tidak ditemukan',
			'data' => null
		];

		if (!empty($model)) {
			$verifikators = $model->cutiVerifikasi;
			$html = "";

			if ($verifikators) {
				$html .= '<table class="table table-bordered table-condensed table-stripped">';
					$html .= "<tr>";
						$html .= "<th>Nama Verifikator</th>";
						$html .= "<th>Status</th>";
						$html .= "<th>Catatan</th>";
					$html .= "</tr>";

					foreach ($verifikators as $key => $verifikator) {
						$html .= "<tr>";
							$html .= "<td>". $verifikator->userDetail->mergeFullName() ."</td>";
							$html .= "<td>". $this->cutiverifikasi->getStatusValue($verifikator->status) ."</td>";
							$html .= "<td>". $verifikator->note ."</td>";
						$html .= "</tr>";
					}
				$html .= '</table>';
			}

			$result = [
				'status' => 'success',
				'message' => 'Data ditemukan',
				'data' => $html
			];
		}

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($result));
	}

	/**
	 * [actionTipeCuti ambil sub tipe cuti]
	 * @param  int    $id [ID Tipe Cuti]
	 * @return json
	 */
	public function actionTipeCuti($id)
	{
		$tipe = $this->tipecuti->findOne($id);
		$list_tipe = $tipe->getChildListType();

		$result = [
			'status' => 'error',
			'message' => 'Data tidak ditemukan',
			'data' => null,
			'meta' => []
		];

		if ($list_tipe) {
			$result = [
				'status' => 'success',
				'message' => 'Data ditemukan',
				'data' => $list_tipe,
				'meta' => $this->tipecuti->getListType()
			];
		}

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($result));
	}

	public function actionCutiBersama()
	{
		$post = $this->input->post('FormCutiBersama');

		$result = [
			'message' => 'Proses pengajuan gagal, mohon periksa isian anda.',
			'type' => 'error'
		];

		if ($post) {
			try {
				$this->db->trans_begin();

				$upload = true;
				if (!empty($_FILES['attachment']['name'])) {
					$path = './web/uploads/attachment/';

					if (!file_exists($path)) {
					    mkdir($path, 0777, true);
					}

					$config['upload_path'] = $path;
					$config['allowed_types'] = 'pdf';
					$config['max_size'] = '1024';
					$config['file_name'] = 'Cuti-Bersama_'. date('YmdHis');
					
					$this->load->library('upload', $config);

					if ( ! $this->upload->do_upload('attachment')){
						$result = ['message' => $this->upload->display_errors()];
						$upload = false;
					} else {
						$post['attachment'] = $path .'/'. $this->upload->data("file_name");
						$upload = true;
					}
				}

				if ($upload) {
					$model = new FormCutiBersama;
					$model->cuti_type = Tipecuti::ANNUAL_LEAVE;

					$model->setAttributes($post);

					if ($model->validate()) {
						if ($model->save()) {
							$result = [
								'message' => 'Penerapan cuti bersama ke seluruh karyawan berhasil',
								'type' => 'success'
							];

							$this->db->trans_commit();
						} else {
							if ($post['attachement']) {
								unlink($post['attachement']);
							}

							throw new CustomException('Penerapan cuti bersama ke seluruh karyawan gagal, silahkan coba beberapa saat lagi.');
						}
					} else {
						throw new CustomException(implode('<br/>', array_values($model->getErrors())));
					}

				}
			} catch (CustomException $e) {
				$result = [
					'message' => $e->getMessage(),
					'type' => 'warning'
				];

				if (!empty($post['attachment'])) {
					if (file_exists($post['attachment'])) {
						unlink($post['attachment']);
					}
				}

				$this->db->trans_rollback();
			} catch (Exception $e) {
				$result = [
					'message' => 'Proses simpan gagal, silahkan coba beberapa saat lagi. Jika masih gagal, silahkan hubungi Administrator',
					'type' => 'error'
				];

				if (!empty($post['attachment'])) {
					if (file_exists($post['attachment'])) {
						unlink($post['attachment']);
					}
				}

				$this->db->trans_rollback();
			}
		}

		return $this->output
	        ->set_content_type('application/json')
	        ->set_status_header(200) // Return status
	        ->set_output(json_encode($result));
	}

}

/* End of file CutiController.php */
/* Location: ./application/controllers/CutiController.php */