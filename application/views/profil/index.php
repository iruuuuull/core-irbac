<link href="<?= base_url('web/assets/pages/css/profile.min.css') ?>" rel="stylesheet" type="text/css" />

<?php if (empty($user_detail)): ?>
    <div class="alert alert-info" style="margin-top: 10px">
        Mohon lengkapi <b>info personal</b> untuk melakukan pengisian detail data tab lainnya.
    </div>
<?php endif ?>
<h1 class="page-title">Profil</h1>
<input type="hidden" name="id" id="user_id" value="<?= $param ?>" readonly>
<div class="row">
    <div class="col-md-3">
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <div class="text-center">
                    <img class="profile-user-img img-fluid img-circle"
                    src="<?= $user_detail->getImage() ?>"
                    alt="User profile picture">
                </div>

                <h3 class="profile-username text-center">
                    <?php if (!empty($user_detail->nama_depan)): ?>
                        <?= $user_detail->mergeFullName() ?>
                    <?php else: ?>
                        <?= $this->session->userdata('identity')->username; ?>
                    <?php endif ?>
                </h3>

                <p class="text-muted text-center">
                    <?php if (!empty($user_detail->job_title)): ?>
                        <?= $user_detail->job_title ?>
                        <br/>
                    <?php endif ?>

                    <?php if (!empty($user_detail->jabatan)): ?>
                        <?= 'GOL '. $user_detail->jabatan->grade 
                            .', SUB GOL '. $user_detail->golongan ?>
                        <br/>
                    <?php endif ?>
                </p>

                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <?= $this->html->a('<i class=\'fa fa-key\'></i> Change Password','#', [
                            'data-toggle' => 'modal',
                            'data-backdrop' => 'static',
                            'data-keyboard' => 'false',
                            'data-target' => '#modal-change-password',
                            'visible' => (
                                ($this->session->userdata('identity')->id == $user_detail->user_id)
                                || $this->helpers->isSuperAdmin() || $this->helpers->isAdminDirektorat() || $this->helpers->isAdminCabang()
                            )
                        ]) ?>
                    </li>
                    <li class="list-group-item">
                        <?= $this->html->a('<i class=\'fa fa-camera\'></i> Change Picture','#', [
                            // 'class' => 'btn default btn-outline',
                            'data-toggle' => 'modal',
                            'data-target' => '#modal-profile',
                            'visible' => (
                                ($this->session->userdata('identity')->id == $user_detail->user_id)
                                || $this->helpers->isSuperAdmin() || $this->helpers->isAdminDirektorat() || $this->helpers->isAdminCabang()
                            )
                        ]); ?>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <!-- BEGIN PROFILE CONTENT -->
        <div class="card">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    <li class="nav-item">
                        <a class="nav-link active" href="#tab_1_1" data-toggle="tab">Info Personal</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tab_1_2" id="btn_biodata" data-toggle="tab">Info Biodata Keluarga</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tab_1_3" id="btn_pendidikan" data-toggle="tab">History Pendidikan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tab_1_4" id="btn_sertifikasi" data-toggle="tab">Training</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tab_1_5" id="btn_karir_lp3i" data-toggle="tab" >History Karir LP3I</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tab_1_6" id="btn_pengalaman" data-toggle="tab">Pengalaman Kerja</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tab_1_7" data-toggle="tab">Dokumen Pribadi</a>
                    </li>
                </ul>
            </div>

            <div class="card-body">
                <div class="tab-content">
                    <!-- PERSONAL INFO TAB -->
                    <div class="tab-pane active" id="tab_1_1">
                        <?php $this->layout->renderPartial('_form_personal', [
                            'user' => $user,
                            'user_detail' => $user_detail,
                            'param' => $param,
                        ]); ?>
                    </div>
                    <!-- END PERSONAL INFO TAB -->
                    <!-- CHANGE AVATAR TAB -->
                    <div class="tab-pane" id="tab_1_2">
                        <?php $this->layout->renderPartial('_form_biodata_keluarga', [
                            'data_pendidikan' => $data_pendidikan,
                            'data_hubungan' => $data_hubungan,
                        ]); ?>
                    </div>
                    <!-- END CHANGE AVATAR TAB -->
                    <!-- CHANGE PASSWORD TAB -->
                    <div class="tab-pane" id="tab_1_3">
                        <?php $this->layout->renderPartial('_form_history_pendidikan', [
                        ]); ?>
                    </div>
                    <!-- END CHANGE PASSWORD TAB -->
                    <!-- PRIVACY SETTINGS TAB -->
                    <div class="tab-pane" id="tab_1_4">
                        <?php $this->layout->renderPartial('_form_user_sertifikasi', [
                          
                        ]); ?>
                        </form>
                    </div>
                    <div class="tab-pane" id="tab_1_5">
                        <?php $this->layout->renderPartial('_form_karir_lp3i', [
                            // 'data_grade' => $data_grade,
                            'data_depart' => $data_depart,
                            'data_Groupunit' => $data_Groupunit,
                        ]); ?>
                    </div>
                    <!-- END PRIVACY SETTINGS TAB -->
                    <!-- PENGALAMAN TAB -->
                    <div class="tab-pane" id="tab_1_6">
                        <?php $this->layout->renderPartial('_form_pengalaman', [
                        ]); ?>
                    </div>
                    <!-- PENGALAMAN TAB -->
                    <!-- DOKUMEN TAB -->
                    <div class="tab-pane" id="tab_1_7">
                        <?php $this->layout->renderPartial('_form_dokumen', [
                            'dokumens' => $dokumens,
                            'data_lampiran' => $data_lampiran,
                            'param' => $param,
                        ]); ?>
                    </div>
                    <!-- DOKUMEN TAB -->
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modal-profile" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Ubah Foto Profil</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <?= form_open('', ['id' => 'form-photo', 'class' => 'text-center', 'enctype' => 'multipart/form-data']); ?>
                    <!-- hidden crop params -->
                    <input type="hidden" id="x1" name="x1" />
                    <input type="hidden" id="y1" name="y1" />
                    <input type="hidden" id="x2" name="x2" />
                    <input type="hidden" id="y2" name="y2" />
                    <input type="hidden" id="w" name="w" />
                    <input type="hidden" id="h" name="h" />

                    <div class="fileinput fileinput-new" data-provides="fileinput">
                        <span class="btn btn-success btn-file">
                            <span class="fileinput-new"> Pilih File </span>
                            <span class="fileinput-exists"> Ubah </span>
                            <input type="file" name="profil_pic" id="file-profile" 
                                onchange="fileSelectHandler()" required accept="image/*" data-size="500" />
                        </span>
                        <span class="fileinput-filename"> </span> &nbsp;
                        <a href="#" class="close fileinput-exists" data-dismiss="fileinput"> </a>
                    </div>
                    <div class="text-danger error"></div>

                    <img id="profile-jcrop" class="img img-responsive" style="margin: 0 auto;"></img>

                    <div class="form-inline jcrop-info" style="display: none;margin-top: 15px">
                        <label>File size</label> <input type="text" class="form-control" id="filesize" name="filesize" disabled />
                        <label>Image dimension</label> <input type="text" class="form-control" id="filedim" name="filedim" disabled />
                        <label>W x H</label> <input type="text" class="form-control" id="wh" name="wh" disabled />
                    </div>

                <?= form_close(); ?>
            </div>
            <div class="modal-footer">
                <span class="text-info pull-left">Ukuran file maksimum adalah 500KB</span>

                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-success" form="form-photo" id="btn-simpan-foto" style="display: none">Simpan</button>
            </div>
        </div>

    </div>
</div>

<div id="modal-change-password" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ubah Password</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <?= form_open("/profil/ubah-password/{$user->id}", ['id' => 'form-password']); ?>
                    <div class="form-group">
                        <?= form_label('Password Lama', 'id_old_password', ['class' => 'control-label']); ?>
                        <?= form_password('ChangePassword[old_password]', null, [
                            'class' => 'form-control',
                            'id' => 'id_old_password',
                            'required' => true,
                        ]); ?>
                    </div>

                    <div class="form-group">
                        <?= form_label('Password Baru', 'id_new_password', ['class' => 'control-label']); ?>
                        <?= form_password('ChangePassword[new_password]', null, [
                            'class' => 'form-control',
                            'id' => 'id_new_password',
                            'required' => true,
                        ]); ?>
                    </div>

                    <div class="form-group">
                        <?= form_label('Ketik Ulang Password', 'id_retype_password', ['class' => 'control-label']); ?>
                        <?= form_password('ChangePassword[retype_password]', null, [
                            'class' => 'form-control',
                            'id' => 'id_retype_password',
                            'required' => true,
                        ]); ?>
                    </div>
                <?= form_close(); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-success" form="form-password" id="btn-ubah-password">Simpan</button>
            </div>
        </div>

    </div>
</div>
