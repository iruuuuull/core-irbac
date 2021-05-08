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
     	 <div class="btn-group mb-3">
    	<?= $this->html->button('Tambah <i class="fa fa-plus"></i>', [
    		'id' => 'btn-add-sertifikasi',
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
    		'id' => 'btn-add-sertifikasi',
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


	<table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table-sertifikasi">
		<thead>
			<tr>
				<th>No</th>
				<th>Nama Pelatihan</th>
				<th>Penyelenggara</th>
				<th>Lokasi</th>
				<th>Jangka Waktu</th>
				<th>Aksi</th>
			</tr>
		</thead>
		<tbody id="table-sertifikasi">
		</tbody>
	</table>
</div>

<div class="modal fade draggable-modal" id="modal-training" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-lg">
			<!-- konten modal-->
		<div class="modal-content">
				<!-- heading modal -->
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h5 class="modal-title">Form Data Sertifikasi</h5>
			</div>
				<!-- body modal -->
			<div class="modal-body">
	<fieldset id="field-sertifikasi">
		<?= form_open('', ['id' => 'form-add-sertifikasi']); ?>
		<div class="form-group">
			<?= form_label('Nama Pelatihan', 'id_nama_pelatihan', ['class' => 'control-label']); ?>
			<?= form_input('Sertifikasi[nama_pelatihan]',''
			,[
				'class' => 'form-control',
				'id' => 'id_nama_pelatihan',
			]); ?>						
		</div>

		<div class="form-group">
			<?= form_label('Penyelenggara', 'id_penyelenggara', ['class' => 'control-label']); ?>
			<?= form_input('Sertifikasi[penyelenggara]',''
			,[
				'class' => 'form-control',
				'id' => 'id_penyelenggara',
			]); ?>						
		</div>

		<div class="form-group">
			<?= form_label('Lokasi', 'id_lokasi', ['class' => 'control-label']); ?>
			<?= form_textarea('Sertifikasi[lokasi]','', [
				'class' => 'form-control',
				'id' => 'id_lokasi_sertifikasi',
				'style' => 'resize:none;height:100px'
			]); ?>
		</div>

		<div class="form-group">
	        <?= form_label('Jangka Waktu', 'id_tanggal_mulai', ['class' => 'label-required']); ?>
	            <div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="dd-mm-yyyy">
                        <?= form_input('Sertifikasi[tanggal_mulai]', '', [
		                    	'class' => 'form-control',
		                    	'id' => 'id_tanggal_mulai_sertifikasi',
	                    		'required' => true,
		                ]); ?>
                        <span class="input-group-addon"> S.D. </span>
                        <?= form_input('Sertifikasi[tanggal_selesai]', '', [
		                    	'class' => 'form-control',
		                    	'id' => 'id_tanggal_selesai_sertifikasi',
	                    		'required' => true,
		                ]); ?>
                     </div>
	    </div>

	    <div class="form-group">
	        <?= form_label('Lampiran', 'id_lampiran_sertifikasi'); ?><br/>
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
				                    		'id' => 'id_lampiran_sertifikasi',
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

	    <div class="row form-group" id="file-exist-sertifikasi" hidden>
	       <?= form_label('Sertifikasi Saat Ini: ', '', ['class' => 'col-md-3']); ?>
	            <div class="col-md-9">
	                <a href="javascript:;"></a>
	            </div>
	    </div>

		
		<?= form_close(); ?>
	</fieldset>
			</div>
				<!-- footer modal -->
			<div class="modal-footer">
				<button type="submit" id="btn-simpan-sertifikasi" class="btn btn-success mt-3" form="form-add-sertifikasi">Simpan</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
			</div>
		</div>
	</div>



