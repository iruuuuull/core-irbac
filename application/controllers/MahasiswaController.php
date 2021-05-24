<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MahasiswaController extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
		$this->load->model([
			'transaksi/Mahasiswa',
            'master/Agama',
            'master/Pendidikan',
            'master/Pekerjaan',
            'master/PembimbingAkademik',
		]);
	}

	public function actionIndex()
	{
		$this->layout->title = 'Student Management';
		$this->layout->view_js = 'ext/index_js';
		$this->layout->view_css = 'ext/index_css';

		$this->layout->render('index', []);

	}

	public function actionGetData()
	{
		if (!$this->input->is_ajax_request()) {
			show_error('Halaman tidak valid', 404);exit();
		}

		$model = new Mahasiswa;
		$list = $model->get_datatables();
        $data = [];
        $no = $_POST['start'];

        foreach ($list as $field) {
            $no++;
            $row = [];
            $row[] = $no;
            $row[] = $field->student_name.'</br>'.$field->student_nim;
            $row[] = $field->unit_id;
            $row[] = $field->student_ta;
            $row[] = $field->product_id;
            $row[] = 'tester';
            $row[] = 'tester';
            // $row[] = $field->unit_type;
            // $row[] = "
            // 	<div class='text-center'>
            // 		". anchor("/master/unit-campus/detail/{$field->id}", "<i class='fa fa-eye'></i>", ['class' => 'btn-normal btn-info btn-xs']) ."
            // 		". anchor("/master/unit-campus/update/{$field->id}", "<i class='fa fa-pencil-alt'></i>", ['class' => 'btn-normal btn-warning btn-xs']) ."
            //         ". $this->html->a(
            //                 "<i class='fa fa-trash-alt'></i>", 
            //                 "/master/unit-campus/hapus/{$field->id}", 
            //                 [
            //                     'class' => 'btn-normal btn-danger btn-xs',
            //                     'data' => [
            //                         'method' => 'post',
            //                         'confirm' => 'Yakin akan menghapus data ini?'
            //                     ],
            //                 ]
            //             ) ."
            // 	</div>
            // ";
 
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
        $this->layout->title = 'Student Management';
        $this->layout->view_js = 'ext/tambah_js';
        $this->layout->view_css = 'ext/tambah_css';

        $model = new Mahasiswa;
        $model_agama = new Agama;
        $model_pendidikan = new Pendidikan;
        $model_pekerjaan = new Pekerjaan;
        $model_pa = new PembimbingAkademik;

        if ($post = $this->input->post('Mahasiswa', true)) {
            print_r($post);
            if ($model->insert($post)) {
                $this->session->set_flashdata('success', 'Simpan data unit berhasil');

                return redirect('/mahasiswa','refresh');
            } else {
                $this->session->set_flashdata('danger', "
                    Simpan data mahasiswa gagal: <br/>
                    ". $this->helpers->valueErrors($model->getErrors(), true) ."
                ");
            }
        }

        $this->layout->render('form', [
            'model' => $model,
            'model_agama' => $model_agama,
            'model_pendidikan' => $model_pendidikan,
            'model_pekerjaan' => $model_pekerjaan,
            'model_pa' => $model_pa,
        ]);
    }

    public function actionUpdate($id)
    {
        $this->layout->title = 'Student Management';
        $this->layout->view_js = 'ext/tambah_js';
        $this->layout->view_css = 'ext/tambah_css';

        $model = $this->Mahasiswa->findOne($id);
        $model_agama = new Agama;
        $model_pendidikan = new Pendidikan;
        $model_pekerjaan = new Pekerjaan;
        $model_pa = new PembimbingAkademik;

        if (!$model) {
            return show_error('Data tidak ditemukan', 404);
        }

        if ($post = $this->input->post('Mahasiswa', true)) {
            if ($model->update($post, $id)) {
                $this->session->set_flashdata('success', 'Simpan data mahasiswa berhasil');

                 return redirect('/mahasiswa','refresh');
            } else {
                $this->session->set_flashdata('danger', "
                    Simpan data mahasiswa gagal: <br/>
                    ". $this->helpers->valueErrors($model->getErrors(), true) ."
                ");
            }
        }

        $this->layout->render('form', [
            'model' => $model,
            'model_agama' => $model_agama,
            'model_pendidikan' => $model_pendidikan,
            'model_pekerjaan' => $model_pekerjaan,
            'model_pa' => $model_pa,
        ]);
    }

    public function actionDetail($id)
    {
        $this->layout->title = 'Student Management';
        $this->layout->view_js = 'ext/tambah_js';
        $this->layout->view_css = 'ext/tambah_css';

        $model = $this->Mahasiswa->findOne($id);
        $model_agama = new Agama;
        $model_pendidikan = new Pendidikan;
        $model_pekerjaan = new Pekerjaan;
        $model_pa = new PembimbingAkademik;


        if (!$model) {
            return show_error('Data tidak ditemukan', 404);
        }

        $this->layout->render('form', [
            'model' => $model,
            'model_agama' => $model_agama,
            'model_pendidikan' => $model_pendidikan,
            'model_pekerjaan' => $model_pekerjaan,
            'model_pa' => $model_pa,
            'readonly' => true,
        ]);
    }

    public function actionHapus($id)
    {
        $model = $this->Mahasiswa->findOne($id);

        if (!$model) {
            return show_error('Data tidak ditemukan', 404);
        }

        if ($model->delete($id)) {
            $this->session->set_flashdata('success', 'Hapus data mahasiswa berhasil');

             return redirect('/mahasiswa','refresh');
        } else {
            $this->session->set_flashdata('danger', 'Hapus data mahasiswa gagal');
        }
    }

}

/* End of file MahasiswaController.php */
/* Location: ./application/controllers/MahasiswaController.php */