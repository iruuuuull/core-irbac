<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UnitCampusController extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			'master/UnitCampus',
		]);
	}

	public function actionIndex()
	{
		$this->layout->title = 'Master Unit Campus';
		$this->layout->view_js = 'ext/index_js';
		$this->layout->view_css = 'ext/index_css';

		$this->layout->render('index', []);
	}

	public function actionGetData()
	{
		if (!$this->input->is_ajax_request()) {
			show_error('Halaman tidak valid', 404);exit();
		}

		$model = new UnitCampus;
		$list = $model->get_datatables();
        $data = [];
        $no = $_POST['start'];

        foreach ($list as $field) {
            $no++;
            $row = [];
            $row[] = $no;
            $row[] = $field->unit_id;
            $row[] = $field->unit_name;
            $row[] = $field->unit_parent_id;
            $row[] = $field->unit_level;
            $row[] = $field->getStatusValue();
            $row[] = $field->unit_kerjasama;
            $row[] = $field->unit_type;
            $row[] = "
            	<div class='text-center'>
            		". anchor("/master/unit-campus/detail/{$field->id}", "<i class='fa fa-eye'></i>", ['class' => 'btn-normal btn-info btn-xs']) ."
            		". anchor("/master/unit-campus/update/{$field->id}", "<i class='fa fa-pencil-alt'></i>", ['class' => 'btn-normal btn-warning btn-xs']) ."
                    ". $this->html->a(
                            "<i class='fa fa-trash-alt'></i>", 
                            "/master/unit-campus/hapus/{$field->id}", 
                            [
                                'class' => 'btn-normal btn-danger btn-xs',
                                'data' => [
                                    'method' => 'post',
                                    'confirm' => 'Yakin akan menghapus data ini?'
                                ],
                            ]
                        ) ."
            	</div>
            ";
 
            $data[] = $row;
        }
 
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $model->count_all(),
            "recordsFiltered" => $model->count_filtered(),
            "data" => $data,
        );

        //output dalam format JSON
        echo json_encode($output);
	}

    public function actionTambah()
    {
        $this->layout->title = 'Master Unit Campus';
        $this->layout->view_js = 'ext/tambah_js';
        $this->layout->view_css = 'ext/tambah_css';

        $model = new UnitCampus;

        if ($post = $this->input->post('Unit', true)) {
            if ($model->insert($post)) {
                $this->session->set_flashdata('success', 'Simpan data unit berhasil');

                return redirect('/master/unit-campus/index','refresh');
            } else {
                $this->session->set_flashdata('danger', "
                    Simpan data unit gagal: <br/>
                    ". $this->helpers->valueErrors($model->getErrors(), true) ."
                ");
            }
        }

        $this->layout->render('form', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $this->layout->title = 'Master Unit Campus';
        $this->layout->view_js = 'ext/tambah_js';
        $this->layout->view_css = 'ext/tambah_css';

        $model = $this->UnitCampus->findOne($id);

        if (!$model) {
            return show_error('Data tidak ditemukan', 404);
        }

        if ($post = $this->input->post('Unit', true)) {
            if ($model->update($post, $id)) {
                $this->session->set_flashdata('success', 'Simpan data unit berhasil');

                return redirect('/master/unit-campus/index','refresh');
            } else {
                $this->session->set_flashdata('danger', "
                    Simpan data unit gagal: <br/>
                    ". $this->helpers->valueErrors($model->getErrors(), true) ."
                ");
            }
        }

        $this->layout->render('form', [
            'model' => $model,
        ]);
    }

    public function actionDetail($id)
    {
        $this->layout->title = 'Master Unit Campus';
        $this->layout->view_js = 'ext/tambah_js';
        $this->layout->view_css = 'ext/tambah_css';

        $model = $this->UnitCampus->findOne($id);

        if (!$model) {
            return show_error('Data tidak ditemukan', 404);
        }

        $this->layout->render('form', [
            'model' => $model,
            'readonly' => true,
        ]);
    }

    public function actionHapus($id)
    {
        $model = $this->UnitCampus->findOne($id);

        if (!$model) {
            return show_error('Data tidak ditemukan', 404);
        }

        if ($model->delete($id)) {
            $this->session->set_flashdata('success', 'Hapus data unit berhasil');

            return redirect('/master/unit-campus/index','refresh');
        } else {
            $this->session->set_flashdata('danger', 'Hapus data unit gagal');
        }
    }

}

/* End of file UnitCampusController.php */
/* Location: ./application/modules/master/controllers/UnitCampusController.php */