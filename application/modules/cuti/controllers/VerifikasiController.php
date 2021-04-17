<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class VerifikasiController extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			'transaksi/cuti',
			'transaksi/cutiverifikasi',
            'transaksi/cutipembatalan',
            'master/department',
            'forms/FormBatalCuti',
            'notifikasi',
		]);
	}

	public function actionIndex()
	{
		$this->layout->title = "Verifikasi Cuti";
		$this->layout->view_js = ["_partial/js"];
		$this->layout->view_css = "_partial/css";

        $statuses = $this->cutiverifikasi->getListStatus();

		$this->layout->render('index', [
            'statuses' => $statuses,
		]);
	}

	public function actionGetData($user_nip = null)
	{
		if (!$this->input->is_ajax_request()) {
			show_error('Halaman tidak valid', 404);exit();
		}

		$model = new Cuti;
		$model->hr_id = $this->session->userdata('identity')->id;
		$model->verifikator_id = $this->session->userdata('identity')->id;

		if ($user_nip) {
			$model->nip_user = $user_nip;
		}

		$list = $model->get_datatables();
        $data = [];
        $no = $_POST['start'];

        foreach ($list as $field) {
        	$cuti = $this->cuti->findOne($field->id);
        	$verifikasi = $cuti->cutiVerifikasi;
        	$current_verifikator = $this->cutiverifikasi->getCurrent($verifikasi);

			$amount = diffWorkDay($field->tanggal_mulai, $field->tanggal_akhir);;

			$btn_cancel = in_array($field->status, [Cuti::STATUS_PROSES, Cuti::STATUS_MENUNGGU]) ? 'btn-warning' : 'btn-default';

            $no++;
            $row = [];
            $row[] = $no;
            $row[] = $cuti->user->userdetail->mergeFullName();
            $row[] = date('d-m-Y', strtotime($field->created_at));
            $row[] = date('d-m-Y', strtotime($field->tanggal_mulai));
            $row[] = date('d-m-Y', strtotime($field->tanggal_akhir));
            $row[] = "<a href='javascript:;' data-note='{$field->note}' class='show-note'>Tampilkan</a>";
            $row[] = $this->cutiverifikasi->getStatusValue($field->status_verif);
            $row[] = $amount . ' Hari';
            $row[] = !empty($field->attachment) ? $this->html->button('<i class="fa fa-file-pdf-o"></i>', [
            				'class' => 'btn btn-danger btn-xs',
            				'onclick' => 'return previewPDF(\''. base_url($field->attachment) .'\')',
            			]) : null;

            $buttons = "<div class='text-center'>";

            # Jika ditetapkan verifikator dan memiliki akses verifikasi atau sudah pernah verifikasi
            if (
                $current_verifikator 
                && (
                    ($field->urutan < $current_verifikator->urutan && empty($current_verifikator->status))
                    || ($current_verifikator->verifikator_id === $model->verifikator_id && $field->status == Cuti::STATUS_PROSES)
                )
            ) {
	            $buttons .= $this->html->button('Verifikasi', [
	        				'class' => 'btn btn-primary btn-xs btn-block btn-verify',
	        				'data-id' => $field->id,
	        			]);
            }

            # Jika ada melakukan pengajuan pembatalan dan memiliki hak sebagai HR
            if (
                $this->session->userdata('detail_identity')->department_id == Department::IDEP_HR
            ) {
                $buttons .= $this->html->button('Pembatalan', [
                    'class' => 'btn btn-info btn-xs btn-block btn-cancel',
                    'data-id' => $field->id,
                ]);
            }

            if (empty($verifikasi) && $cuti->hr === $model->hr_id) {
            	$buttons .= anchor("/cuti/verifikasi/set-verifikator/{$field->id}", 
            		'Set Verifikator', [
            			'class' => 'btn btn-info btn-xs btn-block'
            		]);
            }
 
            $row[] = $buttons . "</div>";

            $data[] = $row;
        }
 

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->cuti->count_all(),
            "recordsFiltered" => $this->cuti->count_filtered(),
            "data" => $data,
        );

        //output dalam format JSON
        echo json_encode($output);
	}

    public function actionSimpan($id)
    {
        $result = [
            'message' => 'Proses verifikasi gagal, mohon periksa isian anda.',
            'type' => 'error'
        ];

        $model = $this->cutiverifikasi->findOne([
            'cuti_id' => $id,
            'verifikator_id' => $this->session->userdata('identity')->id
        ]);

        if ($model && $post = $this->input->post('CutiVerifikasi', true)) {
            $model->status = $post['status'];
            $model->note = $post['note'];

            try {
                $this->db->trans_begin();

                if ($model->save()) {
                    $result = [
                        'message' => 'Proses verifikasi cuti berhasil',
                        'type' => 'success'
                    ];

                    # Informasika ke pemohon jika diterima dan kurangi jumlah cuti
                    $follow_up = $this->cutiverifikasi->followUp($model->cuti_id);

                    $this->db->trans_commit();

                } else {
                    $result = [
                        'message' => 'Proses verifikasi cuti gagal, silahkan coba beberapa saat lagi',
                        'type' => 'warning'
                    ];

                    $this->db->trans_rollback();
                }

            } catch (Exception $e) {
                $result = [
                    'message' => 'Proses simpan gagal, silahkan coba beberapa saat lagi. Jika masih gagal, silahkan hubungi Administrator',
                    'type' => 'danger'
                ];

                $this->db->trans_rollback();
            }
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200) // Return status
            ->set_output(json_encode($result));
    }

    public function actionSetVerifikator($id)
    {
        $model = $this->cuti->findOne($id);

        if (empty($model)) {
            show_error('Data cuti tidak ditemukan.', 404);exit();
        } elseif (!empty($model->cutiVerifikasi)) {
            show_error('Data cuti sudah memiliki verifikator.', 404);exit();
        }

        $set_verificator = $model->setVerifikator();

        if ($set_verificator) {
            $this->session->set_flashdata('success', 'Permohonan cuti berhasil diteruskan ke verifikator.');
        } else {
            $this->session->set_flashdata('success', 'Permohonan cuti gagal diteruskan ke verifikator. Pastikan user sudah ditentukan atasannya.');
        }

        redirect('/cuti/verifikasi', 'refresh');
    }

    public function actionGet($id)
    {
        $model = $this->cuti->get($id);
        $data_verifikasi = null;

        if ($model) {
            $data_verifikasi = $this->cutiverifikasi->get([
                'cuti_id' => $model->id, 
                'verifikator_id' => $this->session->userdata('identity')->id
            ]);
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200) // Return status
            ->set_output(json_encode($data_verifikasi));
    }

    public function actionGetCancelForm($id)
    {
        $model_cuti = $this->cuti->findOne($id);
        $form = '';

        if ($model_cuti) {
            $model = new FormBatalCuti;
            $model_cancel = $this->cutipembatalan->findOne(['cuti_id' => $id]);
            $model_user = $this->user->findOne($model_cuti->user->id);

            $model->setData($model_cancel);

            $form = $this->layout->renderPartial("../permohonan/_partial/agreed_cancel", [
                'model_cuti' => $model_cuti,
                'model_user' => $model_user,
                'model' => $model,
                'action' => "/cuti/verifikasi/pembatalan/{$id}"
            ]);
        }

        return $form;
    }

    public function actionPembatalan($id)
    {
        $model_cuti = $this->cuti->findOne($id);
        $model = new FormBatalCuti;

        $result = [
            'message' => 'Proses persetujuan pembatalan cuti gagal, mohon periksa isian anda.',
            'type' => 'error'
        ];

        if ($model_cuti && $post = $this->input->post('FormBatalCuti')) {
            $model->setAttributes($post);
            $model->data_cuti = $model_cuti;
            $model->method = 'update';

            try {
                $this->db->trans_begin();

                if ($model->validate()) {
                    if ($model->approving()) {
                        $result = [
                            'message' => 'Permohonan pembatalan cuti berhasil disetujui',
                            'type' => 'success'
                        ];

                        $trigger_notif = Notifikasi::sendNotif(
                            $model_cuti->user_id,
                            "Permohonan pembatalan cuti disetujui"
                        );

                        if (!$trigger_notif) {
                            $result['message'] .= ', tetapi gagal mengirimkan notifikasi ke Karyawan. Silahkan informasikan secara manual.';
                        }

                        $this->db->trans_commit();

                    } else {
                        throw new CustomException('Proses persetujuan pembatalan cuti gagal, silahkan coba beberapa saat lagi.');
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
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200) // Return status
            ->set_output(json_encode($result));
    }

}

/* End of file VerifikasiController.php */
/* Location: ./application/modules/cuti/controllers/VerifikasiController.php */