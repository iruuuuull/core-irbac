<?= form_open("/profil/simpan-info-personal?id={$param}"); ?>

	<div class="form-group">
		<?= form_label('Nama Lengkap', 'id_nama_depan', ['class' => 'control-label']); ?>
		<div class="row">
			<div class="col-md-4">
				<?= form_input('Personal[nama_depan]', $user_detail->nama_depan ?? '', [
					'class' => 'form-control',
					'id' => 'id_nama_depan',
					'required' => true,
				]); ?>
				<small class="help-block label-required">Nama Depan</small>
			</div>
			<div class="col-md-4">
				<?= form_input('Personal[nama_tengah]', $user_detail->nama_tengah ?? '', [
					'class' => 'form-control',
					'id' => 'id_nama_tengah',
				]); ?>
				<small class="help-block">Nama Tengah</small>
			</div>
			<div class="col-md-4">
				<?= form_input('Personal[nama_belakang]', $user_detail->nama_belakang ?? '', [
					'class' => 'form-control',
					'id' => 'id_nama_belakang',
					'required' => true,
				]); ?>
				<small class="help-block label-required">Nama Belakang</small>
			</div>
		</div>
	</div>

	<?php /*<div class="form-group">
		<?= form_label('NIK', 'id_telepon', ['class' => 'control-label']); ?>
		<?= form_input('Personal[nik]', $user_detail->nik ?? '', [
			'class' => 'form-control max-length-default',
			'id' => 'id_nik',
			'onkeypress' => 'return isNumberKey(event)',
			'maxlength' => 16
		]); ?>
	</div>*/ ?>

	<div class="form-group">
		<div class="row">
			<div class="col-md-6">
				<?= form_label('Tempat Lahir', 'id_tempat_lahir', ['class' => 'control-label']); ?>
				<?= form_input('Personal[tempat_lahir]', $user_detail->tempat_lahir ?? '', [
					'class' => 'form-control',
					'id' => 'id_tempat_lahir',
				]); ?>
			</div>
			<div class="col-md-6">
				<?= form_label('Tanggal Lahir', 'id_tanggal_lahir', ['class' => 'control-label']); ?>
				<?php 
				$tanggal_lahir = (int) strtotime(def($user_detail, 'tanggal_lahir')) ? date('d-m-Y', strtotime($user_detail->tanggal_lahir)) : '';

				echo form_input('Personal[tanggal_lahir]', $tanggal_lahir, [
					'class' => 'form-control datepicker',
					'id' => 'id_tanggal_lahir',
				]); ?>
			</div>
		</div>
	</div>

	<div class="form-group">
		<?= form_label('Agama', 'id_agama', ['class' => 'control-label']); ?>
		<?= form_dropdown('Personal[agama]', [
			'' => '- Pilih Agama -',
			'islam' => 'Islam',
			'kristen_protestan' => 'Kristen Protestan',
			'hindu' => 'Hindu',
			'buddha' => 'Buddha',
			'katolik' => 'Katolik',
			'kong_hu_cu' => 'Kong Hu Cu',
		], $user_detail->agama ?? '', [
			'class' => 'form-control',
			'id' => 'id_agama',
		]); ?>
	</div>

	<div class="form-group">
		<div class="row">
			<div class="col-md-6">
				<?= form_label('Jenis Kelamin', 'id_tempat_lahir', ['class' => 'control-label']); ?>
				<br>
				<?= form_radio('Personal[jenis_kelamin]','p',
				($user_detail->jenis_kelamin ?? '' == 'p' ? TRUE : FALSE));?>
				<?= form_label('Laki-Laki') ?>
				<?= form_radio('Personal[jenis_kelamin]','w',
				($user_detail->jenis_kelamin ?? '' == 'w' ? TRUE : FALSE),['style' => 'margin-left:20px;']);?>
				<?= form_label('Perempuan') ?>
			</div>
			<div class="col-md-6">
				<?= form_label('Pendidikan', 'id_pendidikan_id', ['class' => 'control-label']); ?>
				<?= form_dropdown('Personal[pddk_terakhir]', $data_pendidikan, $user_detail->pddk_terakhir ?? '', [
					'class' => 'form-control',
					'id' => 'pendidikan_id',
				]); ?>
			</div>
		</div>
	</div>

	<div class="form-group">
		<?= form_label('Status Perkawinan', 'id_status_perkawinan', ['class' => 'control-label']); ?>
		<?= form_dropdown('Personal[status_perkawinan]', [
			'' => '- Pilih Status Perkawinan -',
			'0' => 'Belum Menikah',
			'1' => 'Menikah',
			'2' => 'Duda/Janda',
		], $user_detail->status_perkawinan ?? '', [
			'class' => 'form-control',
			'id' => 'id_status_perkawinan',
		]); ?>
	</div>

	<div class="form-group">
		<?= form_label('Alamat', 'id_alamat', ['class' => 'control-label']); ?>
		<?= form_textarea('Personal[alamat]', $user_detail->alamat ?? '', [
			'class' => 'form-control',
			'id' => 'id_alamat',
			'style' => 'resize:none;height:100px'
		]); ?>
	</div>

	<div class="form-group">
		<?= form_label('Telepon', 'id_telepon', ['class' => 'control-label']); ?>
		<?= form_input('Personal[telepon]', $user_detail->telepon ?? '', [
			'class' => 'form-control',
			'id' => 'id_telepon',
			'onkeypress' => 'return isNumberKey(event)',
		]); ?>
	</div>

	<div class="form-group">
		<?= form_label('Golongan Darah', 'id_gol_darah', ['class' => 'control-label']); ?>
		<?= form_input('Personal[gol_darah]', $user_detail->gol_darah ?? '', [
			'class' => 'form-control',
			'id' => 'id_gol_darah',
		]); ?>
	</div>

	<div class="form-group">
		<?= form_label('Keluarga Dekat', 'id_keluarga_dekat', ['class' => 'control-label']); ?>
		<div class="row">
			<div class="col-md-4">
				<?= form_input('Personal[keluarga_dekat]', $user_detail->keluarga_dekat ?? '', [
					'class' => 'form-control',
					'id' => 'id_keluarga_dekat',
				]); ?>
				<small class="help-block">Nama</small>
			</div>
			<div class="col-md-4">
				<?= form_dropdown('Personal[hubungan_id]', $data_hubungan, $user_detail->hubungan_id ?? '', [
					'class' => 'form-control',
					'id' => 'id_hubungan_id',
				]); ?>
				<small class="help-block">Hubungan</small>
			</div>
			<div class="col-md-4">
				<?= form_input('Personal[telp_keluarga]', $user_detail->telp_keluarga ?? '', [
					'class' => 'form-control',
					'id' => 'id_nama_belakang',
					'onkeypress' => 'return isNumberKey(event)',
				]); ?>
				<small class="help-block">Nomor Telepon</small>
			</div>
		</div>
	</div>

	<?php 

	if($data_login >= $data_profil): ?>
    <?= $this->html->button('Simpan', [
    	'type' => 'submit',
		'class' => 'btn green',
		'visible' => (
			($this->session->userdata('identity')->id == def($user, 'id'))
			|| $this->helpers->isSuperAdmin()  
		)
	]) ?>
	    <?= $this->html->button('Batal', [
    	'type' => 'reset',
		'class' => 'btn default',
		'visible' => (
			($this->session->userdata('identity')->id == def($user, 'id'))
    		|| $this->helpers->isSuperAdmin()
		)
	]) ?>
    <?php else: ?>
    <?= $this->html->button('Simpan', [
    	'type' => 'submit',
		'class' => 'btn green',
		'visible' => (
			($this->session->userdata('identity')->id == def($user, 'id'))
			|| $this->helpers->isSuperAdmin() || $this->helpers->isAdminDirektorat() ||  $this->helpers->isAdminCabang()
		)
	]) ?>
	    <?= $this->html->button('Batal', [
    	'type' => 'reset',
		'class' => 'btn default',
		'visible' => (
			($this->session->userdata('identity')->id == def($user, 'id'))
    		|| $this->helpers->isSuperAdmin() || $this->helpers->isAdminDirektorat() ||  $this->helpers->isAdminCabang()
		)
	]) ?>
    <?php endif ?>

<?= form_close(); ?>
