<?php

$command 	= filter_input(INPUT_POST, "command");
$action 	= filter_input(INPUT_POST, "action");
$key 		= filter_input(INPUT_POST, "key");
$value 		= filter_input(INPUT_POST, "value");

$API = new API($command, $action, $key, $value);
$API->proceed();

?>