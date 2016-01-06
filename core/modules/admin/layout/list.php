<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$Module = new Module();
$Module->addModule(new LayoutList('getLayoutList'), $return);
$Module->output();

?>