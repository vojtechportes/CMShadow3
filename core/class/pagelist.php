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
			case 'getPageTreeDetailed':
				$Pages = new Page();
				$Pages->getPageTree(true, $id);

				$this->template = '/admin/page/list';
				$this->output['pages'] = $Pages->pageTree;
				$this->output['detailed'] = true;
				break;
			case 'getPageList':
				$Pages = new Page();
				$Pages->version = $id;
				$PageList = $Pages->getPageList(false);

				$this->template = '/admin/page/pages';
				$this->output['pages'] = $PageList;
				$this->output['detailed'] = false;
				break;
			case 'getPageList':
				$Pages = new Page();
				$Pages->version = $id;
				$PageList = $Pages->getPageListDetailed(false);

				$this->template = '/admin/page/pages';
				$this->output['pages'] = $PageList;
				$this->output['detailed'] = false;
				break;
			case 'getPageListByParent':
				$Pages = new Page();
				$List = $Pages->getPageList($id);

				if ((int) $id !== 0) {
					$ParentPage = $Pages->getPageById($id);
					$this->output['from'] = $ParentPage['Parent'];
				}

				$this->template = '/admin/page/folders';
				$this->output['pages'] = $List;
				$this->output['detailed'] = false;
				break;
			case 'getPageListByParentDetailed':
				$Pages = new Page();
				$List = $Pages->getPageListDetailed($id);

				if ((int) $id !== 0) {
					$ParentPage = $Pages->getPageById($id);
					$this->output['from'] = $ParentPage['Parent'];
				}

				$this->template = '/admin/page/folders';
				$this->output['pages'] = $List;
				$this->output['detailed'] = true;
				break;
		}
	}

	public function output () {
		parent::output();
	}
}

?>