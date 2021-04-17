<h1 class="page-title">Informasi Cuti</h1>

<div class="portlet light bordered">
	<div class="portlet-body">
        <div class="table-toolbar">
            <div class="btn-group">
                <button class="btn btn-default" type="button" id="btn-request">Ajukan Cuti</button>
            </div>

            <?php if ($this->helpers->isSuperAdmin()): ?>
            <div class="btn-group">
                <button class="btn btn-warning" type="button" data-toggle="modal" data-target="#modal-mass-leave">Cuti Bersama</button>
            </div>
            <?php endif ?>

            <br/>
            <small class="text-danger" id="info-request"></small>
        </div>

		<table class="table table-hover table-stripped" style="margin-top: 25px" id="table-timeoff">
			<thead>
				<tr>
					<th>No</th>
					<th>Tanggal Diajukan</th>
					<th>Tanggal Mulai</th>
					<th>Tanggal Selesai</th>
					<th>Catatan</th>
					<th>Status</th>
					<th>Diambil</th>
					<th>Persetujuan</th>
					<th>Lampiran</th>
					<th></th>
				</tr>
			</thead>
		</table>
	</div>
</div>

<div class="modal fade" id="modal-time-off" role="basic" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Pengajuan Permohonan Cuti</h4>
            </div>
            <div class="modal-body">
                <?php $this->layout->renderPartial('_partial/form_cuti', []); ?>
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

<div class="modal fade" id="modal-mass-leave" role="basic" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Pengajuan Cuti Bersama</h4>
            </div>
            <div class="modal-body">
                <?php $this->layout->renderPartial('_partial/form_cuti_bersama', []); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Tutup</button>
                <button type="button" id="btn-save-mass" class="btn green ladda-button" data-style="expand-right">
                    <span class="ladda-label">Simpan</span></button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<?php $this->load->view('layouts/modal_alert', [
    'id' => 'modal-note',
    'title' => 'Catatan',
    'content' => ""
]); ?>

<div class="modal fade" id="modal-action" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static">
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
