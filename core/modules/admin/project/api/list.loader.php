<?php

$Module = new Module();
$Loader = new APIOutput();
$Loader->template = '/admin/project/api/list';
$Module->addModule($Loader, $return);
$Module->output(false);

?>