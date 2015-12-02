<?php global $M; ?>
<table class="table table-condensed">
  <thead>
    <tr>
      <th>{_'pages_page_list_th_id'}</th>
      <th>{_'pages_page_list_th_title'}</th>
      <th>{_'pages_page_list_th_createdAt'}</th>
      <th>{_'pages_page_list_th_modifiedAt'}</th>
      <th>{_'pages_page_list_th_status'}</th>
      <th>{_'pages_page_list_th_actions'}</th>

    </tr>
  </thead>
  <tbody>
<?php
foreach ($return['pages'] as $id => $page) {
	?>
	<tr>
		<td><?php echo $page['ID'] ?></td>
		<td><?php echo $page['Title'] ?></td>
		<td><?php echo $page['CreatedAt'] ?></td>
		<td><?php echo $page['ModifiedAt'] ?></td>
		<td></td>
		<td></td>
	</tr>	
	<?php

}
?>
  </tbody>
</table>