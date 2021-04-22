<div class="note note-info">
    <h4 class="block">Peraturan</h4>
    <ul>
        <li>Ukuran maksimum file yg di-unggah adalah <b>1MB</b></li>
        <li>Format file yang diterima hanya PDF, JPG, dan PNG</li>
    </ul>
</div>

<div class="table-toolbar">

	<?php if($data_login >= $data_profil): ?>

    <div class="btn-group mb-3">
    	<?= $this->html->button('Tambah <i class="fa fa-plus"></i>', [
    		'id' => 'btn-add-karir',
    		'class' => 'btn sbold btn-primary',
    		'visible' => 
    		 (
    		 	($this->session->userdata('identity')->id == def($user_detail, 'user_id'))
    		 	|| $this->helpers->isSuperAdmin() 
    		 )
    	]) ?>
    </div>

    <?php else: ?>

	<div class="btn-group mb-3">
    	<?= $this->html->button('Tambah <i class="fa fa-plus"></i>', [
    		'id' => 'btn-add-karir',
    		'class' => 'btn sbold btn-primary',
    		'visible' => 
    		 (
    		 	($this->session->userdata('identity')->id == def($user_detail, 'user_id'))
    		 	|| $this->helpers->isSuperAdmin() || $this->helpers->isAdminDirektorat() ||  $this->helpers->isAdminCabang()
    		 )
    	]) ?>
    </div>

    <?php endif ?>

</div>

<table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table-karir-lp3i">
	<thead>
		<tr>
			<th>No</th>
			<th>Unit</th>
			<th>Departemen</th>
			<th>Job Title</th>
			<th>Level</th>
			<th>Tanggal SK</th>
			<th>Tanggal Berakhir</th>
			<th>Aksi</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>

<div class="modal fade draggable-modal" id="modal-karir" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-lg">
			<!-- konten modal-->
		<div class="modal-content">
				<!-- heading modal -->
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h5 class="modal-title">Form History Karir LP3I</h5>
			</div>
				<!-- body modal -->
			<div class="modal-body">		
				<fieldset id="field-karir">	
					<?= form_open('', ['id' => 'form-add-karir']); ?>

					<div class="form-group">
						<?= form_label('Unit', 'id_unit', ['class' => 'control-label']); ?>
						<?= form_dropdown('Karir[unit_id]', $data_unit, '', [
							'class' => 'form-control',
							'id' => 'id_unit',			
						]); ?>
					</div>

					<div class="form-group">
						<?= form_label('Departement', 'id_departement', ['class' => 'control-label']); ?>
						<?= form_dropdown('Karir[department_id]',['' => '- Pilih Department -'],'', [
							'class' => 'form-control',
							'id' => 'id_departement',
						]); ?>
					</div>

					<div class="form-group">
			            <?= form_label('Jenis Grading', 'id_jenis_grading'); ?>
			            <?= form_dropdown('Karir[jenis_grading]', Gradingtype::listGrading(true), '', [
			                'class' => 'form-control',
			                'id' => 'id_jenis_grading'
			            ]); ?>
			        </div>

			        <div class="row">
			            <div class="col-lg-7">
			                <div class="form-group">
			                    <?= form_label('Golongan', 'id_grade'); ?>
			                    <?= form_dropdown('Karir[grade_id]', Jabatan::listGrade(true), '', [
			                        'class' => 'form-control',
			                        'id' => 'id_grade'
			                    ]); ?>
			                </div>
			            </div>

			            <div class="col-lg-5">
			                <div class="form-group">
			                    <?= form_label('Sub Golongan', 'id_golongan'); ?>
			                    <?= form_dropdown('Karir[golongan]', ['' => '- Pilih Golongan -'], '', [
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
			            <?= form_dropdown('Karir[grading_type_id]', ['' => '- Pilih Kelompok -'], '', [
			                'class' => 'form-control',
			                'id' => 'id_grading_type_id'
			            ]); ?>
			        </div>

			        <div class="form-group">
			            <?= form_label('Designation', 'id_designation'); ?>
			            <?= form_dropdown('Karir[designation_id]', ['' => '- Pilih Designation -'], '', [
			                'class' => 'form-control',
			                'id' => 'id_designation'
			            ]); ?>
			        </div>

					<div class="form-group">
						<?= form_label('Job Title', 'id_job_title', ['class' => 'control-label']); ?>
						<?= form_input('Karir[Job_title]',''
						,[
							'class' => 'form-control',
							'id' => 'id_job_title',
						]); ?>						
					</div>

					<div class="form-group">
						<div class="row">
							<div class="col-md-6">
								<?= form_label('Tanggal SK', 'id_tanggal_sk', ['class' => 'control-label']); ?>
								<?php 
								echo form_input('Karir[tanggal_sk]','', [
									'class' => 'form-control datepicker',
									'id' => 'id_tanggal_sk',
								]); ?>
							</div>
							<div class="col-md-6">
								<?= form_label('Tanggal Berakhir', 'id_tanggal_berakhir', ['class' => 'control-label']); ?>
								<?php 
								echo form_input('Karir[tanggal_berakhir]','', [
									'class' => 'form-control datepicker',
									'id' => 'id_tanggal_berakhir',
								]); ?>
							</div>
						</div>
					</div>

					<div class="form-group">
				        <?= form_label('Lampiran', 'id_lampiran_karir'); ?><br/>
				            <div class="fileinput fileinput-new" data-provides="fileinput">
				                <div class="input-group input-large">
				                    <div class="form-control uneditable-input input-fixed input-medium" data-trigger="fileinput">
				                        <i class="fa fa-file fileinput-exists"></i>&nbsp;
				                        <span class="fileinput-filename"></span>
				                    </div>
				                        <span class="input-group-addon btn default btn-file">
				                            <span class="fileinput-new"> Pilih File </span>
				                            <span class="fileinput-exists"> Ubah </span>
				                            <input type="hidden" value="" name="...">
				                                <?= form_upload('lampiran', '', [
							                    		// 'class' => 'form-control',
							                    		'id' => 'id_lampiran_karir',
							                    		'accept' => 'image/*,application/pdf'
							                    ]); ?>
				                            </span>
				                          <a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Hapus </a>
				                </div>
				            </div>
				            <div class="clearfix margin-top-10">
			                    <span class="label label-danger">NOTE!</span> Ukuran file maksimum adalah <b>1MB</b>. 
			                </div>
			        </div>

				    <div class="row form-group" id="file-exist-karir" hidden>
				       <?= form_label('Surat Keterangan Saat Ini: ', '', ['class' => 'col-md-3']); ?>
				            <div class="col-md-9">
				                <a href="javascript:;"></a>
				            </div>
				    </div>


					<?= form_close(); ?>
				</fieldset>
			</div>
			<!-- footer modal -->
			<div class="modal-footer">
				<input type="submit" id="btn-simpan-karir" class="btn btn-success" form="form-add-karir"></button>
				<button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
			</div>
		</div>
	</div>
</div>
