<?php

Class TemplateList Extends Module {

	public $templateOutput = DEFAULT_OUTPUT;
	public $template = '/admin/template/list';	

	public function __construct ($action, $id = 0, $limit = array(0, 20)) {
		switch ($action) {
			case 'getTemplateList':
				$Template = new Template();
				$TemplateList = $Template->getTemplates($limit);
				$TemplateCount = $Template->getTemplateCount();

				$this->template = '/admin/template/list';
				$this->output['templates'] = $TemplateList;
				$this->output['count'] = $LayoutCount['TemplateCount'];
				break;
		}
	}
}

?>