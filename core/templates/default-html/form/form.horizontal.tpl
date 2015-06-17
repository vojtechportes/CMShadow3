<div class="<?php if ($return['object']->getAttribute("hasError")) { echo " has-errors"; } ?>">
	<?php if ($return['object']->getAttribute("hasError")) { ?>
	<div class="alert alert-success" role="alert">
		<ul>
		<?php foreach ($return['object']->getAttribute("errorMessages") as $message) {
			echo "<li>", $message, "</li>";
		} ?>
		</ul>
	</div>
	<?php } ?>
	<div class="form-group">
		<?php echo $return['label']; ?>
		<div class="col-md-8">
			<?php echo $return['input']; ?>
		</div>
	</div>
</div>