<h1 class="page-title"><?= $title ?></h1>

<div class="portlet light bordered">
	<div class="portlet-body">
		<table class="table table-hover table-stripped" style="margin-top: 25px" id="table-timeoff">
			<thead>
				<tr>
					<th>No</th>
					<th>Diajukan Oleh</th>
					<th>Tanggal Diajukan</th>
					<th>Tanggal Mulai</th>
					<th>Tanggal Selesai</th>
					<th>Catatan</th>
					<th>Status</th>
					<th>Diambil</th>
					<th>Lampiran</th>
					<th>Aksi</th>
				</tr>
			</thead>
		</table>
	</div>
</div>

<?php $this->load->view('layouts/modal_alert', [
    'id' => 'modal-note',
    'title' => 'Catatan',
    'content' => ""
]); ?>

<div class="modal fade" id="modal-action" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Verifikasi Permohonan Cuti</h4>
            </div>
            <div class="modal-body">
                <?= form_open('/cuti/verifikasi/simpan/_id_', ['id' => 'form-verifikasi']); ?>

                    <div class="form-group">
                        <?= form_label('Status Verifikasi', 'id_status'); ?>
                        <?= form_dropdown('CutiVerifikasi[status]', $statuses, null, [
                            'class' => 'form-control',
                            'id' => 'id_status',
                        ]); ?>
                    </div>

                    <div class="form-group">
                        <?= form_label('Catatan', 'id_note'); ?>
                        <?= form_textarea('CutiVerifikasi[note]', null, [
                            'class' => 'form-control',
                            'id' => 'id_note',
                            'style' => 'height:100px;resize:none'
                        ]); ?>
                    </div>

                <?= form_close(); ?>
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

<div class="modal fade" id="modal-cancel" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Form Action</h4>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Tutup</button>
                <button type="button" id="btn-action-save" class="btn green ladda-button" data-style="expand-right">
                    <span class="ladda-label">Simpan</span></button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
