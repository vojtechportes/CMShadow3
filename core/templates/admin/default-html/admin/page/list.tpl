<div class="page-list full">
  <div class="clearfix"></div>
  <table class="table">
    <thead>
      <tr>
        <th>{_'pages_page_list_th_title'}</th>
        <th>{_'pages_page_list_th_createdAt'}</th>
        <th>{_'pages_page_list_th_modifiedAt'}</th>
        <th>{_'pages_page_list_th_status'}</th>
        <th class="text-right">{_'pages_page_list_th_actions'}</th>

      </tr>
    </thead>
    <tbody>
  <?php
  foreach ($return['pages'] as $id => $page) {
  	?>
  	<tr>
  		<td><span style="padding-left: <?php echo $page['Depth'] * 20 ?>px; display: inline-block;"><span title="<?php echo $page['ID']; ?>" class="glyphicon-alt glyphicon-larger <?php if ((int) $page['numChildPages'] === 0) { echo 'glyphicon-alt-file'; } else { echo 'glyphicon-alt-folder-black'; } ?>"></span> <?php echo $page['Title'] ?></span></td>
  		<td><?php echo $page['CreatedAt'] ?></td>
  		<td><?php echo $page['ModifiedAt'] ?></td>
  		<td>
        <span class="glyphicon-alt glyphicon-larger glyphicon-alt-eye <?php if ((int) $page['Visible'] === 0) { echo ' not-visible'; } ?>"></span>
        <span class="glyphicon-alt glyphicon-larger glyphicon-alt-<?php if ((int) $page['Locked'] === 1) { echo 'lock'; } else { echo 'unlock'; } ?>"></span>

      </td>
  		<td class="text-right"><span class="glyphicon-alt glyphicon-larger glyphicon-alt-edit-frame"></span><span class="glyphicon-alt glyphicon-larger glyphicon-alt-ok"></span></td>
  	</tr>	
  	<?php

  }
  ?>
    </tbody>
  </table>
</div>