<?php
global $Path;

$Module = new Module();
$Loader = new APIOutput();
$return['path'] = $Path;
$Loader->template = '/admin/page/api/breadcrumbs';
$Module->addModule($Loader, $return);
$Module->output(false);

?>