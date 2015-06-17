<?php

$Module = new Module();
$Module->addModule(new Settings('getModuleRights'), $return);
$Module->output();

?>