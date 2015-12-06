<div class="page-list full">
  <div class="clearfix"></div>
  <table class="table">
    <thead>
      <tr>
        <th>{_'pages_project_list_th_id'}</th>      
        <th>{_'pages_project_list_th_name'}</th>
        <th>{_'pages_project_list_th_createdAt'}</th>
        <th>{_'pages_project_list_th_modifiedAt'}</th>
        <th>{_'pages_project_list_th_status'}</th>        
        <th class="text-right">{_'pages_project_list_th_actions'}</th>

      </tr>
    </thead>
    <tbody>
  <?php
  foreach ($return['projects'] as $id => $project) {
  	?>
  	<tr>
  		<td><?php echo $project['ID'] ?></td>
  		<td><?php echo $project['Name'] ?></td>
  		<td><?php echo $project['CreatedAt'] ?></td>
  		<td><?php echo $project['ModifiedAt'] ?></td>
  		<td></td>
  		<td></td>
  	</tr>	
  	<?php

  }
  ?>
    </tbody>
  </table>
</div>