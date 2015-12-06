<?php

Class ProjectList Extends Module {

	public $templateOutput = DEFAULT_OUTPUT;
	public $template = '/admin/project/list';	

	public function __construct ($action, $id = 0, $limit = array(0, 20)) {
		switch ($action) {
			case 'getProjectList':
				$Projects = new Project();
				$ProjectList = $Projects->getProjects($limit);
				$ProjectCount = $Projects->getProjectCount();
				$StatusList = $Projects->getProjectStatusList();

				$this->template = '/admin/project/list';
				$this->output['projects'] = $ProjectList;
				$this->output['count'] = $ProjectCount['ProjectCount'];
				$this->output['statusList'] = $StatusList;
				break;
		}
	}
}

?>