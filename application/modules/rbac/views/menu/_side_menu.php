<div class="card light bordered">
	<div class="card-header">
		<div class="card-title">Custom Link</div>

		<div class="card-tools">
			<button type="button" class="btn btn-tool" data-card-widget="collapse">
			<i class="fas fa-minus"></i>
			</button>
		</div>
	</div>
	<div class="card-body">
		<div class="form-group">
			<label>URL</label>
			<input type="text" name="url" id="url" class="form-control" placeholder="http://" value="http://" />
		</div>
		<div class="form-group">
			<label>Link Text</label>
			<input type="text" name="label" id="label" class="form-control" placeholder="Sample text" />
		</div>
		<div class="row">
			<div class="col-sm-12">
				<?php echo $this->html->submitButton('Add to Menu', ['class' => 'btn btn-default pull-right', 'id' => 'add-custom-to-menu']) ?>
			</div>
		</div>
	</div>
</div>
