<h1 class="page-title"><?= $title ?></h1>

<div class="row">
    <div class="col-md-12">
        <div class="card light bordered">
            <div class="card-body">
            	<div class="table-toolbar">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="btn-group mb-3">
                                <button id="btn-add" class="btn sbold btn-primary"> Tambah
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            	<table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table-group">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Group</th>
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
                <h5 class="modal-title">Modal Title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
            	<fieldset id="field-single">
            		<?= form_open('', ['id' => 'form-single']); ?>

            			<div class="form-group">
	                        <?= form_label('Nama Group', 'id_label'); ?>
	                    	<?= form_input('Group[label]', '', [
	                    		'class' => 'form-control',
	                    		'id' => 'id_label',
                                'required' => true,
	                    	]); ?>
	                    </div>

            			<div class="form-group">
	                    	<?= form_label('Deskripsi Tugas', 'id_desc'); ?>
	                    	<?= form_textarea('Group[desc]', '', [
	                    		'class' => 'form-control',
	                    		'id' => 'id_desc',
                                'style' => 'height:100px;resize:none;'
	                    	]); ?>
            			</div>

	        		<?= form_close(); ?>
            	</fieldset>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Tutup</button>
                <button type="button" id="btn-save" class="btn btn-success ladda-button" data-style="expand-right">
                	<span class="ladda-label">Simpan</span></button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
