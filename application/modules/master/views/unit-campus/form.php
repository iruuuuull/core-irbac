<?php
    $readonly = isset($readonly) ? $readonly : false;
?>

<div class="form grid-1">
    <div class="card">
        <div class="card-header">
            <h6><?= $title ?></h6>
        </div>

        <div class="card-body">
            <?= form_open('', ['class' => 'form-horizontal offset-md-2']); ?>

            <div class="form-group row">
                <label class="col-lg-2 col-form-label">ID Unit</label>
                <div class="col-lg-8">
                    <?= form_input('Unit[unit_id]', $model->unit_id, [
                        'class' => 'form-control',
                        'maxlength' => '4',
                        'onkeypress' => 'return isNumberKey(event)',
                        'disabled' => $readonly
                    ]); ?>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-lg-2 col-form-label">Unit Nama</label>
                <div class="col-lg-8">
                    <?= form_input('Unit[unit_name]', $model->unit_name, [
                        'class' => 'form-control',
                        'disabled' => $readonly
                    ]); ?>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-lg-2 col-form-label">Unit Parent ID</label>
                <div class="col-lg-8">
                    <?= form_dropdown('Unit[unit_parent_id]', $model->getListParent(true, true), $model->unit_parent_id, [
                        'class' => 'form-control',
                        'disabled' => $readonly
                    ]); ?>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-lg-2 col-form-label">Unit Parent</label>
                <div class="col-lg-8">
                    <?= form_dropdown('Unit[unit_parent]', $model->getListUnitParent(true, true), $model->unit_parent, [
                        'class' => 'form-control',
                        'disabled' => $readonly
                    ]); ?>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-lg-2 col-form-label">Unit Level</label>
                <div class="col-lg-8">
                    <?= form_dropdown('Unit[unit_level]', UnitCampus::getListLevel(true), $model->unit_level, [
                        'class' => 'form-control',
                        'disabled' => $readonly
                    ]); ?>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-lg-2 col-form-label">Unit Status</label>
                <div class="col-lg-8">
                    <?= form_dropdown('Unit[unit_status]', UnitCampus::getListStatus(true), $model->unit_status, [
                        'class' => 'form-control',
                        'disabled' => $readonly
                    ]); ?>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-lg-2 col-form-label">Kerjasama</label>
                <div class="col-lg-8">
                    <?= form_dropdown('Unit[unit_kerjasama]', UnitCampus::getListKerjasama(true), $model->unit_kerjasama, [
                        'class' => 'form-control',
                        'disabled' => $readonly
                    ]); ?>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-lg-2 col-form-label">Unit Tipe</label>
                <div class="col-lg-8">
                    <?= form_dropdown('Unit[unit_type]', UnitCampus::getListType(true), $model->unit_type, [
                        'class' => 'form-control',
                        'disabled' => $readonly
                    ]); ?>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-lg-10 text-right">
                    <?= anchor('/master/unit-campus', 'Kembali', ['class' => 'btn-normal btn-outline-danger']); ?>

                    <?php if ($readonly !== true): ?>
                        <?= form_submit('submit', 'Submit', ['class' => 'btn-normal btn-success']); ?>
                    <?php endif ?>
                </div>
            </div>

            <?= form_close(); ?>
        </div>
    </div>
</div>
