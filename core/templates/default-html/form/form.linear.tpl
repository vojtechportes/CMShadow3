<div class="<?php if ($return['object']->getAttribute("hasError")) { echo " has-errors"; } ?>">
	<?php if ($return['object']->getAttribute("hasError")) { ?>
	<div class="alert alert-danger" role="alert">
		<ul>
		<?php foreach ($return['object']->getAttribute("errorMessages") as $message) {
			echo "<li>", $message, "</li>";
		} ?>
		</ul>
	</div>
	<?php } ?>
	<div class="form-group">
		<?php echo $return['label']; ?>
		<?php echo $return['input']; ?>
	</div>
</div>