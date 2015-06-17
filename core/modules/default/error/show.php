<?php

$Module = new Module();
$Module->addModule(new Message() , $return);
$Module->template = "/default/error/show";
$Module->output();

?>