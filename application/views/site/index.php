<div class="card bordered">
	<div class="detail-wrapper greeting-board">
		<h3 class="page-title" style="font-weight: bold"> <span id="greetings"></span>,
			<?= def($this->session->userdata('identity'), 'username') ?>!
            <br /><small id="timestamp"></small>
        </h3>
	</div>
</div>
