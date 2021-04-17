<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">Custom Link</div>
		<div class="tools">
			<a href="javascript:;" class="collapse"> </a>
		</div>
	</div>
	<div class="portlet-body">
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
