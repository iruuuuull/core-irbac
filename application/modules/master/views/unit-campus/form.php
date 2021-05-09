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
                    <?= $this->html->activeTextInput($model, 'unit_id', [
                        'class' => 'form-control',
                        'maxlength' => '4',
                        'onkeypress' => 'return isNumberKey(event)',
                        'disabled' => $readonly
                    ]) ?>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-lg-2 col-form-label">Unit Nama</label>
                <div class="col-lg-8">
                    <?= $this->html->activeTextInput($model, 'unit_name', [
                        'class' => 'form-control',
                        'disabled' => $readonly,
                    ]) ?>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-lg-2 col-form-label">Unit Parent ID</label>
                <div class="col-lg-8">
                    <?= $this->html->activeDropDownList($model, 'unit_parent_id', $model->getListParent(true, true), [
                        'class' => 'form-control',
                        'disabled' => $readonly,
                    ]) ?>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-lg-2 col-form-label">Unit Parent</label>
                <div class="col-lg-8">
                    <?= $this->html->activeDropDownList($model, 'unit_parent', $model->getListUnitParent(true, true), [
                        'class' => 'form-control',
                        'disabled' => $readonly,
                    ]) ?>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-lg-2 col-form-label">Unit Level</label>
                <div class="col-lg-8">
                    <?= $this->html->activeDropDownList($model, 'unit_level', UnitCampus::getListLevel(true), [
                        'class' => 'form-control',
                        'disabled' => $readonly,
                    ]) ?>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-lg-2 col-form-label">Unit Status</label>
                <div class="col-lg-8">
                    <?= $this->html->activeDropDownList($model, 'unit_status', UnitCampus::getListStatus(true), [
                        'class' => 'form-control',
                        'disabled' => $readonly,
                    ]) ?>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-lg-2 col-form-label">Kerjasama</label>
                <div class="col-lg-8">
                    <?= $this->html->activeDropDownList($model, 'unit_kerjasama', UnitCampus::getListKerjasama(true), [
                        'class' => 'form-control',
                        'disabled' => $readonly,
                    ]) ?>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-lg-2 col-form-label">Unit Tipe</label>
                <div class="col-lg-8">
                    <?= $this->html->activeDropDownList($model, 'unit_type', UnitCampus::getListType(true), [
                        'class' => 'form-control',
                        'disabled' => $readonly,
                    ]) ?>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-lg-10 text-right">
                    <?= anchor('/master/unit-campus', 'Kembali', ['class' => 'btn-normal btn-outline-danger']); ?>

                    <?php if ($readonly !== true): ?>
                        <?= $this->html->submitButton('Submit', [
                            'class' => 'btn-normal btn-success'
                        ]) ?>
                    <?php endif ?>
                </div>
            </div>

            <?= form_close(); ?>
        </div>
    </div>
</div>
