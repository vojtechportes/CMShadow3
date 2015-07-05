<form method="<?php echo $return['object']->method ?>">

	<?php
	foreach ($return['views'] as $row) {
		echo $row;
	}
	?>
	
</form>
