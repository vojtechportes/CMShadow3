<div class="breadcrumbs">
	<ol class="breadcrumb">
		<?php
		$path = '/';
		foreach ($return['path'] as $key => $part) {
			$path .= "{$part}/";
			if ($key === $return['pathCount'] - 1) {
				echo "<li class=\"active\">{$part}</li>";
			} else {
				echo "<li><a href=\"{$path}\">{$part}</a></li>";				
			}
		}
		?>
	</ol>
	<div class="clearfix"></div>
</div>