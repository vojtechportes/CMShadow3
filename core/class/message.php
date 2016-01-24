<?php

Class Message Extends Module {

	public $templateOutput = DEFAULT_OUTPUT;
	public $template = '/message/show';	

	public function __construct () {
		if (!array_key_exists("class", $this->output))
			$this->output["class"] = "alert-success";
	}

	public function output ($unescape = true) {
		parent::output($unescape);
	}
}

?>