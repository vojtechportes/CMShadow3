<?php
$command 	= filter_input(INPUT_POST, "command");
$action 	= filter_input(INPUT_POST, "action");
$key 		= filter_input(INPUT_POST, "key");
$value 		= filter_input(INPUT_POST, "value");

$API = new API($command, $action, $key, $value);
$API = $API->proceed();

$v1 = $API['key'];
$v2 = $API['action'];

if ($API['status'] === 1) {
	$Message = new Module();
	$Message->addModule(new Message(), array("html" => "{_'settings_rights_success', sprintf([\"$v1\", \"$v2\"])}", "class" => "alert-success", "OutputStyle" => $return["OutputStyle"], "OutputType" => $return["OutputType"], "Header" => 200));
	$Message->output();
} else if ($API['status'] === 0) {
	$Message = new Module();
	$Message->addModule(new Message(), array("html" => "{_'settings_rights_error', sprintf([\"$v1\", \"$v2\"])}", "class" => "alert-warning", "OutputStyle" => $return["OutputStyle"], "OutputType" => $return["OutputType"], "Header" => 400));
	$Message->output();	
} else if ($API['status'] === -1) {
	$Message = new Module();
	$Message->addModule(new Message(), array("html" => "{_'settings_rights_error_unauthorized'}", "class" => "alert-danger", "OutputStyle" => $return["OutputStyle"], "OutputType" => $return["OutputType"], "Header" => 403));
	$Message->output();		
}
?>