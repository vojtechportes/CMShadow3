<?php

$Module = new Module();
$Module->addModule(new PageList('getPageTree'), $return);
$Module->output();

?>