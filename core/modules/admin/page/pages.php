<?php
global $Path, $M;
if (array_key_exists('Path', $return)) {
	$path = $return['Path'];
} else {
	$path = $Path;
}

$id = $M->extractID($path);
if ($id === false) {
	$id = 0;
}

$Module = new Module();
$Module->addModule(new PageList('getPageList', $id), $return);
$Module->output();

?>