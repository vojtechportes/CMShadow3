<?php

Class PageList Extends Module {

	public $templateOutput = DEFAULT_OUTPUT;
	public $template = '/admin/page/list';	

	public function __construct ($action, $id = 0) {
		switch ($action) {
			case 'getPageTree':
				$Pages = new Page();
				$Pages->getPageTree(false, $id);

				$this->template = '/admin/page/list';
				$this->output['pages'] = $Pages->pageTree;
				$this->output['detailed'] = false;
				break;
			case 'getPageTreeDetailes':
				$Pages = new Page();
				$Pages->getPageTree(true, $id);

				$this->template = '/admin/page/list';
				$this->output['pages'] = $Pages->pageTree;
				$this->output['detailed'] = true;
				break;
		}
	}

	public function output () {
		parent::output();
	}
}

?>