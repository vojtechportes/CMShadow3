<div class="node-rights rights">
	<table class="table table-condensed">
	  <thead>
	    <tr>
	      <th>{_'settings_node_rights_title'}</th>
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
	      	<span class="glyphicon glyphicon-ok" data-api='{"command": "settingsNodeRightsAssign", "action": "delete", "key": "<?php echo $node['Path']; ?>", "value": "<?php echo $group['ID'] ?>"}'></span>
	      	<?php } else { ?>
	      	<span class="glyphicon glyphicon-plus" data-api='{"command": "settingsNodeRightsAssign", "action": "set", "key": "<?php echo $node['Path']; ?>", "value": "<?php echo $group['ID'] ?>"}'></span>
	      	<?php }
	      }
	      ?></td>
	      <?php } ?>
	    </tr>
	    <?php } ?>
	  </tbody>
	</table>
</div>