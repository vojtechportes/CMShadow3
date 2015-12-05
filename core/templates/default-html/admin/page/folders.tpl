<?php
global $M;
$M->debug($return);
?>
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
   	<tr>
  		<td colspan="5" <?php if (array_key_exists('id', $return['arguments'])) { ?> data-haschildpages data-arguments='{"parent": "<?php echo $return['arguments']['id']; ?>"}' <?php } ?>>
  			<span class="glyphicon-alt glyphicon-alt-back glyphicon-larger"></span><span> {_'pages_page_list_level_up'}</span>
  		</td>
  	</tr>   
  <?php
  foreach ($return['pages'] as $id => $page) {
  	?>

  	<tr>
  		<td <?php if ((int) $page['numChildPages'] > 0) { echo "data-haschildpages data-arguments='{\"parent\": \"{$page['ID']}\", \"id\": \"{$page['Parent']}\"}'"; } ?>><span style="padding-left: <?php echo $page['Depth'] * 20 ?>px; display: inline-block;"><span title="<?php echo $page['ID']; ?>" class="glyphicon-alt glyphicon-larger <?php if ((int) $page['numChildPages'] === 0) { echo 'glyphicon-alt-file'; } else { echo 'glyphicon-alt-folder-black'; } ?>"></span> <?php echo $page['Title'] ?></span></td>
  		<td><?php echo $page['CreatedAt'] ?></td>
  		<td><?php echo $page['ModifiedAt'] ?></td>
  		<td>
        <span class="glyphicon-alt glyphicon-larger glyphicon-alt-eye <?php if ($page['Visible'] === NULL) { echo ' not-visible'; } ?>"></span>
        <span class="glyphicon-alt glyphicon-larger glyphicon-alt-<?php if ($page['Locked'] === NULL) { echo 'lock'; } else { echo 'unlock'; } ?>"></span>

      </td>
  		<td class="text-right"><span class="glyphicon-alt glyphicon-larger glyphicon-alt-edit-frame"></span><span class="glyphicon-alt glyphicon-larger glyphicon-alt-ok"></span></td>
  	</tr>	
  	<?php

  }
  ?>
    </tbody>
  </table>
</div>