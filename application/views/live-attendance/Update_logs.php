<link href="<?= base_url('web/assets/pages/css/profile.min.css') ?>" rel="stylesheet" type="text/css" />
<h1 class="page-title"><?= $title ?></h1>
<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered">
			<div class="portlet-body">
				<div class="table-toolbar">
					<div class="form-group">
						<div class="row">
							<div class="col-sm-2">
								<?= form_label('Date Between', 'label_group', ['class' => 'control-label']); ?>
							</div>
							<div class="col-sm-3">
								<div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="dd-M-yyyy">
									<?= form_input('','', [
										'class' => 'form-control',
										'id' => 'id_tanggal_start',
									]); ?>
									<span class="input-group-addon"> TO </span>
									<?= form_input('','', [
										'class' => 'form-control',
										'id' => 'id_tanggal_end',
									]); ?>
								</div>
							</div>                        	
						</div>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-md-2">
								<?= form_label('Employee', 'label_karyawan', ['class' => 'control-label']); ?>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<select id="id_karyawan" class="form-control select2">
										<option value="">- Select Employee -</option>
									</select>
								</div>                                 
							</div>
							<div class="col-md-1"> 
								<button type="button" id="btn-cari-logs" style="margin-top: 5px;" class="btn btn-sm green"><i class="fa fa-check"></i> Proses</button>
							</div>
						</div>
					</div>
				</div>
				<hr>
				<div class="table-container">

				</div>                             
				
			</div>
		</div>
	</div>
</div>
<div class="modal fade draggable-modal" id="modal-update" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-lg">
		<!-- konten modal-->
		<div class="modal-content">
			<!-- heading modal -->
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Form Update Attendance</h4>
			</div>
			<!-- body modal -->
			<div class="modal-body">
				<fieldset id="field-Attendance">
					<?= form_open('', ['id' => 'form-update-attendance']); ?>
					<input type="text" hidden readonly name="Attendance[user_id]" id='userid'>
					<div class="form-group">
						<?= form_label('Employee Name', 'id_employe', ['class' => 'control-label']); ?>
						<?= form_input('','', [
							'class' => 'form-control',
							'id' => 'id_employe',
							'readonly'=>'true',
						]); ?>
					</div>
					<div class="form-group">
						<?= form_label('Date', 'id_date', ['class' => 'control-label']); ?>
						<?= form_input('Attendance[calendar_id]','', [
							'class' => 'form-control',
							'id' => 'id_date',
							'readonly'=>'true',
						]); ?>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-md-6">
								<?= form_label('Check In', 'id_checkin', ['class' => 'control-label']); ?>
								<input type="time" name="Attendance[check_in]"  id="checkin" class="form-control" value="" id="appt" name="appt">
							</div>
							<div class="col-md-6">
								<?= form_label('Check Out', 'id_checkin', ['class' => 'control-label']); ?>
								<input type="time" name="Attendance[check_out]" id="checkout" class="form-control" value="" id="appt" name="appt">
							</div>
						</div>
					</div>
					
					<div class="form-group">
						<?= form_label('Attendance Status', 'id_status', ['class' => 'control-label']); ?>
						<?= form_dropdown('Attendance[keterangan]', [
							'' => '- Pilih Status -',
							'H' => 'Hadir',
							'S' => 'Sakit',
							'I' => 'Izin',
							'TH' => 'Tidak Hadir',
							'CT' => 'Cuti',
						],'', [
							'class' => 'form-control',
							'id' => 'id_status',
						]); ?>
					</div>	

					<?= form_close(); ?>
				</fieldset>


			</div>
			<!-- footer modal -->
			<div class="modal-footer">
				<button type="submit" id="btn-update-attendance" class="btn green mt-3" form="form-update-attendance">Simpan</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
			</div>
		</div>
	</div>
</div>

