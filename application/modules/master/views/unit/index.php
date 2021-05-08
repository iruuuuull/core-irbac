<section class="content-header">
    <h1><?= $title ?></h1>
</section>

<div class="row">
    <div class="col-md-12">
        <div class="card light bordered">
            <div class="card-body">
            	<div class="btn-group float-right mb-3 mr-2 clearfix">
                    <button id="btn-add" class="btn sbold btn-primary"> Tambah
                        <i class="fa fa-plus"></i>
                    </button>
                </div><br/><br/><br/>

            	<table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table-unit">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Induk</th>
                            <th>[Kode Unit] Nama Unit</th>
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
                <fieldset id="field-unit">
                    <?= form_open('', ['id' => 'form-unit']); ?>

                        <div class="form-group">
                            <?= form_label('Kode Induk', 'id_kode_induk'); ?>
                            <?= form_dropdown('Unit[kode_induk]',$data_group ,'', [
                                'class' => 'form-control',
                                'id' => 'id_kode_induk',         
                            ]); ?>
                        </div>

                         <div class="form-group">
                            <?= form_label('Kode Unit', 'id_kode_unit'); ?>
                            <?= form_input('Unit[kode_unit]', '', [
                                'class' => 'form-control',
                                'id' => 'id_kode_unit',
                            ]); ?>
                        </div>

                        <div class="form-group">
                            <?= form_label('Nama Unit', 'id_nama_unit'); ?>
                            <?= form_input('Unit[nama_unit]', '', [
                                'class' => 'form-control',
                                'id' => 'id_nama_unit',
                            ]); ?>
                        </div>

                        <div class="form-group">
                            <?= form_label('Group Unit', 'id_group_unit', ['class' => 'control-label']); ?>
                            <?= form_dropdown('Unit[sbu_id]',$data_Groupunit ,'', [
                                'class' => 'form-control',
                                'id' => 'id_group_unit',         
                            ]); ?>
                        </div>

                        <div class="form-group">
                                <?= form_label('Level Unit', 'id_unit_level', ['class' => 'control-label']); ?>
                                <?= form_dropdown('Unit[unit_level]', $data_unit_level ,'', [
                                    'class' => 'form-control',
                                    'id' => 'id_unit_level',
                                ]); ?>
                        </div>

                        <div class="form-group">
                                <?= form_label('Owner', 'id_owner_unit', ['class' => 'control-label']); ?>
                                <?= form_dropdown('Unit[kode_owner]', [
                                    '' => '- Pilih Ownership -',
                                    '01' => 'Milik Sendiri / Kerjasama',
                                    '02' => 'Mitra/Investor',
                                    '03' => 'Franchise',
                                ],'', [
                                    'class' => 'form-control',
                                    'id' => 'id_owner_unit',
                                ]); ?>
                        </div>

                        <div class="form-group">
                                <?= form_label('Time Zone', 'id_timezone_unit', ['class' => 'control-label']); ?>
                                <?= form_dropdown('Unit[timezone]', $data_timezone
                                    ,'', [
                                    'class' => 'form-control',
                                    'id' => 'id_timezone_unit',
                                ]); ?>
                        </div>

                    <?= form_close(); ?>
                </fieldset>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Tutup</button>
                <button type="submit" id="btn-save" form="form-unit" class="btn btn-success ladda-button" data-style="expand-right">
                    <span class="ladda-label">Simpan</span></button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>