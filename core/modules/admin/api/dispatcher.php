<?php
$command 	= filter_input(INPUT_POST, "command");
$action 	= filter_input(INPUT_POST, "action");
$key 		= filter_input(INPUT_POST, "key");
$value 		= filter_input(INPUT_POST, "value");
$type 		= filter_input(INPUT_POST, "type");
$arguments 		= filter_input(INPUT_POST, "arguments", FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
$module 		= filter_input(INPUT_POST, "module");
$message 		= filter_input(INPUT_POST, "message");

if (!isset($key)) $key = false;
if (!isset($value)) $value = false;
if (!isset($module)) $module = false;
if (!isset($action)) $action = false;
if (!isset($arguments) || !is_array($arguments)) $arguments = false;
if (!isset($message)) { $message = true; } else { if ($message === "false") { $message = false; } else { $message = true; }}

$API = new API();
$API->command = $command;
$API->key = $key;
$API->value = $value;
$API->action = $action;
$API->type = $type;
$API->arguments = $arguments;
$API->module = $module;
$API = $API->proceed();

$v1 = $API['key'];
$v2 = $API['action'];

//var_dump($API);

if ($API['status'] === 1) {
	if ($message) {
		$Message = new Module();
		$Message->addModule(new Message(), array("html" => "{_'api_success', sprintf([\"$v1\", \"$v2\"])}", "class" => "alert-success", "OutputStyle" => $return["OutputStyle"], "OutputType" => $return["OutputType"], "Header" => 200));
		$Message->output();
	}

	if ($API['output'] !== false) {
		$APIOutput = new Module();
		$APIOutput->addModule(new APIOutput(), $API['output'] + array("OutputStyle" => $return["OutputStyle"], "OutputType" => $return["OutputType"]));
		$APIOutput->output();
	}
} else if ($API['status'] === 0) {
	if ($message) {
		$Message = new Module();
		$Message->addModule(new Message(), array("html" => "{_'api_error', sprintf([\"$v1\", \"$v2\"])}", "class" => "alert-warning", "OutputStyle" => $return["OutputStyle"], "OutputType" => $return["OutputType"], "Header" => 400));
		$Message->output();	
	}
} else if ($API['status'] === -1) {
	$Message = new Module();
	$Message->addModule(new Message(), array("html" => "{_'api_error_unauthorized'}", "class" => "alert-danger", "OutputStyle" => $return["OutputStyle"], "OutputType" => $return["OutputType"], "Header" => 403));
	$Message->output();		
}
?>