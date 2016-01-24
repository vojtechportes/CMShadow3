<?php

Class TemplatePages Extends Minimal {
	public $id;
	public $name;
	public $user;
	public $createdAt;
	public $modifiedAt;

	private function getAttributes () {
		return "
		T_TemplatePages.`ID`,
		T_TemplatePages.`Name`,
		T_TemplatePages.`User`,
		T_TemplatePages.`CreatedAt`,
		T_TemplatePages.`ModifiedAt`";
	}

	public function getTemplatePages($limit = array(0, 20), $order = 'DESC') {
		global $DB;

		$Stm = $DB->prepare("SELECT
			{$this->getAttributes()}
			FROM T_TemplatePages
			ORDER BY T_TemplatePages.`ID` {$order}
			LIMIT {$limit[0]}, {$limit[1]}");	

		$Stm->execute();

		return $Stm->fetchAll(PDO::FETCH_ASSOC);
	}



	public function getTemplatePageCount() {
		global $DB;

		$Stm = $DB->prepare("SELECT
		COUNT(T_TemplatePages.`ID`) AS TemplatePagesCount
		FROM T_TemplatePages");
		$Stm->execute();

		return $Stm->fetch(PDO::FETCH_ASSOC);
	}

	public function getAllTemplatePages() {
		global $DB;

		$Stm = $DB->prepare("SELECT
			{$this->getAttributes()}
			FROM T_TemplatePages
			ORDER BY T_TemplatePages.`ID` DESC");	

		$Stm->execute();

		return $Stm->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getTemplatePageByID() {

	}

	public function getTemplatePageByTemplate() {

	}

	public function createTemplatePage () {
		global $DB;

		$Stm = $DB->prepare("INSERT INTO T_TemplatePages
			(`Name`, `User`) VALUES (:Name, :User)");
		$Stm->execute(array(
			':Name' => $this->name,
			':User' => $this->user
		));
	}

	public function cloneTemplatePage () {

	}

	public function deleteTemplatePage () {

	}

	public function deleteTemplatePageRefference () {
		
	}
}

?>