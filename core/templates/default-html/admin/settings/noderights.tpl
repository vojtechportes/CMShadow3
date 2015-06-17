<?php
global $M;
?>
<div class="node-rights rights">
	<table class="table table-condensed">
	  <thead>
	    <tr>
	      <th>Page Name</th>
	      <?php foreach ($return['Groups'] as $group) { ?>
	      <th class="text-center"><?php echo $group['Name'] ?></th>
	      <?php } ?>
	    </tr>
	  </thead>
	  <tbody>
	    <?php foreach ($return["Nodes"] as $node) { ?>
	    <tr>
	      <th scope="row"><?php echo $node["Path"]; ?></th>
	      <?php foreach ($return['Groups'] as $group) { ?>
	      <td class="text-center"><?php
	      if (array_key_exists($group['ID'], $return['Rights'])) {
	      	if (in_array($node['Path'], $return['Rights'][$group['ID']])) { ?>
	      	<span class="glyphicon glyphicon-ok" data-role="{'action': 'remove', 'path': '<?php echo $node["Path"]; ?>', 'role': '<?php echo $group['ID'] ?>'}"></span>
	      	<?php } else { ?>
	      	<span class="glyphicon glyphicon-plus" data-role="{'action': 'add', 'path': '<?php echo $node["Path"]; ?>', 'role': '<?php echo $group['ID'] ?>'}"></span>
	      	<?php }
	      }
	      ?></td>
	      <?php } ?>
	    </tr>
	    <?php } ?>
	  </tbody>
	</table>
</div>