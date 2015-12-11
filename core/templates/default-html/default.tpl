<?php global $Path; ?>
<?php
	if (filter_input(INPUT_GET, "logout") == 1) {
		$Logout = new User();
		$Logout->deleteUserSession();
	}
	global $M;
	//$M->debug($return);
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="robots" content="noindex, nofollow">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<?php foreach ($return["Styles"] as $style) { ?>
			<link rel="stylesheet" type="text/css" href="<?php echo BASE_PATH.$style; ?>">
		<?php } ?>
	</head>
	<body class="<?php echo str_replace('/', '--', substr(strtolower($Path), 1)); ?>">
		<div id="body-inner">
			<?php print_slot("header", $return["Content"]); ?>
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="page-header">
						<h1 class="pull-left"><?php  echo print_property("Title", $return["NodeConfig"]); ?></h1>
						<div class="pull-right"><?php print_slot("breadcrumbs", $return["Content"]); ?></div>
						<div class="clearfix"></div>
						</div>
						<?php print_slot("main", $return["Content"]); ?>
					</div>
				</div>
			</div>
		</div>
		<?php foreach ($return["Scripts"] as $key => $script) { ?>
			<?php if (!is_array($script)) { ?>
				<script src="<?php echo BASE_PATH.$script; ?>"></script>
				<?php unset($return["Scripts"][$key]); ?>
			<?php } ?>
		<?php } ?>
		<?php if (!empty($return["Scripts"])) { ?>
			<div id="__customScripts" data-scripts='<?php echo json_encode(flatten_array($return["Scripts"])); ?>'></div>
		<?php } ?>
	</body>
</html>