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

	public function getTemplatePages() {

	}

	public function getTemplatePageCount() {

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