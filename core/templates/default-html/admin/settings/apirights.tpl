<div class="module-rights rights">
	<table class="table table-condensed">
	  <thead>
	    <tr>
	      <th>{_'settings_module_rights_title'}</th>
	      <?php foreach ($return['Groups'] as $group) { ?>
	      <th class="text-center"><?php echo $group['Name'] ?></th>
	      <?php } ?>
	    </tr>
	  </thead>
	  <tbody>
	    <?php foreach ($return["Commands"] as $command) { ?>
	    <tr>
	      <th scope="row"><?php echo $command; ?></th>
	      <?php foreach ($return['Groups'] as $group) { ?>
	      <td class="text-center"><?php
	      if (array_key_exists($group['ID'], $return['Rights'])) {
	      	if (in_array($command, $return['Rights'][$group['ID']])) { ?>
	      	<span class="glyphicon glyphicon-ok" data-api='{"command": "settingsAPIRightsAssign", "action": "delete", "path": "<?php echo $command; ?>", "role": "<?php echo $group['ID'] ?>"}'></span>
	      	<?php } else { ?>
	      	<span class="glyphicon glyphicon-plus" data-api='{"command": "settingsAPIRightsAssign", "action": "set", "path": "<?php echo $command; ?>", "role": "<?php echo $group['ID'] ?>"}'></span>
	      	<?php }
	      }
	      ?></td>
	      <?php } ?>
	    </tr>
	    <?php } ?>
	  </tbody>
	</table>
</div>