<div class="row">
    <div class="col-lg-8">
        <?= form_open('', [
            'id' => 'form-cuti-bersama', 
            'class' => 'form-horizontal'
        ]); ?>

            <div class="form-group">
                <?= form_label('Jangka Waktu', 'id_tanggal_mulai_mass', [
                    'class' => 'control-label col-lg-3'
                ]); ?>
                <div class="col-lg-8">
                    <div class="input-group input-large date-picker input-daterange" data-date-format="dd-mm-yyyy" id="div-tanggal">
                        <?= form_input('FormCutiBersama[tanggal_mulai]', '', [
                                'class' => 'form-control',
                                'id' => 'id_tanggal_mulai_mass',
                                'required' => true,
                                'readonly' => true,
                        ]); ?>
                        <span class="input-group-addon"> s/d </span>
                        <?= form_input('FormCutiBersama[tanggal_akhir]', '', [
                                'class' => 'form-control',
                                'id' => 'id_tanggal_akhir_mass',
                                'required' => true,
                                'readonly' => true,
                        ]); ?>
                        <span class="input-group-btn">
                            <button class="btn btn-default reset-date" type="button">Reset</button>
                        </span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <?= form_label('Lampiran', 'id_attachment_mass', [
                    'class' => 'control-label col-lg-3'
                ]); ?>
                <div class="fileinput fileinput-new col-lg-8" data-provides="fileinput">
                    <div class="input-group input-large">
                        <div class="form-control uneditable-input input-fixed input-medium" data-trigger="fileinput">
                            <i class="fa fa-file fileinput-exists"></i>&nbsp;
                            <span class="fileinput-filename"></span>
                        </div>
                        <span class="input-group-addon btn default btn-file">
                            <span class="fileinput-new"> Pilih File </span>
                            <span class="fileinput-exists"> Ubah </span>
                            <input type="hidden" value="" name="...">
                            <?= form_upload('attachment', '', [
                                // 'class' => 'form-control',
                                'id' => 'id_attachment_mass',
                                'accept' => 'application/pdf'
                            ]); ?>
                        </span>
                        <a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Hapus </a>
                    </div>
                    <div class="clearfix margin-top-10">
                        <span class="label label-danger">NOTE!</span> Ukuran maksimum adalah <b>1MB</b> dengan format <b>PDF</b>. </div>
                </div>
            </div>

            <div class="form-group">
                <?= form_label('Catatan', 'id_note_mass', [
                    'class' => 'control-label col-lg-3'
                ]); ?>
                <div class="col-lg-8">
                	<?= form_textarea('FormCutiBersama[note]', '', [
                		'class' => 'form-control',
                		'id' => 'id_note_mass',
                        'style' => 'height:108px;resize:none'
                	]); ?>
                </div>
            </div>

        <?= form_close(); ?>
    </div>
</div>