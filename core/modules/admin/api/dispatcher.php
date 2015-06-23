<?php
$command 	= filter_input(INPUT_POST, "command");
$action 	= filter_input(INPUT_POST, "action");
$key 		= filter_input(INPUT_POST, "key");
$value 		= filter_input(INPUT_POST, "value");

$API = new API($command, $action, $key, $value);
$API = $API->proceed();

if ($API === 1) {
	$Message = new Module();
	$Message->addModule(new Message(), array("html" => "{_'settings_rights_success', sprintf($key)}", "class" => "alert-success", "OutputStyle" => $return["OutputStyle"], "OutputType" => $return["OutputType"], "Header" => 200));
	$Message->output();
} else {
	$Message = new Module();
	$Message->addModule(new Message(), array("html" => "{_'settings_rights_error', sprintf($key)}", "class" => "alert-warning", "OutputStyle" => $return["OutputStyle"], "OutputType" => $return["OutputType"], "Header" => 200));
	$Message->output();	
}
?>