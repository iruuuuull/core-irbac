<div class="note note-info">
    <h4 class="block">Peraturan</h4>
    <ul>
        <li>Ukuran maksimum file yg di-unggah adalah <b>1MB</b></li>
        <li>Format file yang diterima hanya PDF, JPG, dan PNG</li>
    </ul>
</div>
<div class="table-toolbar">

<?php 

	if($data_login >= $data_profil): ?>
    <div class="btn-group">
    	<?= $this->html->button('Tambah <i class="fa fa-plus"></i>', [
    		'id' => 'btn-add-pendidikan',
    		'class' => 'btn sbold btn-primary',
    		'visible' => 
    		 (
    		 	($this->session->userdata('identity')->id == def($user_detail, 'user_id'))
    		 	|| $this->helpers->isSuperAdmin()
    		 )
    	]) ?>
    </div>
    <?php else: ?>
    <div class="btn-group">
    	<?= $this->html->button('Tambah <i class="fa fa-plus"></i>', [
    		'id' => 'btn-add-pendidikan',
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

	
	<table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table-pendidikan">
		<thead>
			<tr>
				<th>No</th>
				<th>Nama Sekolah</th>
				<th>Lokasi</th>
				<th>Periode</th>
				<th>Jurusan</th>
				<th>Dokumen</th>
				<th>Aksi</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>


<div class="modal fade draggable-modal" id="modal-pendidikan" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-lg">
			<!-- konten modal-->
		<div class="modal-content">
				<!-- heading modal -->
		<div class="modal-header">
			<h5 class="modal-title">Form History Pendidikan</h5>
			<button type="button" class="close" data-dismiss="modal">&times;</button>
		</div>
				<!-- body modal -->
		<div class="modal-body">
	<fieldset id="field-pendidikan">
		<?= form_open('', ['id' => 'form-add-pendidikan', 'enctype' => 'multipart/form-data']); ?>

		<div class="form-group">
			<?= form_label('Jenjang Pendidikan', 'id_jenjang_pendidikan', ['class' => 'control-label']); ?>
				<?= form_dropdown('Pendidikan[m_pendidikan_id]', $data_pendidikan,'', [
				'class' => 'form-control',
				'id' => 'id_jenjang_pendidikan',
			]); ?>
		</div>

		<div class="form-group">
			<?= form_label('Nama Sekolah', 'id_nama_sekolah', ['class' => 'control-label']); ?>
			<?= form_input('Pendidikan[nama_sekolah]','', 
				[
				'class' => 'form-control',
				'id' => 'id_nama_sekolah',
			]); ?>
		</div>

		<div class="form-group">
			<?= form_label('Lokasi', 'id_lokasi', ['class' => 'control-label']); ?>
			<?= form_textarea('Pendidikan[lokasi]','', [
				'class' => 'form-control',
				'id' => 'id_lokasi',
				'style' => 'resize:none;height:100px'
			]); ?>
		</div>

		<div class="form-group">
			<div class="row">
				<div class="col-md-6">
					<?= form_label('Tahun Masuk', 'id_tahun_masuk', ['class' => 'control-label']); ?>
					<?= form_input('Pendidikan[tahun_masuk]','', 
						[
						'class' => 'form-control',
						'id' => 'id_tahun_masuk',
					]); ?>
				</div>
				<div class="col-md-6">
					<?= form_label('Tahun Keluar', 'id_tahun_keluar', ['class' => 'control-label']); ?>
					<?= form_input('Pendidikan[tahun_keluar]','', 
						[
						'class' => 'form-control',
						'id' => 'id_tahun_keluar',
					]); ?>
				</div>
			</div>
		</div>

		<div class="form-group">
			<div class="row">
				<div class="col-md-6">
					<?= form_label('Jurusan', 'id_jurusan', ['class' => 'control-label']); ?>
					<?= form_input('Pendidikan[jurusan]','', [
						'class' => 'form-control',
						'id' => 'id_jurusan',
					]); ?>
				</div>
				<div class="col-md-6">
					<?= form_label('Nilai Akhir', 'id_nilai_akhir', ['class' => 'control-label']); ?>
					<?= form_input('Pendidikan[nilai_akhir]','', [
						'class' => 'form-control',
						'id' => 'id_nilai_akhir',
					]); ?>
				</div>
			</div>
		</div>

	    <div class="form-group">
	        <?= form_label('Lampiran', 'id_ijazah'); ?><br/>
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
	                                <?= form_upload('ijazah', '', [
				                    		// 'class' => 'form-control',
				                    		'id' => 'id_ijazah',
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

	    <div class="row form-group" id="file-ijazah" hidden>
	       <?= form_label('Ijazah Saat Ini: ', '', ['class' => 'col-md-3']); ?>
	            <div class="col-md-9">
	                <a href="javascript:;"></a>
	            </div>
	    </div>
			
					
	 <?= form_close(); ?>	

	</fieldset>

			</div>
				<!-- footer modal -->
			<div class="modal-footer">
				<button type="submit" class="btn btn-success" id="btn-simpan-pendidikan" form="form-add-pendidikan">Simpan</button>
				<button type="button" class="btn btn-danger" id="btn-close-pendidikan" data-dismiss="modal">Tutup</button>
			</div>
		</div>
	</div>
</div>


