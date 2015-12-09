<?php
global $Path;

if (!array_key_exists('path', $return))
	$return['path'] = $Path;

$return['path'] = Minimal::getPathParts($return['path']);
$return['pathCount'] = count($return['path']);

$Module = new Module();
$Module->template = '/admin/page/breadcrumbs';
$Module->addModule(false, $return);
$Module->output();

?>