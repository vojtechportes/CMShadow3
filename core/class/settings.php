<?php

Class Settings Extends Module {

	public $templateOutput = DEFAULT_OUTPUT;
	public $template = '';	
	protected $module;
	protected $output = array();

	public function __construct ($action) {
		switch ($action) {
			case 'getCategories':
				$this->template = '/admin/settings/categories';
				$this->getCategories();
				break;
			case 'getNodeRights':
				$this->template = '/admin/settings/noderights';
				$this->getNodeRights();
				break;
		}
	}

	protected function getCategories () {
		global $DB;
		$Stm = $DB->prepare("SELECT `Title`, `Path`, `Icon` FROM T_ConfigCategories LIMIT 100");
		$Stm->execute();
		$this->output['Categories'] = $Stm->fetchAll(PDO::FETCH_ASSOC);
	}

	protected function getNodeRights () {
		global $DB; global $M;
		$_rights = array();
		$_groups = array();


		$Stm = $DB->prepare("SELECT `Node`, `Group` FROM T_NodeRights");
		$Stm->execute();
		$Res = $Stm->fetchAll(PDO::FETCH_ASSOC);

		$Nodes = new Node();	
		$Groups = new UserGroup();
		$this->output["Nodes"] = $Nodes->getAllNodes(false);
		$this->output["Groups"] = $Groups->getGroups();

		foreach ($this->output["Groups"] as $group) {
			$_groups[$group['ID']] = array();
		}

		foreach ($Res as $rights) {
			$_rights[$rights['Group']][] = $rights['Node'];
		}

		$this->output["Rights"] = $_rights + $_groups;
	}
}

?>