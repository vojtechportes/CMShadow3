<?php

Class Page Extends Minimal {

	public $parent;
	public $owner;
	public $version;
	public $locked;
	public $visible;
	public $weight;
	public $name;
	public $title;
	public $description;
	public $keywords;
	public $template;
	public $pageTree;
	private $status;

	private function getPageAttributes () {
		return "
			T_Pages.`ID`,
			T_Pages.`Parent`,
			T_Pages.`Owner`,
			T_Pages.`ChangedBy`,
			T_Pages.`Version`,
			T_Pages.`Locked`,
			T_Pages.`Visible`,
			T_Pages.`CreatedAt`,
			T_Pages.`ModifiedAt`,
			T_PageDetails.`Title`";
	}

	private function getPageAttributesDetailed () {
		return "
			T_Pages.`ID`,
			T_Pages.`Parent`,
			T_Pages.`Owner`,
			T_Pages.`ChangedBy`,
			T_Pages.`Version`,
			T_Pages.`Locked`,
			T_Pages.`Visible`,
			T_Pages.`CreatedAt`,
			T_Pages.`ModifiedAt`,
			T_PageDetails.`Name`,
			T_PageDetails.`Title`,
			T_PageDetails.`Description`,
			T_PageDetails.`Keywords`,
			T_PageDetails.`Template`";
	}

	public function createPage () {
		global $DB;
		$Stm = $DB->prepare("INSERT INTO T_Pages (`Parent`, `Owner`, `Version`, `Locked`, `Visible`, `Weight`) VALUES (:Parent, :Owner, :Version, :Locked, :Visible, :Weight)");
		$Stm->execute(array(
			':Parent' => $this->parent,
			':Owner' => $this->owner,
			':Version' => 1,
			':Locked' => 0,
			':Visible' => $this->visible,
			':Weight' => $this->weight
		));
		$this->status = $DB->lastInsertId();
		return $Stm->rowCount();
	}

	public function createPageDetails () {
		global $DB;
		$Stm = $DB->prepare("INSERT INTO T_PageDetails (`Page`, `Name`, `Title`, `Description`, `Keywords`, `Template`) VALUES (:Page, :Name, :Title, :Description, :Keywords, :Template)");
		$Stm->execute(array(
			':Page' => $this->status,
			':Name' => $this->name,
			':Title' => $this->title,
			':Description' => $this->description,
			':Keywords' => $this->keywords,
			':Template' => $this->template
		));
		return $Stm->rowCount();	
	}

	public function getPageByID ($id) {
		global $DB;
		$Stm = $DB->prepare("SELECT
		{$this->getPageAttributesDetailed()}
		FROM T_Pages
		LEFT JOIN T_PageDetails
		ON T_Pages.`ID` = T_PageDetails.`Page` WHERE T_Pages.`ID` = :ID LIMIT 1");			
		$Stm->execute(array(
			':ID' => $id
		));
		return $Stm->fetch(PDO::FETCH_ASSOC);			
	}

	public function getPageList ($parent = 0) {
		global $DB, $M;
		$Stm = $DB->prepare("SELECT
		{$this->getPageAttributes()}
		FROM T_Pages
		LEFT JOIN T_PageDetails
		ON (T_Pages.`ID` = T_PageDetails.`Page`) WHERE T_Pages.`Parent` = :Parent");
		//$M->debug($Stm);
		$Stm->execute(array(
			':Parent' => $parent
		));
		return $Stm->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getPageListDetailed ($parent = 0) {
		global $DB;
		$Stm = $DB->prepare("SELECT
		{$this->getPageAttributesDetailed()}
		FROM T_Pages
		LEFT JOIN T_PageDetails
		ON T_Pages.`ID` = T_PageDetails.`Page` WHERE T_Pages.`Parent` = :Parent");
		$Stm->execute(array(
			':Parent' => $parent
		));
		return $Stm->fetchAll(PDO::FETCH_ASSOC);		
	}

	private function hasChildPages ($parent = 0) {
		global $DB;
		$Stm = $DB->prepare("SELECT `ID` FROM T_Pages WHERE `Parent` = :Parent");
		$Stm->execute(array(
			':Parent' => $parent
		));
		if ($Stm->rowCount() > 0)
			return true;
		return false;
	}

	public function getPageTree ($detailed = false, $parent = 0, $depth = 0) {
		global $M;

		if ($detailed) {
			$pages = self::getPageListDetailed($parent);
		} else {
			$pages = self::getPageList($parent);
		}

		foreach ($pages as $page) {
			$isFolder = self::hasChildPages($page['ID']);
			$page['Depth'] = $depth;
			$page['hasChildPages'] = false;
			if ($isFolder) $page['hasChildPages'] = true;

			$this->pageTree[] = $page;		
			if (self::hasChildPages($page['ID'])) {

				self::getPageTree($detailed, $page['ID'], $depth + 1);
			}
		}
		$depth = $depth -1;
	}
} 

?>