<?php

$Module = new Module();
$Loader = new APIOutput();
$Loader->template = '/admin/template/api/controls';
$Module->addModule($Loader, $return);
$Module->output(false);

?>