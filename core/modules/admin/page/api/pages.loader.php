<?php
global $Path;

$Module = new Module();
$Loader = new APIOutput();
$Loader->template = '/admin/page/api/pages';
$Module->addModule($Loader, $return + array("Path" => $Path));
$Module->output(false);

?>