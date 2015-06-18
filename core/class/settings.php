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
			case 'getModuleRights':
				$this->template = '/admin/settings/modulerights';
				$this->getModuleRights();
				break;
			case 'getAPIRights':
				$this->template = '/admin/settings/apirights';
				$this->getAPIRights();
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
		global $DB;
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


	protected function getModuleRights () {
		global $DB;

		$_rights = array();
		$_groups = array();

		$Stm = $DB->prepare("SELECT `Module`, `Group` FROM T_ModuleRights");
		$Stm->execute();
		$Res = $Stm->fetchAll(PDO::FETCH_ASSOC);		

		$Groups = new UserGroup();
		$Modules = new FileSystem(false, true);
		$Modules->path = './core/modules/';
		$Modules->pathStrip = true;
		$Modules->extensionStrip = true;

		$this->output["Groups"] = $Groups->getGroups();
		$this->output["Modules"] = $Modules->output();

		foreach ($this->output["Groups"] as $group) {
			$_groups[$group['ID']] = array();
		}

		foreach ($Res as $rights) {
			$_rights[$rights['Group']][] = $rights['Module'];
		}

		$this->output["Rights"] = $_rights + $_groups;
	}

	protected static function getAPICommands () {
		return array(
			"settingsNodeRigtsAssign",
			"settignsModuleRightsAssign",
			"settingsAPIRightsAssign"
		);
	}

	protected function getAPIRights () {
		global $DB;

		$_rights = array();
		$_groups = array();

		$Stm = $DB->prepare("SELECT `Module`, `Group` FROM T_APIRights");
		$Stm->execute();
		$Res = $Stm->fetchAll(PDO::FETCH_ASSOC);		

		$Groups = new UserGroup();

		$this->output["Groups"] = $Groups->getGroups();
		$this->output["Commands"] = self::getAPICommands();

		foreach ($this->output["Groups"] as $group) {
			$_groups[$group['ID']] = array();
		}

		foreach ($Res as $rights) {
			$_rights[$rights['Group']][] = $rights['Module'];
		}

		$this->output["Rights"] = $_rights + $_groups;
	}	
}

?>