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

            	<table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table-dokumen">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Designation Group</th>
                            <th>Designation</th>
                            <th>Set as Job Title</th>
                            <th>Action</th>
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
            		<?= form_open('', [
                        'id' => 'form-single',
                        'class' => 'form-horizontal'
                    ]); ?>

                        <div class="form-group">
    	                    <?= form_label('Designation Group', 'id_grading_type', ['class' => 'control-label col-lg-4']); ?>

                            <div class="col-md-8">
    	                    	<?= form_dropdown('Designation[grading_type_id]', $list_grading_type, '', [
    	                    		'class' => 'form-control',
    	                    		'id' => 'id_grading_type',
    	                    	]); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <?= form_label('Designation', 'id_designation', ['class' => 'control-label col-lg-4']); ?>

                            <div class="col-md-8">
                                <?= form_input('Designation[designation]', '', [
                                    'class' => 'form-control',
                                    'id' => 'id_designation',
                                ]); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <?= form_label('Set As Job Title', 'id_combine_label', ['class' => 'control-label col-lg-4']); ?>

                            <div class="col-md-8">
                                <?= form_hidden('Designation[combine_label]', 0); ?>
                                <?= form_checkbox('Designation[combine_label]', 1, 0, [
                                    'class' => 'make-switch',
                                    'id' => 'id_combine_label',
                                    'data-size' => 'mini',
                                    'data-on-text' => '<i class=\'fa fa-check\'></i>',
                                    'data-off-text' => '<i class=\'fa fa-times\'></i>'
                                ]); ?>
                            </div>
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
