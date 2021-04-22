<div class="table-toolbar">

<?php 
	if($data_login >= $data_profil): ?>
     	 <div class="btn-group">
    	<?= $this->html->button('Tambah <i class="fa fa-plus"></i>', [
    		'id' => 'btn-add-keluarga',
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
    		'id' => 'btn-add-keluarga',
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

<table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table-keluarga">
	<thead>
		<tr>
			<th>No</th>
			<th>NIK</th>
			<th>Nama</th>
			<th>Tanggal Lahir</th>
			<th>Hubungan</th>
			<th>Pendidikan</th>
			<th>Aksi</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>

<div class="modal fade draggable-modal" id="modal-keluarga" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-lg">
			<!-- konten modal-->
		<div class="modal-content">
				<!-- heading modal -->
			<div class="modal-header">
				<h5 class="modal-title">Form Biodata Keluarga</h5>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
				<!-- body modal -->
			<div class="modal-body">
					<fieldset id="field-keluarga">
	<?= form_open('', ['id' => 'form-add-keluarga']); ?>
	<div class="form-group">
		<?= form_label('NIK (Nomor Induk Kependudukan)', 'id_no_ktp', ['class' => 'control-label']); ?>
		<?= form_input('Keluarga[no_ktp]','', [
			'class' => 'form-control',
			'id' => 'id_no_ktp',
		]); ?>
	</div>	

	<div class="form-group">
		<?= form_label('Nama Lengkap', 'id_nama_lengkap', ['class' => 'control-label']); ?>
		<?= form_input('Keluarga[nama_lengkap]',''
		,[
			'class' => 'form-control',
			'id' => 'id_nama_lengkap',
		]); ?>						
	</div>

	<div class="form-group">
		<?= form_label('Alamat', 'id_alamat', ['class' => 'control-label']); ?>
		<?= form_textarea('Keluarga[alamat]', '', [
			'class' => 'form-control',
			'id' => 'id_alamat_keluarga',
			'style' => 'resize:none;height:100px'
		]); ?>
	</div>

	<div class="form-group">
		<div class="row">
			<div class="col-md-6">
				<?= form_label('Tempat Lahir', 'id_tempat_lahir_keluarga', ['class' => 'control-label']); ?>
				<?= form_input('Keluarga[tempat_lahir]','', 
					[
					'class' => 'form-control',
					'id' => 'id_tempat_lahir_keluarga',
				]); ?>
			</div>
			<div class="col-md-6">
				<?= form_label('Tanggal Lahir', 'id_tanggal_lahir_keluarga', ['class' => 'control-label']); ?>
				<?php 		
				echo form_input('Keluarga[tanggal_lahir]','', [
					'class' => 'form-control datepicker',
					'id' => 'id_tanggal_lahir_keluarga',
				]); ?>
			</div>
		</div>
	</div>

	<div class="form-group">
		<?= form_label('Agama', 'id_agama_keluarga', ['class' => 'control-label']); ?>
		<?= form_dropdown('Keluarga[agama]', [
			'' => '- Pilih Agama -',
			'islam' => 'Islam',
			'kristen_protestan' => 'Kristen Protestan',
			'hindu' => 'Hindu',
			'buddha' => 'Buddha',
			'katolik' => 'Katolik',
			'kong_hu_cu' => 'Kong Hu Cu',
		],'', [
			'class' => 'form-control',
			'id' => 'id_agama_keluarga',
		]); ?>
	</div>

	<div class="form-group">
		<div class="row">
			<div class="col-md-6">	
				<?= form_label('Pekerjaan', 'id_pekerjaan', ['class' => 'control-label']); ?>
						<?= form_input('Keluarga[pekerjaan]','', [
							'class' => 'form-control',
							'id' => 'id_pekerjaan',
						]); ?>	
			</div>					
			<div class="col-md-6">
					<?= form_label('Pendidikan', 'id_pendidikan_id', ['class' => 'control-label']); ?>
					<?= form_dropdown('Keluarga[pendidikan_id]', $data_pendidikan,'', [
						'class' => 'form-control',
						'id' => 'pendidikan_id',
					]); ?>					
			</div>
		</div>
	</div>

	<div class="form-group">
		<?= form_label('Hubungan Keluarga', 'id_relation_id', ['class' => 'control-label']); ?>
		<?= form_dropdown('Keluarga[relation_id]', $data_hubungan,'', [
			'class' => 'form-control',
			'id' => 'id_relation_id',
		]); ?>
	</div>

	<?= form_close(); ?>
</fieldset>


			</div>
				<!-- footer modal -->
			<div class="modal-footer">
					<button type="submit" id="btn-simpan-keluarga" class="btn btn-success mt-3" form="form-add-keluarga">Simpan</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
			</div>
		</div>
	</div>
</div>
