<?php

$Module = new Module();
$Loader = new APIOutput();
$Loader->template = '/admin/page/api/list';
$Module->addModule($Loader, $return);
$Module->output(false);

?>