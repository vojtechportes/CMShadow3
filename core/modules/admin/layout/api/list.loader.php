<?php

$Module = new Module();
$Loader = new APIOutput();
$Loader->template = '/admin/layout/api/list';
$Module->addModule($Loader, $return);
$Module->output(false);

?>