<h1 class="page-title"><?= $title ?></h1>

<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered">
			<div class="portlet-body">
				<table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table-list">
					<thead>
						<tr>
							<th>No</th>
							<th>Tanggal</th>
							<th>Nomor Surat</th>
							<th>Userid</th>
							<th>Dari</th>
							<th>Kepada</th>
							<th>Keterangan</th>
							<th>Lampiran</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<div class="modal fade draggable-modal" id="modal_list" tabindex="-1" role="basic" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title">File Surat</h4>
			</div>
			<div class="modal-body">
				<fieldset id="field-list">
					<?= form_open('', ['id' => 'form-list']); ?>

					<div class="row form-group" id="file-surat" hidden>
				       <?= form_label('Surat Saat Ini: ', '', ['class' => 'col-md-3']); ?>
				            <div class="col-md-9">
				                <a href="javascript:;"></a>
				            </div>
				    </div>

					<?= form_close(); ?>
				</fieldset>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
