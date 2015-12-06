<?php

if (!array_key_exists('limit', $return))
	$return['limit'] = array(LIMIT_OFFSET, LIMIT_CNT);

$Module = new Module();
$Module->addModule(new ProjectList('getProjectList', 0, $return['limit']), $return);
$Module->output();

?>