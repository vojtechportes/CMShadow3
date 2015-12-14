<div class="workflow">
	<div class="btn-group btn-group-lg pull-left" role="group">
	  <?php if (!empty($return['workflow'])) { ?>
	  	<?php foreach ($return['workflow'] as $item) { ?>
	  		<a href="#" class="btn btn-default"><span><?php echo $item ?></span></a>
	  	<?php } ?>
	  <?php } ?>
	</div>
	<div class="pull-right">
		<a href="#">{_'projects_status_display_workflow'}</a> 
	</div>
	<div class="clearfix"></div>
	<hr>
</div>