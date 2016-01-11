<?php
global $M, $Path;

if (!array_key_exists('Path', $return))
	$return['Path'] = $Path;

$id = $M->extractID($return['Path']);

if ($id) {
	$Layout = new Layout();
	$Layout = $Layout->getLayoutByIdDetailed($id);

	$Module = new Module();
	$Module->template = '/admin/layout/slots';
	$Module->addModule(false, $return + array("Layout" => $Layout));
	$Module->output();
}

?>