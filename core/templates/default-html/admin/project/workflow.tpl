<div class="workflow">
	<div class="btn-group btn-group pull-left" role="group">
	  <?php if (!empty($return['workflow'])) { ?>
	  	<?php foreach ($return['workflow'] as $item) { ?>
	  		<a href="#" class="btn btn-default"><span><?php echo $item ?></span></a>
	  	<?php } ?>
	  <?php } ?>
	</div>
	<div class="pull-right">
		<a href="#" data-toggle="modal" data-target="#workflowpreview">{_'projects_status_display_workflow'}</a> 
	</div>
	<div class="clearfix"></div>
	<hr>
</div>

<div class="modal fade" id="workflowpreview" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="{_'default_modal_close'}"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">{_'project_status_workflow_title'}</h4>
			</div>
			<div class="modal-body">
				<div class="workflow-preview">
					<div class="row level">
						<div class="col-xs-4 col-xs-offset-4">
							<span class="badge badge-info <?php if ($return['status'] === 1) { echo 'badge-active'; } ?>">{_'projects_status_type_1'}</span>
						</div>
						<div class="clearfix"></div>
						<canvas class="workflow_canvas col-xs-12" id="c1" data-directions='{"0": ["center", "left", true], "1": ["center", "center", true], "2": ["center", "right", true]}'></canvas>
						<div class="clearfix"></div>
					</div>
					<div class="row level">
						<div class="col-xs-4">
							<span class="badge badge-empty <?php if ($return['status'] === 2) { echo 'badge-active'; } ?>">{_'projects_status_type_2'}</span>
						</div>
						<div class="col-xs-4">
							<span class="badge badge-primary <?php if ($return['status'] === 3) { echo 'badge-active'; } ?>">{_'projects_status_type_3'}</span>
						</div>
						<div class="col-xs-4">
							<span class="badge badge-warning <?php if ($return['status'] === 4) { echo 'badge-active'; } ?>">{_'projects_status_type_4'}</span>
						</div>
						<div class="clearfix"></div>
						<canvas class="workflow_canvas col-xs-12" id="c1" data-directions='{"0": ["center", "center"], "1": ["right", "right"]}'></canvas>
						<div class="clearfix"></div>
					</div>
					<div class="row level">
						<div class="col-xs-4 col-xs-offset-4">
							<span class="badge badge-success <?php if ($return['status'] === 5) { echo 'badge-active'; } ?>">{_'projects_status_type_5'}</span>
						</div>
						<div class="col-xs-4">
							<span class="badge badge-canceled <?php if ($return['status'] === 6) { echo 'badge-active'; } ?>">{_'projects_status_type_6'}</span>
						</div>
						<div class="clearfix"></div>
						<canvas class="workflow_canvas col-xs-12" id="c1" data-directions='{"0": ["center", "left"], "1": ["center", "center"]}'></canvas>
						<div class="clearfix"></div>
					</div>
					<div class="row level">
						<div class="col-xs-4">
							<span class="badge badge-warning<?php if ($return['status'] === 7) { echo 'badge-active'; } ?>">{_'projects_status_type_7'}</span>
						</div>
						<div class="col-xs-4">
							<span class="badge badge-info<?php if ($return['status'] === 8) { echo 'badge-active'; } ?>">{_'projects_status_type_8'}</span>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">{_'default_modal_close'}</button>
			</div>
		</div>
	</div>
</div>