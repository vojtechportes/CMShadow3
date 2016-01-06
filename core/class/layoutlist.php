<?php

Class LayoutList Extends Module {

	public $templateOutput = DEFAULT_OUTPUT;
	public $template = '/admin/layout/list';	

	public function __construct ($action, $id = 0, $limit = array(0, 20)) {
		switch ($action) {
			case 'getLayoutList':
				$Layouts = new Layout();
				$LayoutList = $Layouts->getLayouts($limit);
				$LayoutCount = $Layouts->getLayoutCount();

				$this->template = '/admin/layout/list';
				$this->output['layouts'] = $LayoutList;
				$this->output['count'] = $LayoutCount['LayoutCount'];
				break;
		}
	}
}

?>