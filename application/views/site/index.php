<div class="card light portlet-fit bordered">
	<div class="card-body greeting-board">
		<h3 class="page-title" style="font-weight: bold"> <span id="greetings"></span>,
			<?= def($this->session->userdata('detail_identity'), 'nama_depan') ?>!
            <br /><small id="timestamp"></small>
        </h3>

        <?php /*<small style="font-weight: bold">Shortcut</small><br />
        <?= anchor('/live-attendance', 'Live attendance', [
        	'class' => 'btn btn-default'
        ]); ?>
        <?= anchor('/cuti/permohonan', 'Permohonan cuti', [
        	'class' => 'btn btn-default'
        ]); ?>*/ ?>
	</div>
</div>
