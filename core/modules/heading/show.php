<?php

global $M;
/*$M->debug($return);*/

if (empty($return['class']))
	$return['class'] = '';

if (empty($return['id']))
	$return['id'] = false;

$heading = new Heading ($return['level'], $return['html'], $return['class'], $return['id']);
$heading->output();


?>