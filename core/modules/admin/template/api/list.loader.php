<?php

$Module = new Module();
$Loader = new APIOutput();
$Loader->template = '/admin/template/api/list';
$Module->addModule($Loader, $return);
$Module->output(false);

?>