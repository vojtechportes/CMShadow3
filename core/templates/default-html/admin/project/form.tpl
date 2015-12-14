<form method="<?php echo $return['object']->method ?>">
	<div class="row">
		<?php
		foreach ($return['views'] as $row) {
			echo $row;
		}
		?>

		<div class="clearfix"></div>
	</div>
	<hr>
</form>