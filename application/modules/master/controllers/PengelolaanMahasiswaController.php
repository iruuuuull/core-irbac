<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PengelolaanMahasiswaController extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
		$this->load->model([
			'transaksi/Students',
            'master/Agama',
            'master/Pendidikan',
            'master/Pekerjaan',
            'master/Pembimbingakademik',
            'master/Provinsi',
            'master/Kabupaten',
            'master/Kecamatan',
            'master/Kelurahan',
            'master/Kelurahan',
            'master/UnitCampus',
            'master/Product',
            'master/Jenisdokumenmahasiswa',
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

		$model = new Students;
		$list = $model->get_datatables();
        $data = [];
        $no = $_POST['start'];

        foreach ($list as $field) {
            $getProduct = $this->Product->get(['product_id' => $field->product_id]);
            $getUnitParentId = $this->UnitCampus->get(['unit_id' => $field->unit_parent_id]);
            $getUnitId = $this->UnitCampus->get(['unit_id' => $field->unit_id]);
            $tingkat = diffYears(date('Y'),substr($field->student_ta, 0,4));

            if(!$field->student_photo == null){
                $image = base_url().substr($field->student_photo, 2);
                $gambar = "<div class='image-student'><img style='height:55px;' src=" . $image . "></img></div>";
            }else{
                $gambar = "<div class='image-student'><img src=". base_url('./web/assets/lp3i/img/default-user.jpg') ." ></img></div>";
            }

            $no++;
            $row = [];
            $row[] = $no;
            $row[] = '<div class="d-flex grid-gap-1 flex-change">'. $gambar .'<div class="d-flex flex-column">'. $field->student_name.'</br>'.$field->student_nim.'</div></div>';
            $row[] = '<div class="d-flex flex-column campus"><div class="text-primary">'.$getUnitParentId->unit_name.'</div><div class="text-secondary">'.$getUnitId->unit_name.'</div></div>';
            $row[] = '<div class="d-flex flex-column"><span class="years">'.$field->student_ta.'</span><span class="angkatan"> Tingkat '.$tingkat.'</span></div>';
            $row[] = $getProduct->product_name;
            $row[] = '<span class="btn-status btn-primary">'. $field->getStatusValue() .'</span>';
            $row[] = "
            	<div class='text-center'>
            		". anchor("/pengelolaan-mahasiswa/detail/{$field->id}", "<i class='fa fa-eye'></i>", ['class' => 'text-secondary']) ."
            		". anchor("/pengelolaan-mahasiswa/update/{$field->id}", "<i class='fa fa-pencil-alt'></i>", ['class' => 'text-primary']) ."
                    ". $this->html->a(
                            "<i class='fa fa-trash-alt'></i>", 
                            "/pengelolaan-mahasiswa/hapus/{$field->id}", 
                            [
                                'class' => 'text-danger',
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
            "recordsTotal" => $model->count_all(null,'student_id'),
            "recordsFiltered" => $model->count_filtered(null,'student_id'),
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

        $model = new Students;
        $getLastId = $model->getLastId();
        $listInstitusi = $this->UnitCampus->getListInstitusi(true);
        $listAgama = $this->Agama->getListAgama(true,true);
        $listPendidikan = $this->Pendidikan->getListPendidikan(true,true);
        $listPekerjaan = $this->Pekerjaan->getListPekerjaan(true,true);
        $listPembimbingAkademik = $this->Pembimbingakademik->getListPa(true,true);
        $listProvinsi = $this->Provinsi->getListProvinsi(true,true);
        $listDokument = $this->Jenisdokumenmahasiswa->getAll();
        $Provinsi = $this->Provinsi->getAll();

        if ($post = $this->input->post('Students', true)) {
                $post['student_date_birth'] = $post['student_date_birth'] ? date('Y-m-d', strtotime($post['student_date_birth'])) : '';
                $post['student_tgl_lahir_ayah'] = $post['student_tgl_lahir_ayah'] ? date('Y-m-d', strtotime($post['student_tgl_lahir_ayah'])) : '';
                $post['student_tgl_lahir_ibu'] = $post['student_tgl_lahir_ibu'] ? date('Y-m-d', strtotime($post['student_tgl_lahir_ibu'])) : '';

                $upload = true;
                $this->load->library('upload');
                if ($post_dokumen = $this->input->post('Document')) {
                    foreach ($post_dokumen as $key => $value) {
                        if (!empty($_FILES[$key]['name'])) {
                            $path = "./web/uploads/dokumen_mahasiswa/{$key}";

                            if (!file_exists($path)) {
                                mkdir($path, 0777, true);
                            }

                            $config['upload_path'] = $path;
                            $config['allowed_types'] = 'jpg|png|jpeg';
                            $config['max_size'] = '1000';
                            $config['file_name'] = $key.'_'.$post['student_nim'];
                            $config['overwrite'] = true;
                            
                            $this->upload->initialize($config);

                            if ($this->upload->do_upload($key)){
                                if($key == 'student_photo'){
                                     # RESIZE IMAGE WITH JCROP OPTIONS
                                    $uploaded_file = $this->upload->data();
                                    unset($config);
                                       //Compress Image
                                    $config['image_library'] = 'gd2';
                                    $config['source_image'] = $path .'/'. $uploaded_file['file_name'];
                                    $config['quality'] = "100%";
                                    $config['maintain_ratio'] = TRUE; 
                                    $config['width'] = 215;
                                    $config['height'] = 200;
                                    $config['new_image'] = $path .'/'. $uploaded_file['file_name'];
                                    $this->load->library('image_lib', $config);
                                    $this->image_lib->resize();
                                }
                                
                                $post[$key] = $path .'/'. $this->upload->data("file_name");
                                $upload = true;
                            } else {

                                $this->session->set_flashdata('danger', $this->upload->display_errors()."<br/>
                                    ". $this->helpers->valueErrors($model->getErrors(), true) ."
                                    ");

                                $upload = false;

                            }
                        }
                     }
                }

            
                if ($model->insert($post)) {
                    $this->session->set_flashdata('success', 'Simpan data mahasiswa berhasil');

                     return redirect('/pengelolaan-mahasiswa','refresh');
                } else {
                    $this->session->set_flashdata('danger', "
                        Simpan data mahasiswa gagal: <br/>
                        ". $this->helpers->valueErrors($model->getErrors(), true) ."
                    ");
                }
        }

        $this->layout->render('form', [
            'model' => $model,
            'Provinsi' => $Provinsi,
            'LastId' => $getLastId->student_id ?? 0,
            'listDokument' => $listDokument,
            'listInstitusi' => $listInstitusi,
            'listAgama' => $listAgama,
            'listPendidikan' => $listPendidikan,
            'listPekerjaan' => $listPekerjaan,
            'listPembimbingAkademik' => $listPembimbingAkademik,
            'listProvinsi' => $listProvinsi,
        ]);
    }

    public function actionUpdate($id)
    {
        $this->layout->title = 'Student Management';
        $this->layout->view_js = 'ext/tambah_js';
        $this->layout->view_css = 'ext/tambah_css';

        $model = $this->Students->findOne($id);
        $getLastId = $model->getLastId();
        $listInstitusi = $this->UnitCampus->getListInstitusi(true);
        $listAgama = $this->Agama->getListAgama(true,true);
        $listPendidikan = $this->Pendidikan->getListPendidikan(true,true);
        $listPekerjaan = $this->Pekerjaan->getListPekerjaan(true,true);
        $listPembimbingAkademik = $this->Pembimbingakademik->getListPa(true,true);
        $listProvinsi = $this->Provinsi->getListProvinsi(true,true);
        $listDokument = $this->Jenisdokumenmahasiswa->getAll();
        $Provinsi = $this->Provinsi->getAll();

        if ($post = $this->input->post('Students', true)) {
            $post['student_date_birth'] = $post['student_date_birth'] ? date('Y-m-d', strtotime($post['student_date_birth'])) : '';
            $post['student_tgl_lahir_ayah'] = $post['student_tgl_lahir_ayah'] ? date('Y-m-d', strtotime($post['student_tgl_lahir_ayah'])) : '';
            $post['student_tgl_lahir_ibu'] = $post['student_tgl_lahir_ibu'] ? date('Y-m-d', strtotime($post['student_tgl_lahir_ibu'])) : '';

            $upload = true;
            $this->load->library('upload');
            if ($post_dokumen = $this->input->post('Document')) {
                foreach ($post_dokumen as $key => $value) {
                    if (!empty($_FILES[$key]['name'])) {
                        $path = "./web/uploads/dokumen_mahasiswa/{$key}";

                        if (!file_exists($path)) {
                            mkdir($path, 0777, true);
                        }

                        $config['upload_path'] = $path;
                        $config['allowed_types'] = 'jpg|png|jpeg';
                        $config['max_size'] = '1000';
                        $config['file_name'] = $key.'_'.$post['student_nim'];
                        $config['overwrite'] = true;

                        if (file_exists($this->upload->data("file_name"))) {
                            unlink($this->upload->data("file_name"));
                        }

                        $this->upload->initialize($config);

                            if ($this->upload->do_upload($key)){
                                if($key == 'student_photo'){
                                     # RESIZE IMAGE WITH JCROP OPTIONS
                                    $uploaded_file = $this->upload->data();
                                    unset($config);
                                       //Compress Image
                                    $config['image_library'] = 'gd2';
                                    $config['source_image'] = $path .'/'. $uploaded_file['file_name'];
                                    $config['quality'] = "100%";
                                    $config['maintain_ratio'] = TRUE; 
                                    $config['width'] = 215;
                                    $config['height'] = 200;
                                    $config['new_image'] = $path .'/'. $uploaded_file['file_name'];
                                    $this->load->library('image_lib', $config);
                                    $this->image_lib->resize();
                                }
                                
                                $post[$key] = $path .'/'. $this->upload->data("file_name");
                                $upload = true;
                            } else {

                                $this->session->set_flashdata('danger', $this->upload->display_errors()."<br/>
                                    ". $this->helpers->valueErrors($model->getErrors(), true) ."
                                    ");

                                $upload = false;

                            }
                    }
                }
            }

        
            if ($model->update($post,$id)) {
                $this->session->set_flashdata('success', 'Simpan data mahasiswa berhasil');

                 return redirect('/pengelolaan-mahasiswa','refresh');
            } else {
                $this->session->set_flashdata('danger', "
                    Simpan data mahasiswa gagal: <br/>
                    ". $this->helpers->valueErrors($model->getErrors(), true) ."
                ");
            }
        }

        $this->layout->render('form', [
            'id' => $id,
            'model' => $model,
            'Provinsi' => $Provinsi,
            'LastId' => $getLastId->student_id,
            'listDokument' => $listDokument,
            'listInstitusi' => $listInstitusi,
            'listAgama' => $listAgama,
            'listPendidikan' => $listPendidikan,
            'listPekerjaan' => $listPekerjaan,
            'listPembimbingAkademik' => $listPembimbingAkademik,
            'listProvinsi' => $listProvinsi,
        ]);
    }

    public function actionDetail($id)
    {
        $this->layout->title = 'Student Management';
        $this->layout->view_js = 'ext/tambah_js';
        $this->layout->view_css = 'ext/tambah_css';

        $model = $this->Students->findOne($id);
         $getLastId = $model->getLastId();
        $listInstitusi = $this->UnitCampus->getListInstitusi(true);
        $listAgama = $this->Agama->getListAgama(true,true);
        $listPendidikan = $this->Pendidikan->getListPendidikan(true,true);
        $listPekerjaan = $this->Pekerjaan->getListPekerjaan(true,true);
        $listPembimbingAkademik = $this->Pembimbingakademik->getListPa(true,true);
        $listProvinsi = $this->Provinsi->getListProvinsi(true,true);
        $listDokument = $this->Jenisdokumenmahasiswa->getAll();


        if (!$model) {
            return show_error('Data tidak ditemukan', 404);
        }

        $this->layout->render('form', [
            'id' => $id,
            'model' => $model,
            'LastId' => $getLastId->student_id,
            'listDokument' => $listDokument,
            'listInstitusi' => $listInstitusi,
            'listAgama' => $listAgama,
            'listPendidikan' => $listPendidikan,
            'listPekerjaan' => $listPekerjaan,
            'listPembimbingAkademik' => $listPembimbingAkademik,
            'listProvinsi' => $listProvinsi,
            'readonly' => true,
        ]);
    }

    public function actionHapus($id)
    {
        $model = $this->Students->findOne($id);

        if (!$model) {
            return show_error('Data tidak ditemukan', 404);
        }

        if ($model->delete($id)) {
            $this->session->set_flashdata('success', 'Hapus data mahasiswa berhasil');

             return redirect('/pengelolaan-mahasiswa','refresh');
        } else {
            $this->session->set_flashdata('danger', 'Hapus data mahasiswa gagal');
        }
    }

    public function actionGetKabupaten($id_provinsi){

        $provinsi = $this->Provinsi->get($id_provinsi);
        $kabupatens = [];

        if($provinsi){ 
            $kabupaten = $this->Kabupaten->getAll(['provinsi_id' => $provinsi->id]);
            $kabupatens = $this->Kabupaten->getListKabupaten($kabupaten);
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200) // Return status
            ->set_output(json_encode($kabupatens));
    }

     public function actionGetKecamatan($id_kabupaten){

        $kabupaten = $this->Kabupaten->get($id_kabupaten);
        $kecamatans = [];

        if($kabupaten){ 
            $kecamatan = $this->Kecamatan->getAll(['kabupaten_id' => $kabupaten->id]);
            $kecamatans = $this->Kecamatan->getListKecamatan($kecamatan);
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200) // Return status
            ->set_output(json_encode($kecamatans));
    }

     public function actionGetKelurahan($id_kecamatan){

        $kecamatan = $this->Kecamatan->get($id_kecamatan);
        $kelurahans = [];

        if($kecamatan){ 
            $kelurahan = $this->Kelurahan->getAll(['kecamatan_id' => $kecamatan->id]);
            $kelurahans = $this->Kelurahan->getListKelurahan($kelurahan);
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200) // Return status
            ->set_output(json_encode($kelurahans));
    }

    public function actionGetKampus($id_parent_unit){

        $allkampus = [];

        if($id_parent_unit){ 
            $kampus = $this->UnitCampus->getAll(['unit_parent_id' => $id_parent_unit]);
            $allkampus = $this->UnitCampus->getListAllKampus($kampus);
        }

        return $this->output
        ->set_content_type('application/json')
            ->set_status_header(200) // Return status
            ->set_output(json_encode($allkampus));
        }

     public function actionGetProductByUnit($id_unit){

        $units = $this->UnitCampus->get(['unit_id' => $id_unit]);
        $products = [];

        if($units){ 
            $product = $this->Product->getAll(['unit_id' => $units->unit_id]);
            $products = $this->Product->getListProductByUnit($product);
        }

        return $this->output
        ->set_content_type('application/json')
            ->set_status_header(200) // Return status
            ->set_output(json_encode($products));
        }

       
}

/* End of file MahasiswaController.php */
/* Location: ./application/controllers/MahasiswaController.php */