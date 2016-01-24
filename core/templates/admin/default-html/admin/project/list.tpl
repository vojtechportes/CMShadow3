<div class="page-list full">
  <div class="clearfix"></div>
  <table class="table">
    <thead>
      <tr>
        <th>{_'projects_project_list_th_id'}</th>      
        <th>{_'projects_project_list_th_name'}</th>
        <th>{_'projects_project_list_th_createdAt'}</th>
        <th>{_'projects_project_list_th_modifiedAt'}</th>
        <th>{_'projects_project_list_th_status'}</th>        
        <th class="text-right">{_'projects_project_list_th_actions'}</th>

      </tr>
    </thead>
    <tbody>
  <?php
  foreach ($return['projects'] as $id => $project) {
  	$status = $return['statusList'][$project['Status']];
  	?>
  	<tr>
  		<td><?php echo $project['ID'] ?></td>
  		<td><?php echo $project['Name'] ?></td>
  		<td><?php echo $project['CreatedAt'] ?></td>
  		<td><?php echo $project['ModifiedAt'] ?></td>
  		<td><span class="badge <?php echo $status[1] ?>"><?php echo $status[0] ?></span></td>
  		<td class="text-right"><?php if ((int) $project['HasRights'] > 0) { ?><a href="<?php echo ADMIN_PATH ?>projects/edit/<?php echo $project['ID'] ?>" class="nolink"><span class="glyphicon-alt glyphicon-larger glyphicon-alt-in"></span></a><?php } ?></td>
  	</tr>	
  	<?php
  }
  ?>
    </tbody>
  </table>
</div>