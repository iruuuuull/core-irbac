     <?php
    $readonly = isset($readonly) ? $readonly : false;
?>       
        <?= form_open('', ['enctype' => 'multipart/form-data']); ?>
            <div class="py-1">
                <div class="card">
                    <div class="d-flex flex-space-between align-center flex-change">
                        <div class="title-form">
                            <h6>Form Tambah Baru Mahasiswa</h6>
                        </div>
                        <div class="d-flex grid-gap-1">
                            <?= anchor('/pengelolaan-mahasiswa', 'Kembali', ['class' => 'btn btn-link btn-danger px-20']); ?>

                            <?php if ($readonly !== true): ?>
                                <?= $this->html->submitButton('Save', [
                                    'class' => 'btn btn-link btn-secondary  px-20'
                                ]) ?>
                            <?php endif ?>
<!--                             <button class="btn btn-link btn-danger px-20">Back</button>
                            <button class="btn btn-link btn-secondary  px-20">Save</button> -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-tabs px-0">
                <!-- Nav pills -->
                <ul class="nav nav-pills" id="tabs">
                    <li class="nav-item">
                        <a class="nav-link" id="tab1">
                            <div class="wizard-wrapper">
                                <div class="wizard-number">1</div>
                                <div class="wizard-label">
                                    <span class="wizard-title">Profile</span>
                                    <span class="wizard-desc">Biodata Mahasiswa</span>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab2">
                            <div class="wizard-wrapper">
                                <div class="wizard-number">2</div>
                                <div class="wizard-label">
                                    <span class="wizard-title">Kemahasiswaan</span>
                                    <span class="wizard-desc">Data Kemahasiswaan</span>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab3">
                            <div class="wizard-wrapper">
                                <div class="wizard-number">3</div>
                                <div class="wizard-label">
                                    <span class="wizard-title">Dokumen</span>
                                    <span class="wizard-desc">Dokumen Mahasiswa</span>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab4">
                            <div class="wizard-wrapper">
                                <div class="wizard-number">4</div>
                                <div class="wizard-label">
                                    <span class="wizard-title">Orang Tua</span>
                                    <span class="wizard-desc">Data Orang Tua</span>
                                </div>
                            </div>
                        </a>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                <input type="text" hidden readonly id="student_id" value="<?= $id ?? '' ?>">

                    <!-- Tab1 biodata mahasiswa start -->

                    <div class="container tab-pane active" id="tab1C">
                        <div class="d-flex flex-change">
                            <div class="form-mr-2">
                                <div class="form-inputs">
                                    <label>ID</label>
                                    <?= form_input('Students[student_id]', $model->id ?? $LastId+1 , [
                                        'class' => 'form-control',
                                        'placeholder' => 'ID Mahasiswa',
                                        'onkeypress' => 'return isNumberKey(event)',
                                        'required' => true,
                                        'disabled' => $readonly
                                    ]); ?>
                                    <!--  <?= $this->html->activeTextInput($model, 'student_nik', [
                                        'class' => 'form-control',
                                        'placeholder' => 'ID Mahasiswa',
                                        // 'readonly' => true,
                                        'onkeypress' => 'return isNumberKey(event)',
                                        'disabled' => $readonly
                                    ]) ?> -->
                                </div>
                                <div class="form-inputs">
                                    <label>NIK</label>
                                    <?= $this->html->activeTextInput($model, 'student_nik', [
                                        'class' => 'form-control',
                                        'placeholder' => 'NIK',
                                        'onkeypress' => 'return isNumberKey(event)',
                                        'disabled' => $readonly
                                    ]) ?>
                                </div>
                                <div class="form-inputs">
                                    <label>NIM</label>
                                    <?= $this->html->activeTextInput($model, 'student_nim', [
                                        'class' => 'form-control',
                                        'placeholder' => 'NIM',
                                        'onkeypress' => 'return isNumberKey(event)',
                                        'disabled' => $readonly
                                    ]) ?>
                                </div>
                                <div class="form-inputs">
                                    <label>Nama Lengkap</label>
                                    <?= $this->html->activeTextInput($model, 'student_name', [
                                        'class' => 'form-control',
                                        'disabled' => $readonly,
                                        'placeholder' => 'Nama Lengkap',
                                    ]) ?>
                                </div>
                                <div class="form-inputs">
                                    <label>Tanggal Lahir</label>
                                    <?= $this->html->activeTextInput($model, 'student_date_birth', [
                                        'class' => 'form-control datepicker',
                                        'disabled' => $readonly,
                                        'placeholder' => 'Tanggal Lahir',
                                    ]) ?>
                                </div>
                                <div class="form-inputs">
                                    <label>Tempat Lahir</label>
                                    <?= $this->html->activeTextInput($model, 'student_place_birth', [
                                        'class' => 'form-control',
                                        'disabled' => $readonly,
                                        'placeholder' => 'Tempat Lahir',
                                    ]) ?>
                                </div>
                                <div class="form-inputs">
                                    <label>Jenis Kelamin</label>
                                    <div class="form-group">
                                        <label class="radio-button">
                                            <?= form_radio('Students[student_sex]','Pria',
                                            ($model->student_sex ?? '' == 'Pria' ? TRUE : FALSE));?>
                                            <span class="checkmark"></span>
                                            <?= form_label('Laki-Laki') ?>
                                        </label>
                                        <label class="radio-button">
                                            <?= form_radio('Students[student_sex]','Wanita',
                                            ($model->student_sex ?? '' == 'Wanita' ? TRUE : FALSE),['style' => 'margin-left:20px;']);?>
                                            <span class="checkmark"></span>
                                            <?= form_label('Perempuan') ?>
                                        </label>

                                      

                                    </div>
                                </div>
                                <div class="form-inputs">
                                    <label>Agama</label>
                                    <?= $this->html->activeDropDownList($model, 'student_agama', $listAgama, [
                                        'class' => 'form-control',
                                        'disabled' => $readonly,
                                    ]) ?>
                                </div>
                                <div class="form-inputs">
                                    <label for="email">Email</label>
                                     <?= $this->html->activeTextInput($model, 'student_email', [
                                        'class' => 'form-control',
                                        'disabled' => $readonly,
                                        'placeholder' => 'Email',
                                    ]) ?>
                                </div>
                                <div class="form-inputs">
                                    <label for="email">No.Handphone</label>
                                    <?= $this->html->activeTextInput($model, 'student_phone', [
                                        'class' => 'form-control',
                                        'placeholder' => 'No. Handphone',
                                        'onkeypress' => 'return isNumberKey(event)',
                                        'disabled' => $readonly
                                    ]) ?>
                                </div>
                                <div class="form-inputs">
                                    <label for="email">Email Orang tua</label>
                                     <?= $this->html->activeTextInput($model, 'student_parent_email', [
                                        'class' => 'form-control',
                                        'disabled' => $readonly,
                                        'placeholder' => 'Email Orang Tua',
                                    ]) ?>
                                </div>
                                <div class="form-inputs">
                                    <label for="email">No.Handphone Orang tua</label>
                                   <?= $this->html->activeTextInput($model, 'student_parent_phone', [
                                        'class' => 'form-control',
                                        'disabled' => $readonly,
                                        'placeholder' => 'No. Handphone Orang tua',
                                    ]) ?>
                                </div>
                            </div>
                            <div class="form-img">
                                <input type="text" hidden readonly name='Document[student_photo]'>
                                <?php if(!$model->student_photo == null) : 
                                     $image = base_url().substr($model->student_photo, 2);
                                ?>
                                   <img src="<?= $image ?>"></img>
                                <?php else: ?>
                                    <img src="<?php echo base_url('./web/assets/lp3i/img/default-user.jpg') ?>" alt="">
                                <?php endif; ?>
                                <br>
                                <?= form_upload('student_photo', '', [
                                            // 'class' => 'form-control',
                                    'id' => 'id_image_mahasiswa',
                                    // 'disabled' => $readonly,
                                    'accept' => 'image/*'
                                ]); ?>
                            </div>
                        </div>

                    </div>

                    <!-- tab1 biodata mahasiswa end -->

                    <!-- tab2 data mahasiswa start -->

                    <div class="container tab-pane" id="tab2C">
                        <div class="form-inputs">
                            <label>Institusi</label>
                            <?= $this->html->activeDropDownList($model, 'unit_parent_id', $listInstitusi, [
                                'class' => 'form-control id_institusi',
                                'disabled' => $readonly,
                            ]) ?>
                        </div>
                        <div class="form-inputs">
                            <label>Kampus</label>
                            <?= $this->html->activeDropDownList($model, 'unit_id', ['0' => '- Pilih Kampus -'], [
                                'exist_unit_id' => $model->unit_id ?? '',
                                'class' => 'form-control id_kampus',
                                'tesajalagi' => $model->product_id ?? '',
                                'disabled' => $readonly,
                            ]) ?>
                        </div>
                        <div class="form-inputs">
                            <label>Jurusan</label>
                             <?= $this->html->activeDropDownList($model, 'product_id', ['0' => '- Pilih Jurusan -'], [
                                'exist_product_id' => $model->product_id ?? '',
                                'class' => 'form-control id_product',
                                'disabled' => $readonly,
                            ]) ?>
                        </div>
                        <div class="form-inputs">
                            <label>Tahun Angkatan</label>
                            <?= $this->html->activeTextInput($model, 'student_ta', [
                                'class' => 'form-control',
                                'disabled' => $readonly,
                                'placeholder' => 'Tahun Angkatan',
                            ]) ?>
                        </div>
                        <div class="form-inputs">
                            <label>Alamat</label>
                             <?= $this->html->activeTextarea($model, 'student_alamat', [
                                'class' => 'form-control',
                                'disabled' => $readonly,
                                'style' => 'resize:none;height:100px',
                            ]) ?>
                        </div>
                        <div class="form-inputs">
                            <label>Provinsi</label>
                            <?= $this->html->activeDropDownList($model, 'student_provinsi', $listProvinsi , [
                                'class' => 'form-control id_provinsi',
                                'disabled' => $readonly,
                            ]) ?>
                        </div>
                        <div class="form-inputs">
                            <label>Kabupaten</label>
                            <?= $this->html->activeDropDownList($model, 'student_kabupaten',['0' => '- Pilih Kabupaten -'], [
                                'exist_student_kabupaten' => $model->student_kabupaten ?? '',
                                'class' => 'form-control id_kabupaten',
                                'disabled' => $readonly,
                            ]) ?>
                        </div>
                        <div class="form-inputs">
                            <label>Kecamatan</label>
                            <?= $this->html->activeDropDownList($model, 'student_kecamatan',['0' => '- Pilih Kecamatan -'], [
                                'exist_student_kecamatan' => $model->student_kecamatan ?? '',
                                'class' => 'form-control id_kecamatan',
                                'disabled' => $readonly,
                            ]) ?>
                        </div>
                        <div class="form-inputs">
                            <label>Kelurahan</label>
                            <?= $this->html->activeDropDownList($model, 'student_kota',['0' => '- Pilih Kelurahan -'], [
                                'exist_student_kelurahan' => $model->student_kota ?? '',
                                'class' => 'form-control id_kelurahan',
                                'disabled' => $readonly,
                            ]) ?>
                        </div>
                        <div class="form-inputs">
                            <label>Kodepos</label>
                            <?= $this->html->activeTextInput($model, 'student_kodepos', [
                                'class' => 'form-control',
                                'disabled' => $readonly,
                                'placeholder' => 'Kodepos',
                            ]) ?>
                        </div>
                        <div class="form-inputs">
                            <label>Pembimbing Akademik</label>
                            <?= $this->html->activeDropDownList($model, 'pa_id', $listPembimbingAkademik, [
                                'class' => 'form-control',
                                'disabled' => $readonly,
                            ]) ?>
                        </div>
                        <div class="form-inputs">
                            <label>Status</label>
                            <?= $this->html->activeDropDownList($model, 'student_status', Students::getListStatusMahasiswa(true), [
                                'class' => 'form-control',
                                'disabled' => $readonly,
                            ]) ?>
                        </div>
                    </div>
                    <!-- tab2 data mahasiswa end -->

                    <!-- tab3 dokumen mahasiswa start -->
                    <div class="container tab-pane" id="tab3C">
                        <?php foreach ($listDokument  as $key => $value) :
                            $path_dokument = def($model, $value->kode_dokumen);
                         ?>
                            <div class="form-inputs doc-grid-custom">
                                <label><?= $value->label ?></label>
                                <input type="text" hidden readonly name='Document[<?= $value->kode_dokumen ?>]'>
                                <?= form_upload($value->kode_dokumen, '', [
                                            // 'class' => 'form-control',
                                    'id' => 'id_image_ktp',
                                    // 'disabled' => $readonly,
                                    'accept' => 'image/*'
                                ]); ?>

                                <?php if($path_dokument){
                                        $onclick = "onclick=\" return previewPDF('". base_url($path_dokument) ."');\")";
                                        $label_view = "View";
                                    }else{
                                        $onclick = "";
                                        $label_view = "No file";
                                    }
                                ?>

                                <a href="javascript:;" <?= $onclick  ?> class="text-primary d-flex flex-center view-docs"> <?= $label_view ?> </a>
                            </div> 
                       <?php endforeach ?>

                        <!-- <div class="form-inputs doc-grid-custom">
                            <label>KTP</label>
                            <input type="text" hidden readonly name='Document[student_ktp]'>
                            <?= form_upload('student_ktp', '', [
                                            // 'class' => 'form-control',
                                    'id' => 'id_image_ktp',
                                    // 'disabled' => $readonly,
                                    'accept' => 'image/*,application/pdf'
                                ]); ?>


                            <a href="javascript:;" onclick="return previewPDF('<?= base_url($model->student_ktp) ?>')" class="text-primary d-flex flex-center view-docs"> View</a>
                        </div> 
                         <div class="form-inputs doc-grid-custom">
                            <label>Kartu Keluarga</label>
                             <input type="text" hidden readonly name='Document[student_kartu_keluarga]'>
                            <?= form_upload('student_kartu_keluarga', '', [
                                            // 'class' => 'form-control',
                                'id' => 'id_image_kartukel',
                                    // 'disabled' => $readonly,
                                'accept' => 'image/*,application/pdf'
                            ]); ?>
                            <a href="javascript:;" onclick="return previewPDF('<?= base_url($model->student_kartu_keluarga) ?>')" class="text-primary d-flex flex-center view-docs"> View</a>
                        </div>
                        <div class="form-inputs doc-grid-custom">
                            <label>Akte Kelahiran</label>
                            <input type="text" hidden readonly name='Document[student_akte_kelahiran]'>
                            <?= form_upload('student_akte_kelahiran', '', [
                                            // 'class' => 'form-control',
                                'id' => 'id_image_aktelahir',
                                    // 'disabled' => $readonly,
                                'accept' => 'image/*,application/pdf'
                            ]); ?>
                              <a href="javascript:;" onclick="return previewPDF('<?= base_url($model->student_akte_kelahiran) ?>')" class="text-primary d-flex flex-center view-docs"> View</a>
                        </div>
                        <div class="form-inputs doc-grid-custom">
                            <label>Ijazah SMU</label>
                            <input type="text" hidden readonly name='Document[student_ijazah_smu]'>
                            <?= form_upload('student_ijazah_smu', '', [
                                            // 'class' => 'form-control',
                                'id' => 'id_image_ijazah',
                                    // 'disabled' => $readonly,
                                'accept' => 'image/*,application/pdf'
                            ]); ?>
                            <a href="javascript:;" onclick="return previewPDF('<?= base_url($model->student_ijazah_smu) ?>')" class="text-primary d-flex flex-center view-docs"> View</a>
                        </div>
                        <div class="form-inputs doc-grid-custom">
                            <label>Kartu Mahasiswa</label>
                            <input type="text" hidden readonly name='Document[student_idcard]'>
                            <?= form_upload('student_idcard', '', [
                                            // 'class' => 'form-control',
                                'id' => 'id_image_ktm',
                                    // 'disabled' => $readonly,
                                'accept' => 'image/*,application/pdf'
                            ]); ?>
                            <a href="javascript:;" onclick="return previewPDF('<?= base_url($model->student_idcard) ?>')" class="text-primary d-flex flex-center view-docs"> View</a>
                        </div> -->
                    </div>
                    <!-- tab3 dokumen mahasiswa end -->

                    <!-- tab4 data ortu start  -->
                    <div class="container tab-pane" id="tab4C">
                        <div class="card-header">
                            <h6>Ayah</h6>
                        </div>

                        <div class="form-inputs">
                            <label>NIK</label>
                             <?= $this->html->activeTextInput($model, 'student_nik_ayah', [
                                'class' => 'form-control',
                                'disabled' => $readonly,
                                'placeholder' => 'NIK Ayah',
                            ]) ?>
                        </div>
                        <div class="form-inputs">
                            <label>Nama</label>
                            <?= $this->html->activeTextInput($model, 'student_nama_ayah', [
                                'class' => 'form-control',
                                'disabled' => $readonly,
                                'placeholder' => 'Nama Ayah',
                            ]) ?>
                        </div>
                        <div class="form-inputs">
                            <label>Tanggal Lahir</label>
                            <?= $this->html->activeTextInput($model, 'student_tgl_lahir_ayah', [
                                'class' => 'form-control datepicker',
                                'disabled' => $readonly,
                                'placeholder' => 'Tanggal Lahir Ayah',
                            ]) ?>
                        </div>
                        <div class="form-inputs">
                            <label>Pendidikan</label>
                            <?= $this->html->activeDropDownList($model, 'student_pddkn_ayah', $listPendidikan, [
                                'class' => 'form-control',
                                'disabled' => $readonly,
                            ]) ?>
                        </div>
                        <div class="form-inputs">
                            <label>Pekerjaan</label>
                            <?= $this->html->activeDropDownList($model, 'student_pekerjaan_ayah', $listPekerjaan, [
                                'class' => 'form-control',
                                'disabled' => $readonly,
                            ]) ?>
                        </div>
                        <div class="form-inputs">
                            <label>Penghasilan</label>
                            <?= $this->html->activeTextInput($model, 'student_penghasilan_ayah', [
                                'class' => 'form-control',
                                'disabled' => $readonly,
                                'placeholder' => 'Penghasilan Ayah',
                            ]) ?>
                        </div>

                        <div class="card-header">
                            <h6>Ibu</h6>
                        </div>
                        <div class="form-inputs">
                            <label>NIK</label>
                            <?= $this->html->activeTextInput($model, 'student_nik_ibu', [
                                'class' => 'form-control',
                                'disabled' => $readonly,
                                'placeholder' => 'NIK Ibu',
                            ]) ?>
                        </div>
                        <div class="form-inputs">
                            <label>Nama</label>
                            <?= $this->html->activeTextInput($model, 'student_nama_ibu', [
                                'class' => 'form-control',
                                'disabled' => $readonly,
                                'placeholder' => 'Nama Ibu',
                            ]) ?>
                        </div>
                        <div class="form-inputs">
                            <label>Tanggal Lahir</label>
                             <?= $this->html->activeTextInput($model, 'student_tgl_lahir_ibu', [
                                'class' => 'form-control datepicker',
                                'disabled' => $readonly,
                                'placeholder' => 'Tanggal Lahir Ibu',
                            ]) ?>
                        </div>
                        <div class="form-inputs">
                            <label>Pendidikan</label>
                            <?= $this->html->activeDropDownList($model, 'student_pddkn_ibu', $listPendidikan, [
                                'class' => 'form-control',
                                'disabled' => $readonly,
                            ]) ?>
                        </div>
                        <div class="form-inputs">
                            <label>Pekerjaan</label>
                             <?= $this->html->activeDropDownList($model, 'student_pekerjaan_ibu', $listPekerjaan, [
                                'class' => 'form-control',
                                'disabled' => $readonly,
                            ]) ?>
                        </div>
                        <div class="form-inputs">
                            <label>Penghasilan</label>
                            <?= $this->html->activeTextInput($model, 'student_penghasilan_ibu', [
                                'class' => 'form-control',
                                'disabled' => $readonly,
                                'placeholder' => 'Penghasilan Ibu',
                            ]) ?>
                        </div>
                    </div>
                    <!-- tab4 data ortu end  -->
                    <?= form_close(); ?>
                </div>
            </div>
        </form>