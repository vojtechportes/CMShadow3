<?php
global $M;

$Slots = $return['Layout']['Slots'];
$Layout = $return['Layout']['Layout'];
?>

<div class="layout-slots jumbotron">
	<?php
	$PrintSlots = function ($parent = 0) use (&$Slots, &$PrintSlots) {
		foreach ($Slots as $key => $slot) {
			if ((int) $slot['Parent'] === $parent) {
				?>
				<div class="panel panel-default">
					<div class="panel-heading">
						<?php echo $slot['Name']; ?>
						<div class="pull-right">
        					<a href="#" data-add data-toggle="modal" data-target="#slotLayoutForm" data-arguments='{"slotId": "<?php echo $slot['ID']; ?>", "type": "subordinate"}' class="nolink"><span class="glyphicon-alt glyphicon-larger glyphicon-alt-plus"></span></a>							
        					<a href="#" data-delete data-toggle="modal" data-target="#slotDelete" data-arguments='{"slotId": "<?php echo $slot['ID']; ?>", "slotName": "<?php echo $slot['Name']; ?>"}' class="nolink"><span class="glyphicon-alt glyphicon-larger glyphicon-alt-cross"></span></a>						
        					<a href="#" data-edit data-toggle="modal" data-target="#slotLayoutForm" data-arguments='{"slotId": "<?php echo $slot['ID']; ?>", "type": "edit"}' class="nolink"><span class="glyphicon-alt glyphicon-larger glyphicon-alt-in"></span></a>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="panel-body">
						<?php
						if ((int) $slot['numChildSlots'] > 0) {
							$PrintSlots((int) $slot['ID']);
						}
						?>
					</div>
				</div>
				<?php
			}			
		}
	};
	echo $PrintSlots();
	?>
</div>