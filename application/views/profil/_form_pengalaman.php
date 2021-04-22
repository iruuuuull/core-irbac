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
    		'id' => 'btn-add-experience',
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
    		'id' => 'btn-add-experience',
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


<table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table-pengalaman">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Perusahaan</th>
            <th>Jangka Waktu</th>
            <th>Jabatan</th>
            <th>Bagian</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

<div class="modal fade draggable-modal" id="modal-experience" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h5 class="modal-title">Form Pengalaman Kerja</h5>
            </div>
            <div class="modal-body">
            	<fieldset id="field-experience">
            		<?= form_open('', [
            			'id' => 'form-experience', 
            			'enctype' => 'multipart/form-data'
            		]); ?>

            			<div class="form-group">
	                    	<?= form_label('Nama Perusahaan', 'id_nama_perusahaan', ['class' => 'label-required']); ?>
	                    	<?= form_input('Pengalaman[nama_perusahaan]', '', [
	                    		'class' => 'form-control',
	                    		'id' => 'id_nama_perusahaan',
	                    		'required' => true,
	                    	]); ?>
            			</div>

            			<div class="form-group">
	                        <?= form_label('Jangka Waktu', 'id_tanggal_mulai', ['class' => 'label-required']); ?>
	                        <div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="dd-mm-yyyy">
                                <?= form_input('Pengalaman[tanggal_mulai]', '', [
		                    		'class' => 'form-control',
		                    		'id' => 'id_tanggal_mulai',
	                    			'required' => true,
		                    	]); ?>
                                <span class="input-group-addon"> s.d. </span>
                                <?= form_input('Pengalaman[tanggal_selesai]', '', [
		                    		'class' => 'form-control',
		                    		'id' => 'id_tanggal_selesai',
	                    			'required' => true,
		                    	]); ?>
                            </div>
	                    </div>

	                    <div class="form-group">
	                    	<?= form_label('Jabatan', 'id_jabatan'); ?>
	                    	<?= form_input('Pengalaman[jabatan]', '', [
	                    		'class' => 'form-control',
	                    		'id' => 'id_jabatan',
	                    	]); ?>
            			</div>

	                    <div class="form-group">
	                    	<?= form_label('Bagian', 'id_bagian'); ?>
	                    	<?= form_input('Pengalaman[bagian]', '', [
	                    		'class' => 'form-control',
	                    		'id' => 'id_bagian',
	                    	]); ?>
            			</div>

	                    <div class="form-group">
	                    	<?= form_label('Atasan', 'id_atasan'); ?>
	                    	<?= form_input('Pengalaman[atasan]', '', [
	                    		'class' => 'form-control',
	                    		'id' => 'id_atasan',
	                    	]); ?>
            			</div>

	                    <div class="form-group">
	                    	<?= form_label('Lokasi Bekerja', 'id_lokasi_bekerja'); ?>
	                    	<?= form_textarea('Pengalaman[lokasi_bekerja]', '', [
	                    		'class' => 'form-control',
	                    		'id' => 'id_lokasi_bekerja',
	                    		'style' => 'height:70px;resize:none'
	                    	]); ?>
            			</div>

	                    <div class="form-group">
	                    	<?= form_label('Tanggung Jawab', 'id_tanggung_jawab'); ?>
	                    	<?= form_textarea('Pengalaman[tanggung_jawab]', '', [
	                    		'class' => 'form-control',
	                    		'id' => 'id_tanggung_jawab',
	                    		'style' => 'height:100px;resize:none'
	                    	]); ?>
            			</div>

	                    <div class="form-group">
	                    	<?= form_label('Pencapaian', 'id_pencapaian'); ?>
	                    	<?= form_textarea('Pengalaman[pencapaian]', '', [
	                    		'class' => 'form-control',
	                    		'id' => 'id_pencapaian',
	                    		'style' => 'height:100px;resize:none'
	                    	]); ?>
            			</div>

	                    <div class="form-group">
	                    	<?= form_label('Lampiran', 'id_paklaring'); ?><br/>
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
				                    		'id' => 'id_lampiran',
				                    		'accept' => 'image/*,application/pdf'
				                    	]); ?>
	                                </span>
	                                <a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Hapus </a>
	                            </div>
	                        </div>
	                        <div class="clearfix margin-top-10">
                                <span class="label label-danger">NOTE!</span> Ukuran file maksimum adalah <b>1MB</b>. </div>
            			</div>

	                    <div class="row form-group" id="file-paklaring" hidden>
	                    	<?= form_label('Lampiran Saat Ini: ', '', ['class' => 'col-md-3']); ?>
	                    	<div class="col-md-9">
	                    		<a href="javascript:;"></a>
	                    	</div>
	                    </div>

	        		<?= form_close(); ?>
            	</fieldset>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Tutup</button>
                <button type="submit" id="btn-save-experience" class="btn btn-success" form="form-experience">
                	<span class="ladda-label">Simpan</span></button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
