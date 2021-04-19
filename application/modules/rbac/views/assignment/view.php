<?php
$animateIcon = ' <i class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></i>';
?>

<h1 class="page-title"><?= $title ?></h1>

<div class="row">
    <div class="col-md-12">
        <div class="card light bordered">
            <div class="card-body">
            	<div class="row">
		            <div class="col-sm-5">
		                <div class="input-group">
		                    <input class="form-control search" data-target="available" placeholder="Cari permission yang belum terdaftar">
		                    <span class="input-group-btn">
		                        <?= $this->html->a('<span class="fa fa-sync"></span>', "/rbac/assignment/refresh/{$model->id}", [
		                            'class' => 'btn btn-default',
		                            'id' => 'btn-refresh'
		                        ]) ?>
		                    </span>
		                </div>
		                <select multiple size="20" class="form-control list" data-target="available"></select>
		            </div>
		            <div class="col-sm-2 text-center">
		                <br><br>
		                <?= $this->html->a('&gt;&gt;' . $animateIcon, "/rbac/assignment/assign/{$model->id}", [
		                    'class' => 'btn btn-success btn-assign ladda-button',
		                    'data-target' => 'available',
		                    'title' => 'Assign',
		                ]) ?><br><br>
		                <?= $this->html->a('&lt;&lt;' . $animateIcon, "/rbac/assignment/remove/{$model->id}", [
		                    'class' => 'btn btn-danger btn-assign ladda-button',
		                    'data-target' => 'assigned',
		                    'title' => 'Remove'
		                ]) ?>
		            </div>
		            <div class="col-sm-5">
		                <input class="form-control search" data-target="assigned" placeholder="Cari permission terdaftar">
		                <select multiple size="20" class="form-control list" data-target="assigned"></select>
		            </div>
		        </div>
            </div>
        </div>
    </div>
</div>
