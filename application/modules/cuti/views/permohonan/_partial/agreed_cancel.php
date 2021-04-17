<div class="row">
    <div class="col-lg-6">
        <table class="table-striped table-bordered table-condensed">
            <thead>
                <tr>
                    <th width="136px">Leave Balance</th>
                    <th width="48px"><?= $model_user->userDetail->getCutiCount() ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Last Year Leave</td>
                    <td><?= $model_user->userDetail->last_leave ?></td>
                </tr>
                <tr>
                    <td>Current Year Leave</td>
                    <td><?= $model_user->userDetail->current_leave ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <?php if ($model->method === 'update'): ?>
        
    <div class="col-lg-6">
        <div class="row">
            <div class="col-lg-8 align-items-center justify-content-center">
                <p class="text-right">
                    <span style="border-bottom: 1px solid #000;font-weight: bold;">
                        <?= $model_user->userDetail->mergeFullName($model_user->userDetail) ?></span><br/>
                    <?= $model_user->userDetail->job_title ?>
                </p>
            </div>
            <div class="col-lg-4">
                <img src="<?= $model_user->userDetail->getImage() ?>" class="img img-circle" width="75%" />
            </div>
        </div>
    </div>

    <?php endif ?>
</div>

<hr/>

<?= form_open($action, [
    'id' => 'form-pembatalan',
    'class' => 'form-horizontal'
]); ?>

    <div class="row">
        <div class="col-lg-7">
            <div class="form-group">
                <?= form_label('Tipe Cuti', 'id_cuti_type', [
                    'class' => 'col-lg-4 control-label'
                ]); ?>
                <div class="col-lg-7">
                    <?= form_input('tipe_cuti', $model_cuti->tipeCutiMain->getListTypeValue(), [
                            'class' => 'form-control',
                            'id' => 'id_cuti_type',
                            'readonly' => true,
                    ]); ?>
                </div>
            </div>

            <div class="form-group">
                <?= form_label('Jenis Cuti yg Diambil', 'id_type_taken', [
                    'class' => 'col-lg-4 control-label'
                ]); ?>
                <div class="col-lg-7">
                    <?= form_input('tipe_cuti_diambil', $model_cuti->tipeCuti->getListTypeValue(), [
                            'class' => 'form-control',
                            'id' => 'id_type_taken',
                            'readonly' => true,
                    ]); ?>
                </div>
            </div>

        </div>

        <div class="col-lg-5">
            <div class="form-group">
                <?= form_label('Tanggal Mulai', 'id_tanggal_mulai', [
                    'class' => 'col-lg-5 control-label'
                ]); ?>
                <div class="col-lg-7">
                    <?= form_input('tanggal_mulai', date('d-m-Y', strtotime($model_cuti->tanggal_mulai)), [
                            'class' => 'form-control',
                            'id' => 'id_tanggal_mulai',
                            'readonly' => true,
                    ]); ?>
                </div>
            </div>

            <div class="form-group">
                <?= form_label('Tanggal Akhir', 'id_tanggal_akhir', [
                    'class' => 'col-lg-5 control-label'
                ]); ?>
                <div class="col-lg-7">
                    <?= form_input('tanggal_mulai', date('d-m-Y', strtotime($model_cuti->tanggal_akhir)), [
                            'class' => 'form-control',
                            'id' => 'id_tanggal_akhir',
                            'readonly' => true,
                    ]); ?>
                </div>
            </div>
        </div>
    </div>

    <br/>

    <div class="row">
        <fieldset class="col-lg-7" <?= $model->method === 'update' ? 'disabled' : '' ?>>
            <div class="form-group">
                <?= form_label('Jangka Waktu', 'id_start_cancel', [
                    'class' => 'control-label col-lg-4'
                ]); ?>
                <div class="col-lg-7">
                    <div class="input-group date-picker">
                        <?= form_input('FormBatalCuti[start_cancel]', 
                            isDate($model->start_cancel) ? date('d-m-Y', strtotime($model->start_cancel)) : '', [
                                'class' => 'form-control',
                                'id' => 'id_start_cancel',
                                'required' => true,
                                'readonly' => true,
                        ]); ?>
                        <span class="input-group-addon"> s/d </span>
                        <?= form_input('FormBatalCuti[end_cancel]', 
                            isDate($model->end_cancel) ? date('d-m-Y', strtotime($model->end_cancel)) : '', [
                                'class' => 'form-control',
                                'id' => 'id_end_cancel',
                                'required' => true,
                                'readonly' => true,
                        ]); ?>
                        <span class="input-group-btn">
                            <button class="btn btn-default reset-date" type="button">
                                <i class="fa fa-refresh"></i></button>
                        </span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <?= form_label('Catatan Pembatalan', 'id_note', [
                    'class' => 'control-label col-lg-4'
                ]); ?>
                <div class="col-lg-7">
                    <?= form_textarea('FormBatalCuti[note]', $model->note, [
                        'class' => 'form-control',
                        'id' => 'id_note',
                        'style' => 'height:108px;resize:none'
                    ]); ?>
                </div>
            </div>

            <div class="form-group">
                <div class="col-lg-5"></div>
                <div class="col-lg-7">
                    <label class="mt-checkbox">
                        <?= form_hidden('FormBatalCuti[agreement]', null); ?>
                        <?= form_checkbox('FormBatalCuti[agreement]', 1, $model->agreement, [
                            'id' => 'id_agreement',
                        ]); ?>

                        Batalkan cuti
                        <span></span>
                    </label>
                </div>
            </div>
        </fieldset>

        <?php if ($model->method === 'update'): ?>

        <fieldset class="col-lg-5">
            <div class="form-group">
                <?= form_label('Tanggal Disetujui', 'id_tanggal_setuju', [
                    'class' => 'col-lg-5 control-label'
                ]); ?>
                <div class="col-lg-7">
                    <?= form_input('tanggal_setuju', date('d-m-Y'), [
                            'class' => 'form-control',
                            'id' => 'id_tanggal_setuju',
                            'readonly' => true,
                    ]); ?>
                </div>
            </div>

            <div class="form-group">
                <div class="col-lg-5"></div>
                <div class="col-lg-7">
                    <label class="mt-checkbox">
                        <?= form_hidden('FormBatalCuti[approval]', null); ?>
                        <?= form_checkbox('FormBatalCuti[approval]', 1, $model->approval, [
                            'id' => 'id_approval',
                        ]); ?>

                        Setujui Pembatalan Cuti
                        <span></span>
                    </label>
                </div>
            </div>
        </fieldset>

        <?php endif ?>
    </div>

<?= form_close(); ?>

<script type="text/javascript" id="script-cancel">
    let start_date = moment('<?= date('d-m-Y', strtotime($model_cuti->tanggal_mulai)) ?>', 'DD-MM-YYYY');
    let end_date = moment('<?= date('d-m-Y', strtotime($model_cuti->tanggal_akhir)) ?>', 'DD-MM-YYYY');

    if (start_date._d < new Date) {
        start_date = moment(new Date);
    }

    $("#id_start_cancel").datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
        startDate: start_date.format('DD-MM-YYYY'),
        endDate: end_date.format('DD-MM-YYYY'),
        daysOfWeekDisabled: [0]
    }).on('changeDate', function(e) {
        let selected_date = e.date;

        if (selected_date) {
            let start = new Date(selected_date.valueOf());

            if ($("#id_end_cancel").val().length == 0) {
                $("#id_end_cancel").datepicker('setDate', start);
            } else {
                let end = moment($("#id_end_cancel").val(), 'DD-MM-YYYY').toDate();

                if (start > end) {
                    $("#id_end_cancel").datepicker('setDate', start);
                }
            }
        }
    });

    $("#id_end_cancel").datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
        startDate: start_date.format('DD-MM-YYYY'),
        endDate: end_date.format('DD-MM-YYYY'),
        daysOfWeekDisabled: [0]
    }).on('changeDate', function(e) {
        let selected_date = e.date;

        if (selected_date) {
            let end = new Date(selected_date.valueOf());

            if ($("#id_start_cancel").val().length == 0) {
                $("#id_start_cancel").datepicker('setDate', end);
            } else {
                let start = moment($("#id_start_cancel").val(), 'DD-MM-YYYY').toDate();

                if (end < start) {
                    $("#id_start_cancel").datepicker('setDate', end);
                }
            }
        }
    });
</script>
