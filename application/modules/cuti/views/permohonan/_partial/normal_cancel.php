<?= form_open("/cuti/permohonan/batalkan/{$id}", ''); ?>

	<div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <?= form_label('Catatan', 'id_note'); ?>
            	<?= form_textarea('Cuti[note_cancel]', '', [
            		'class' => 'form-control',
            		'id' => 'id_note_cancel',
                    'style' => 'height:100px;resize:none'
            	]); ?>
            </div>
        </div>
    </div>

<?= form_close(); ?>
