<h1 class="page-title"><?= $title ?></h1>

<div class="portlet light bordered" style="border-radius: 10px !important">
    <div class="portlet-body">
		<div class="row">
			<div class="col-lg-6 text-center">
				<h2 class="attend-time" style="margin-top: 10px;font-weight: bold"></h2>
				<small class="attend-date"></small><br>	

				<hr />

				<p style="font-weight: bold;"><?= isWeekend() ? 'Akhir Pekan' : 'Hari Kerja' ?></p>
				<h2 style="line-height:0;;font-weight: bold">08:00 AM - 17:00 PM</h2>

				<?= form_open('/live-attendance/check', [
					'id' => 'form-attendance'
				]); ?>

				<?= form_textarea('Attendance[catatan]', '', [
					'class' => 'form-control',
					'placeholder' => 'Catatan',
					'style' => 'margin-top:50px; height:120px; resize:none',
					'id' => 'attendance-catatan'
				]); ?>

				<input type="text" id="timezone" hidden disabled value="<?= $timezone ?>">
				<input type="hidden" name="Attendance[coordinate]" id="attendance-coordinate">
				<input type="hidden" name="Attendance[location]" id="attendance-location">

				<div class="row" style="margin-top: 25px">
					<div class="col-lg-6">
						<?= $this->html->button('<i class="fa fa-sign-in"></i> Check In', [
							'class' => 'btn btn-primary pull-left ladda-button',
							'id' => 'btn-check-in',
							'data-style' => 'expand-right',
							'disabled' => !empty($attendance->check_in)
						]) ?>
					</div>
					<div class="col-lg-6">
						<?= $this->html->button('<i class="fa fa-sign-out"></i> Check Out', [
							'class' => 'btn btn-primary pull-right ladda-button',
							'id' => 'btn-check-out',
							'data-style' => 'expand-right',
							'disabled' => !empty($attendance->check_out) || empty($attendance->check_in)
						]) ?>
					</div>
				</div>

				<?php form_close(); ?>

				<hr />

				<h4 class="text-left"><b>Attendance Logs</b></h4>
				<table class="table table-hover table-stipped" id="table-attendance">
					<?php if (empty($attendance)): ?>
						<tr><td><i>Belum ada aktifitas absen.</i></td></tr>
					<?php else: ?>
						<?php if ($attendance->check_in): ?>
							<tr>
								<td><?= date('H:i A', strtotime($attendance->check_in)) ?></td>
								<td>Check In</td>
								<td><a href="#" class="btn-detail-in" data-id="<?= $attendance->id ?>">Detail</a></td>
							</tr>
						<?php endif ?>
						<?php if ($attendance->check_out): ?>
							<tr>
								<td><?= date('H:i A', strtotime($attendance->check_out)) ?></td>
								<td>Check Out</td>
								<td><a href="#" class="btn-detail-out" data-id="<?= $attendance->id ?>">Detail</a></td>
							</tr>
						<?php endif ?>
					<?php endif ?>
				</table>

			</div>

			<div class="col-lg-6">
				<div id="map-location-preview" style="width: 100%;height: 60vh;margin-bottom: 10px"></div>
				<table>
					<tr>
						<th valign="top">Lokasi</th>
						<td valign="top" width="10px">:</td>
						<td><span id="attend-location">-</span></td>
					</tr>
				</table>
			</div>
		</div>
    </div>
</div>

<p class="text-muted text-center">
	<i class="fa fa-info-circle"></i> Apa yang harus saya lakukan jika saya sangat yakin bahwa 
	lokasi yang ditunjukkan map berbeda dengan lokasi saya saat ini?
	<a href="#" data-toggle="modal" data-target="#disclaimer">Pelajari lebih lanjut</a>
</p>

<?php $this->load->view('layouts/modal_alert', [
	'id' => 'disclaimer',
	'title' => 'Perhatian',
	'content' => "Untuk melakukan absen secara online, mohon perhatikan beberapa hal berikut ini:
				<ol>
					<li>Pastikan anda terhubung ke internet saat melakukan absen.</li>
					<li>Menentukan lokasi absen menggunakan teknologi <b>GPS</b>, jadi pastikan <i>gadget</i> 
						anda memiliki <b>GPS</b>, dan <b>GPS</b> dalam keadaan aktif.</li>
					<li>Nantinya, browser akan meminta akses ke lokasi anda melalui popup pemberitahuan.</li>
					<li>Jika anda terhubung ke internet, tetapi aplikasi ini tidak memiliki akses ke <b>GPS</b> anda, 
						maka penetapan koordinat lokasi menggunakan lokasi anda mendapatkan akses internet.</li>
					<li>Direkomendasikan membuka aplikasi <i>Live Attendance</i> di browser mobile/smartphone.</li>
				</ol>"
]); ?>

<?php $this->load->view('layouts/modal_alert', [
	'id' => 'modal-gps',
	'title' => 'Perhatian',
	'content' => "
		<img src='". base_url("/web/images/gps-logo.png") ."' class='img img-responsive center-block' width='200px' />
		<p class='text-center'>
			Mohon aktifkan GPS dan izinkan akses lokasi. Direkomendasikan membuka 
			aplikasi <i>Live Attendance</i> di browser mobile/smartphone.
		</p>
	"
]); ?>
