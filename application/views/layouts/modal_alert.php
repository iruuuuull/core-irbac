<!-- Modal -->
<div id="<?= $id ?? '' ?>" class="modal fade <?= $class ?? '' ?>" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?= $title ?? '' ?></h5>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<?php /*Untuk melakukan absen secara online, mohon perhatikan beberapa hal berikut ini:
				<ol>
					<li>Pastikan anda terhubung ke internet saat melakukan absen.</li>
					<li>Menentukan lokasi absen menggunakan teknologi <b>GPS</b>, jadi pastikan <i>gadget</i> 
						anda memiliki <b>GPS</b>, dan <b>GPS</b> dalam keadaan aktif.</li>
					<li>Nantinya, browser akan meminta akses ke lokasi anda melalui popup pemberitahuan.</li>
					<li>Jika anda terhubung ke internet, tetapi aplikasi ini tidak memiliki akses ke <b>GPS</b> anda, 
						maka penetapan koordinat lokasi menggunakan lokasi anda mendapatkan akses internet.</li>
				</ol>*/ ?>
				<?= $content ?? '' ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?= $text_close ?? 'Tutup' ?></button>
			</div>
		</div>

	</div>
</div>