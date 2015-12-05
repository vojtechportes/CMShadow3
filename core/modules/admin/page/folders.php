<?php

$Module = new Module();
$Module->addModule(new PageList('getPageListByParent', $return['parent']), $return);
$Module->output();

?>