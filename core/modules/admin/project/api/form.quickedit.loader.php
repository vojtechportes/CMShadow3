<?php
global $Path;

$Module = new Module();
$Loader = new APIOutput();
$Loader->template = '/admin/project/api/form.quickedit';
$Module->addModule($Loader, $return + array("Path" => $Path));
$Module->output(false);

?>