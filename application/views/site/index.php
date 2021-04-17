<div class="portlet light portlet-fit bordered">
	<div class="portlet-body greeting-board">
		<h1 class="page-title" style="font-weight: bold"> <span id="greetings"></span>, 
			<?= def($this->session->userdata('detail_identity'), 'nama_depan') ?>!
            <br /><small id="timestamp"></small>
        </h1>

        <small style="font-weight: bold">Shortcut</small><br />
        <?= anchor('/live-attendance', 'Live attendance', [
        	'class' => 'btn btn-default'
        ]); ?>
        <?= anchor('/cuti/permohonan', 'Permohonan cuti', [
        	'class' => 'btn btn-default'
        ]); ?>
	</div>
</div>

<h1 class="page-title">Employee Head Count</h1>
<div class="row">
	<?php
		$style = [
			['color' => 'green-sharp', 'icon' => 'icon-pie-chart'],
			['color' => 'red-haze', 'icon' => 'icon-pie-chart'],
			['color' => 'blue-sharp', 'icon' => 'icon-bar-chart'],
			['color' => 'purple-soft', 'icon' => 'icon-bar-chart'],
		];

		$i = 0;
		foreach ($employee_summary as $key => $summary):
			# Lanjutkan loop untuk total
			if (strpos($key, 'total') !== false) {
				continue;
			}

			$total = strstr($key, '_', true) . '_total';
			$title = strstr($key, '_');
			$percentage = round(($summary / $employee_summary[$total]) * 100);
	?>

	    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
	        <div class="dashboard-stat2 ">
	            <div class="display">
	                <div class="number">
	                    <h3 class="font-<?= $style[$i]['color'] ?? '' ?>">
	                        <span data-counter="counterup" data-value="<?= $summary ?>">
	                        	<?= $summary ?></span>
	                        <small class="font-<?= $style[$i]['color'] ?? '' ?>"></small>
	                    </h3>
	                    <small><?= strtoupper(str_replace('_', ' ', $title)) ?></small>
	                </div>
	                <div class="icon">
	                    <i class="<?= $style[$i]['icon'] ?? '' ?>"></i>
	                </div>
	            </div>
	            <div class="progress-info">
	                <div class="progress">
	                    <span style="width: <?= $percentage ?>%;" class="progress-bar progress-bar-success <?= $style[$i]['color'] ?? '' ?>">
	                        <span class="sr-only"><?= $percentage ?>% Percentage</span>
	                    </span>
	                </div>
	                <div class="status">
	                    <div class="status-title"> Percentage </div>
	                    <div class="status-number"> <?= $percentage ?>% </div>
	                </div>
	            </div>
	        </div>
	    </div>

	<?php 
		if (($i + 1) === 4) {
			echo "</div><div class='row'>";
		}

		$i++;
		endforeach;
	?>
</div>
