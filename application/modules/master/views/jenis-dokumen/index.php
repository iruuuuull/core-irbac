<h1 class="page-title"><?= $title ?></h1>

<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
            	<div class="table-toolbar">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="btn-group">
                                <button id="btn-add" class="btn sbold btn-primary"> Tambah
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            	<table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table-dokumen">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Dokumen</th>
                            <th>Label Dokumen</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade draggable-modal" id="draggable" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Modal Title</h4>
            </div>
            <div class="modal-body">
            	<fieldset id="field-multiple">
	        		<?= form_open('/master/jenis-dokumen/tambah', ['id' => 'form-multiple']); ?>

	        			<div class="form-group mt-repeater">
                            <div data-repeater-list="JenisDokumen">
                                <div data-repeater-item class="mt-repeater-item">
                                    <div class="mt-repeater-row">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="row form-group">
                                                    <div class="col-md-5">
                                                    	<?= form_label('Kode Dokumen'); ?>
                                                    	<?= form_input('kode_dokumen', '', [
                                                    		'class' => 'form-control',
                                                    	]); ?>
                                                    </div>
                                                    <div class="col-md-7">
                                                        <?= form_label('Label'); ?>
                                                    	<?= form_input('label', '', [
                                                    		'class' => 'form-control',
                                                    	]); ?>
                                                    </div>
                                                </div>

                                                <div class="row form-group">
                                                    <div class="col-md-12">
                                                        <?= form_label('Deskripsi'); ?>
                                                        <?= form_textarea('desc', '', [
                                                            'class' => 'form-control',
                                                            'style' => 'resize: none;height: 90px'
                                                        ]); ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-1">
                                                <a href="javascript:;" data-repeater-delete class="btn btn-danger mt-repeater-delete">
                                                    <i class="fa fa-close"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a href="javascript:;" data-repeater-create class="btn btn-info mt-repeater-add">
                                <i class="fa fa-plus"></i> Tambah Jenis Dokumen</a>
                        </div>

	        		<?= form_close(); ?>
            	</fieldset>

            	<fieldset id="field-single" hidden>
            		<?= form_open('', ['id' => 'form-single']); ?>

                        <div class="row form-group">
                            <div class="col-md-5">
    	                    	<?= form_label('Kode Dokumen', 'id_kode_dokumen'); ?>
    	                    	<?= form_input('JenisDokumen[kode_dokumen]', '', [
    	                    		'class' => 'form-control',
    	                    		'id' => 'id_kode_dokumen',
    	                    	]); ?>
                            </div>

                            <div class="col-md-7">
    	                        <?= form_label('Label', 'id_label'); ?>
    	                    	<?= form_input('JenisDokumen[label]', '', [
    	                    		'class' => 'form-control',
    	                    		'id' => 'id_label',
    	                    	]); ?>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-12">
                                <?= form_label('Deskripsi', 'id_desc'); ?>
                                <?= form_textarea('JenisDokumen[desc]', '', [
                                    'class' => 'form-control',
                                    'id' => 'id_desc',
                                    'style' => 'resize: none;height: 90px'
                                ]); ?>
                            </div>
                        </div>

	        		<?= form_close(); ?>
            	</fieldset>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Tutup</button>
                <button type="button" id="btn-save" class="btn green ladda-button" data-style="expand-right">
                	<span class="ladda-label">Simpan</span></button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
