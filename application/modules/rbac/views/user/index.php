<section class="content-header">
    <h1>User</h1>
</section>

<div class="row">
    <div class="col-md-12">
        <div class="card light bordered">
            <div class="card-body">
            	<div class="table-toolbar">
                    <div class="row">
                        <div class="col-md-6"></div>
                        <div class="col-md-6">
                            <div class="btn-group float-right mb-3">
                                <a href="<?= site_url('/rbac/user/create') ?>" class="btn sbold btn-primary"> Tambah
                                    <i class="fa fa-plus"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            	<table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table-user">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Status User</th>
                            <th>Tanggal Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade draggable-modal" id="modal-detail" role="basic" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
            	<?= form_open('', ['id' => 'form-detail']); ?>

                    <div class="form-group">
                        <?= form_label('Nama Karyawan', 'id_nama_depan'); ?>
                        <div class="row">
                            <div class="col-md-4">
                                <?= form_input('UserDetail[nama_depan]', '', [
                                    'class' => 'form-control',
                                    'id' => 'id_nama_depan',
                                    'placeholder' => 'Nama Depan',
                                    'required' => true,
                                ]); ?>
                            </div>
                            <div class="col-md-4">
                                <?= form_input('UserDetail[nama_tengah]', '', [
                                    'class' => 'form-control',
                                    'id' => 'id_nama_tengah',
                                    'placeholder' => 'Nama Tengah',
                                ]); ?>
                            </div>
                            <div class="col-md-4">
                                <?= form_input('UserDetail[nama_belakang]', '', [
                                    'class' => 'form-control',
                                    'id' => 'id_nama_belakang',
                                    'placeholder' => 'Nama Belakang',
                                ]); ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <?= form_label('Nomor Induk Karyawan', 'id_nik'); ?>
                        <?= form_input('UserDetail[nik]', '', [
                            'class' => 'form-control',
                            'id' => 'id_nik'
                        ]); ?>
                    </div>

            		<div class="form-group">
            			<?= form_label('Tanggal Gabung', 'id_tanggal_gabung'); ?>
            			<?= form_input('UserDetail[tanggal_gabung]', '', [
            				'class' => 'form-control',
            				'id' => 'id_tanggal_gabung',
            				'placeholder' => 'dd-mm-yyyy',
            			]); ?>
            		</div>

            		<div class="form-group">
            			<?= form_label('Tanggal Selesai', 'id_tanggal_selesai'); ?>
            			<?= form_input('UserDetail[tanggal_selesai]', '', [
            				'class' => 'form-control',
            				'id' => 'id_tanggal_selesai',
            				'placeholder' => 'dd-mm-yyyy',
            			]); ?>
            		</div>

            		<div class="form-group">
            			<?= form_label('Unit', 'id_unit'); ?>
            			<?= form_dropdown('UserDetail[unit_id]', null, '', [
            				'class' => 'form-control',
                            'required' => true,
            				'id' => 'id_unit'
            			]); ?>
            		</div>

            		<div class="form-group">
            			<?= form_label('Departemen', 'id_department'); ?>
            			<?= form_dropdown('UserDetail[department_id]', ['' => '- Pilih Department -'], '', [
            				'class' => 'form-control',
                            'required' => true,
            				'id' => 'id_department'
            			]); ?>
            		</div>

                    <div class="form-group">
                        <?= form_label('Jenis Grading', 'id_jenis_grading'); ?>
                        <?= form_dropdown('UserDetail[jenis_grading]', Gradingtype::listGrading(true), '', [
                            'class' => 'form-control',
                            'id' => 'id_jenis_grading'
                        ]); ?>
                    </div>

                    <div class="row">
                        <div class="col-lg-7">
                            <div class="form-group">
                                <?= form_label('Golongan', 'id_grade'); ?>
                                <?= form_dropdown('UserDetail[grade_id]', Jabatan::listGrade(true), '', [
                                    'class' => 'form-control',
                                    'id' => 'id_grade'
                                ]); ?>
                            </div>
                        </div>

                        <div class="col-lg-5">
                            <div class="form-group">
                                <?= form_label('Sub Golongan', 'id_golongan'); ?>
                                <?= form_dropdown('UserDetail[golongan]', ['' => '- Pilih Golongan -'], '', [
                                    'class' => 'form-control',
                                    'id' => 'id_golongan'
                                ]); ?>
                            </div>
                        </div>
                    </div>

            		<div class="form-group">
                        <?= form_label('Kelas Jabatan', 'id_kelas_jabatan'); ?>
                        <?= form_input('kelas_jabatan', '', [
                            'class' => 'form-control',
                            'id' => 'id_kelas_jabatan',
                            'readonly' => true,
                        ]); ?>
                    </div>

                    <div class="form-group">
                        <?= form_label('Kelompok Grading', 'id_grading_type_id'); ?>
                        <?= form_dropdown('UserDetail[grading_type_id]', ['' => '- Pilih Kelompok -'], '', [
                            'class' => 'form-control',
                            'id' => 'id_grading_type_id'
                        ]); ?>
                    </div>

                    <div class="form-group">
                        <?= form_label('Designation', 'id_designation'); ?>
                        <?= form_dropdown('UserDetail[designation_id]', ['' => '- Pilih Designation -'], '', [
                            'class' => 'form-control',
                            'id' => 'id_designation'
                        ]); ?>
                    </div>

                    <div class="form-group">
                        <?= form_label('Jabatan', 'id_job_title'); ?>
                        <?= form_input('UserDetail[job_title]', '', [
                            'class' => 'form-control',
                            'id' => 'id_job_title'
                        ]); ?>
                    </div>

            		<div class="form-group">
            			<?= form_label('Status Kerja', 'id_status'); ?>
            			<?= form_dropdown('UserDetail[status_id]', $statuses, '', [
            				'class' => 'form-control',
                            'required' => true,
            				'id' => 'id_status'
            			]); ?>
            		</div>

                    <div class="form-group">
                        <?= form_label('Atasan Langsung', 'id_atasan'); ?>
                        <?= form_dropdown('UserDetail[atasan_id]', [], '', [
                            'class' => 'form-control select2',
                            'id' => 'id_atasan'
                        ]); ?>
                    </div>

            	<?= form_close(); ?>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Tutup</button>
                <button type="submit" form="form-detail" id="btn-save" class="btn btn-success">
                	<span class="ladda-label">Simpan</span></button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
