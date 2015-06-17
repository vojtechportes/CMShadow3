<?php

Class Code Extends Minimal {

	public $html;
	public $templateOutput = DEFAULT_OUTPUT;
	public $template = '/code/show';	

	public function __construct ($html) {
		$this->html = $html;
	}

	public function output () {
		$output = array(
			"html" => $this->html,
			"object" => $this
		);

		ob_start();
		parent::load(DEFAULT_TEMPLATE_PATH.$this->templateOutput.$this->template, $output);
		$html = ob_get_contents(); ob_end_clean();
		echo $html;
	}
}

?>