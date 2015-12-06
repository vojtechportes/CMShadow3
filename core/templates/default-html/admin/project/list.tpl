<div class="page-list full">
  <div class="clearfix"></div>
  <table class="table">
    <thead>
      <tr>
        <th>{_'pages_project_list_th_id'}</th>      
        <th>{_'pages_project_list_th_title'}</th>
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

  	</tr>	
  	<?php

  }
  ?>
    </tbody>
  </table>
</div>