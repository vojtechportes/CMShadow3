<?php

Class APIOutput Extends Module {

	public $templateOutput = DEFAULT_OUTPUT;
	public $template = '/admin/api/output';	

	public function output ($unescape = true) {
		parent::output($unescape);
	}
}

?>