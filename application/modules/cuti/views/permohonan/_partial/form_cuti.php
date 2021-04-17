<div class="row">
    <div class="col-lg-6">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Leave Balance</th>
                    <th><?= $model_user->userDetail->getCutiCount() ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Last Year Leave</td>
                    <td><?= (int) $model_user->userDetail->last_leave ?></td>
                </tr>
                <tr>
                    <td>Current Year Leave</td>
                    <td><?= (int) $model_user->userDetail->current_leave ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<hr/>

<?= form_open('', ['id' => 'form-cuti']); ?>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <?= form_label('Jenis Cuti', 'id_cuti_type'); ?>
                <?= form_dropdown('Cuti[cuti_type]', $tipe_cuti, '', [
                    'class' => 'form-control',
                    'id' => 'id_cuti_type',
                ]); ?>
            </div>

            <div class="form-group" id="div-type_taken">
                <?= form_label('Jenis Cuti yg Diambil', 'id_type_taken'); ?>
                <?= form_dropdown('Cuti[type_taken]', ['' => '- Pilih Tipe Diambil -'], '', [
                    'class' => 'form-control',
                    'id' => 'id_type_taken',
                ]); ?>
            </div>

            <div class="form-group">
                <?= form_label('Jangka Waktu', 'id_tanggal_mulai'); ?>
                <div class="input-group input-large date-picker input-daterange" data-date-format="dd-mm-yyyy" id="div-tanggal">
                    <?= form_input('Cuti[tanggal_mulai]', '', [
                            'class' => 'form-control',
                            'id' => 'id_tanggal_mulai',
                            'required' => true,
                            'readonly' => true,
                    ]); ?>
                    <span class="input-group-addon"> s/d </span>
                    <?= form_input('Cuti[tanggal_akhir]', '', [
                            'class' => 'form-control',
                            'id' => 'id_tanggal_akhir',
                            'required' => true,
                            'readonly' => true,
                    ]); ?>
                    <span class="input-group-btn">
                        <button class="btn btn-default reset-date" type="button">Reset</button>
                    </span>
                </div>
            </div>

            <div class="form-group">
                <?= form_label('Lampiran', 'id_attachment'); ?><br/>
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
                            <?= form_upload('attachment', '', [
                                // 'class' => 'form-control',
                                'id' => 'id_attachment',
                                'accept' => 'application/pdf'
                            ]); ?>
                        </span>
                        <a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Hapus </a>
                    </div>
                </div>
                <div class="clearfix margin-top-10">
                    <span class="label label-danger">NOTE!</span> Ukuran maksimum adalah <b>1MB</b> dengan format <b>PDF</b>. </div>
            </div>

        </div>

        <div class="col-md-6">
            <div class="form-group">
                <?= form_label('Catatan', 'id_note'); ?>
            	<?= form_textarea('Cuti[note]', '', [
            		'class' => 'form-control',
            		'id' => 'id_note',
                    'style' => 'height:108px;resize:none'
            	]); ?>
            </div>
        </div>
    </div>

<?= form_close(); ?>