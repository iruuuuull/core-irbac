<h1 class="page-title"><?= $title ?></h1>
<input type="text" hidden disabled id='is_attach' value="<?= $is_attach ?>">
<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered">
			<div class="portlet-body">
				<div class="table-toolbar">
					<div class="row">
						<div class="col-md-6">
							<div class="btn-group">
								<button id="btn-add-create" class="btn sbold btn-primary"> Tambah
									<i class="fa fa-plus"></i>
								</button>
							</div>
						</div>
					</div>
				</div>

				<table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table-create">
					<thead>
						<tr>
							<th>No</th>
							<th>Tanggal</th>
							<th>Nomor Surat</th>
							<th>Dari</th>
							<th>Kepada</th>
							<th>Keterangan</th>
							<th>Lampiran</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<div class="modal fade draggable-modal" id="modal_create" tabindex="-1" role="basic" data-backdrop="static"  aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title">Form Surat</h4>
			</div>
			<div class="modal-body">
				<fieldset id="field-create">
					<?= form_open('', [
						'id' => 'form-create',
						'enctype' => 'multipart/form-data'
					]); ?>

					<div class="form-group" id="surat_date">
						<?= form_label('Tanggal Surat', 'id_tangal_surat'); ?>
						<?= form_input('letter[letter_date]', date('d-m-Y'), [
							'date' => date('d-m-Y'),
							'class' => 'form-control datepicker',
							'id' => 'id_tangal_surat',
						]); ?>
					</div>
					
					<div class="form-group" id="from_surat">
						<?= form_label('Surat Dari', 'id_dari_surat'); ?>
						<?= form_dropdown('letter[letter_from]',$list_code ,'', [
							'class' => 'form-control',
							'id' => 'id_dari_surat',         
						]); ?>
					</div>

					<div class="form-group" id="desc_surat">
						<?= form_label('Keterangan Surat', 'id_keterangan_surat', ['class' => 'control-label']); ?>
						<?= form_dropdown('letter[desc]', $list_type ,'', [
							'class' => 'form-control',
							'id' => 'id_keterangan_surat',
						]); ?>
					</div>
					
					<div class="form-group" id="to_surat">
						<?= form_label('Ditujukan Kepada', 'id_untuk_surat'); ?>
						<?= form_input('letter[letter_to]', '', [
							'class' => 'form-control',
							'id' => 'id_untuk_surat',
						]); ?>
					</div>

					<div class="form-group" id="no_surat">
						<?= form_label('No Surat', 'id_no_surat'); ?>
						<?= form_input('letter[letter_no]', $letter_no, [
							'format' => $letter_no,
							'class' => 'form-control',
							'readonly' => 'true',
							'id' => 'id_no_surat',
						]); ?>
					</div>


					<div class="form-group" id="file-upload">
						<?= form_label('Lampiran', 'id_lampiran_letter'); ?><br/>
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
										'id' => 'id_lampiran_letter',
										'accept' => 'image/*,application/pdf'
									]); ?>
								</span>
								<a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Hapus </a>
							</div>
						</div>
						<div class="clearfix margin-top-10">
							<span class="label label-danger">NOTE!</span> Ukuran file maksimum adalah <b>1MB</b>.</br>
							<label style="margin-left:55px; margin-top: 10px;">Wajib upload <b>Surat</b></label> 
						</div>
					</div>

					<div class="row form-group" id="file-surat" hidden>
				       <?= form_label('Surat Saat Ini: ', '', ['class' => 'col-md-3']); ?>
				            <div class="col-md-9">
				                <a href="javascript:;"></a>
				            </div>
				    </div>

					<?= form_close(); ?>
				</fieldset>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" id="btn-close-pendidikan" data-dismiss="modal">Tutup</button>
				<button type="submit" class="btn green" id="btn-simpan-create" form="form-create">Simpan</button>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
