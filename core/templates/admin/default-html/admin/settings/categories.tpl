<div class="categories">
<?php foreach ($return["Categories"] as $category) { ?>
	<div class="col-md-4 col-sm-6 category-item">
		<a href="<?php echo $category["Path"]; ?>">
			<div class="<?php echo $category["Icon"]; ?>"></div>
			<div class="text">
				<p><?php echo $category["Title"]; ?></p>
			</div>
			<div class="clearfix"></div>
		</a>
	</div>
	<div class="clearfix visible-xs-block"></div>
<?php } ?>
</div>