<?php

Class PageList Extends Module {

	public $templateOutput = DEFAULT_OUTPUT;
	public $template = '/admin/page/list';	

	public function __construct ($action) {
		switch ($action) {
			case 'getPageTree':
				$Pages = new Page();
				$Pages->getPageTree();

				$this->template = '/admin/page/list';
				$this->output['pages'] = $Pages;
				$this->output['detailed'] = false;
				break;
			case 'getPageTreeDetailes':
				$Pages = new Page();
				$Pages->getPageTree(true);

				$this->template = '/admin/page/list';
				$this->output['pages'] = $Pages;
				$this->output['detailed'] = true;
				break;
		}
	}

	public function output () {
		parent::output();
	}
}

?>