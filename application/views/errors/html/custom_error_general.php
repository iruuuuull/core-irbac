<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="error-page">
	<h2 class="headline text-warning"> <?= $status_code ?></h2>

	<div class="error-content">
		<h3><i class="fas fa-exclamation-triangle text-warning"></i> <?= $heading ?>.</h3>

		<p><?= $message ?>.</p>
	</div>
	<!-- /.error-content -->
</div>
<!-- /.error-page -->
