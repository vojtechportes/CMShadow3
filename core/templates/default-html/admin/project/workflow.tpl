<div class="workflow">
	<div class="btn-group btn-group-lg pull-left" role="group">
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
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">{_'project_status_workflow_title'}</h4>
			</div>
			<div class="modal-body">
				<div class="workflow-preview">
					<div class="level">
						<span class="badge badge-info">{_'projects_status_type_1'}</span>
						<div class="clearfix"></div>
					</div>
					<div class="level">
						<span class="badge badge-empty">{_'projects_status_type_2'}</span>
						<span class="badge badge-primary">{_'projects_status_type_3'}</span>
						<span class="badge badge-warning">{_'projects_status_type_4'}</span>
						<div class="clearfix"></div>
					</div>
					<div class="level">
						<span class="badge badge-success">{_'projects_status_type_5'}</span>
						<span class="badge badge-canceled">{_'projects_status_type_6'}</span>
						<div class="clearfix"></div>
					</div>
					<div class="level">
						<span class="badge badge-warning">{_'projects_status_type_7'}</span>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>