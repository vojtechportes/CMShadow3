<?php
global $Path;

$Module = new Module();
$Loader = new APIOutput();
$Loader->template = '/admin/layout/api/form.slot';
$Module->addModule($Loader, $return + array("Path" => $Path));
$Module->output(false);

?>