<?php
	$Status = $return["StatusList"][$return['Project']['Status']];
?>
<div class="project-details" data-title="<?php echo $return['Project']['Name']; ?>">
	<strong class="project-label">{_'projects_project_details'}</strong><hr>
	<div class="clearfix"></div>
    <div class="row">
    	<div class="col-md-4 item">
    		<strong>{_'projects_project_details_state'}</strong>
    		<p><span class="badge <?php echo $Status[1]; ?>"><?php echo $Status[0]; ?></p>
    	</div>
    	<div class="col-md-4 item">
    		<strong>{_'projects_project_details_labels'}</strong>
    		<p></p>
    	</div>
    	<div class="col-md-4 item">
    		<strong>{_'projects_project_details_release_date'}</strong>
    		<p><?php echo $return['Project']['ReleaseDate']; ?></p>
    	</div>
    	<div class="clearfix"></div>
    	<div class="col-md-4 item list">
    		<strong>{_'projects_project_details_owners'}</strong>
    		<ul>
    		<?php foreach ($return['Users']['Owners'] as $user) { ?>
    			<li><a href="/admin/users/detail/<?php echo $user['User']; ?>"><?php echo $user['Name']; ?></a></li>
    		<?php } ?>
    		</ul>
    	</div>
    	<div class="col-md-4 item list">
    		<strong>{_'projects_project_details_editors'}</strong>
    		<ul>
    		<?php foreach ($return['Users']['Editors'] as $user) { ?>
    			<li><a href="/admin/users/detail/<?php echo $user['User']; ?>"><?php echo $user['Name']; ?></a></li>
    		<?php } ?>
    		</ul>
    	</div>
    </div>
	<strong class="project-label">{_'projects_project_details_description'}</strong><hr>
	<div class="clearfix"></div>
	<div class="row">
    	<div class="col-md-12">
    		<p><?php echo $return['Project']['Description']; ?></p>
    	</div>
    	<div class="clearfix"></div>
	</div>
</div>