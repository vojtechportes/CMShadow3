<?php

$Module = new Module();
$Module->addModule(new Navigation($return["name"]), $return);
$Module->output();

?>