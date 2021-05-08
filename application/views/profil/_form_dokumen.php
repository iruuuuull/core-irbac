<?= form_open_multipart("/profil/simpan-dokumen?id={$param}") ?>
    <div class="note note-info">
        <h4 class="block">Peraturan</h4>
        <ul>
        	<li>Ukuran maksimum file yg di-unggah adalah <b>1MB</b></li>
        	<li>Format file yang diterima hanya PDF, JPG, dan PNG</li>
        </ul>
    </div>

    <?php
    	foreach ($dokumens as $key => $dokumen):
    		$existing = !empty($data_lampiran[$dokumen->id]) ? $data_lampiran[$dokumen->id] : [];
    ?>
		<div class="form-group">
	    	<?= form_label($dokumen->label, "id_{$dokumen->kode_dokumen}", ['class' => 'control-label']); ?>
	    	<div class="row">
		    	<div class="col-md-6">
		    		<?= form_input("Dokumen[{$dokumen->kode_dokumen}]", def($existing, 'nomor_dokumen'), ['class' => 'form-control']); ?>
		    		<div class="help-block">Nomor Dokumen</div>
		    	</div>
		    	<div class="col-md-6">
			    	<div class="fileinput fileinput-new" data-provides="fileinput">
			            <div class="input-group input-large">
			                <div class="form-control uneditable-input input-fixed input-medium" data-trigger="fileinput">
			                    <i class="fa fa-file fileinput-exists"></i>
			                    	<?= substr(def($existing, 'path_dokumen'), strrpos(def($existing, 'path_dokumen'), '/') + 1) ?>
			                    <span class="fileinput-filename"></span>
			                </div>
			                <span class="input-group-addon btn btn-default btn-file">
			                    <span class="fileinput-new"> Pilih File </span>
			                    <span class="fileinput-exists"> Ubah </span>
			                    <input type="hidden" value="" name="...">
			                    <?= form_upload($dokumen->kode_dokumen, '', [
			                		// 'class' => 'form-control',
			                		'id' => "id_{$dokumen->kode_dokumen}",
			                		'accept' => 'image/*,application/pdf'
			                	]); ?>
			                </span>
			                <a href="javascript:;" class="input-group-addon btn btn-danger fileinput-exists" data-dismiss="fileinput"> Hapus </a>
			            </div>
		    			<div class="help-block">
		    				<?php if (!empty($existing->path_dokumen)): ?>
		    					<a href="javascript:;" onclick="return previewPDF('<?= base_url($existing->path_dokumen) ?>')"><?= "File {$dokumen->label}" ?></a>
		    				<?php endif ?>
		    			</div>
			        </div>
		    	</div>
	    	</div>
	    </div>
    <?php endforeach ?>


<?php 

	if($data_login >= $data_profil): ?>
    <?= $this->html->button('Simpan', [
    	'type' => 'submit',
		'class' => 'btn btn-success',
		'visible' => (
			($this->session->userdata('identity')->id == def($user_detail, 'user_id'))
			|| $this->helpers->isSuperAdmin() 
		)
	]) ?>
	    <?= $this->html->button('Batal', [
    	'type' => 'reset',
		'class' => 'btn default',
		'visible' => (
			($this->session->userdata('identity')->id == def($user_detail, 'user_id'))
    		|| $this->helpers->isSuperAdmin() 
		)
	]) ?>
    <?php else: ?>
    <?= $this->html->button('Simpan', [
    	'type' => 'submit',
		'class' => 'btn btn-success',
		'visible' => (
			($this->session->userdata('identity')->id == def($user_detail, 'user_id'))
			|| $this->helpers->isSuperAdmin() || $this->helpers->isAdminDirektorat() ||  $this->helpers->isAdminCabang()
		)
	]) ?>
	    <?= $this->html->button('Batal', [
    	'type' => 'reset',
		'class' => 'btn default',
		'visible' => (
			($this->session->userdata('identity')->id == def($user_detail, 'user_id'))
    		|| $this->helpers->isSuperAdmin() || $this->helpers->isAdminDirektorat() ||  $this->helpers->isAdminCabang()
		)
	]) ?>
    <?php endif ?>


<?= form_close(); ?>
