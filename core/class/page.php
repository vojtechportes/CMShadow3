<?php

Class Page Extends Minimal {

	public $parent;
	public $owner;
	public $version = 0;
	public $locked;
	public $visible;
	public $weight;
	public $name;
	public $title;
	public $description;
	public $keywords;
	public $template;
	public $pageTree;
	public $pageRefference = 0;
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
			T_Pages.`PageRefference`,
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
			T_Pages.`PageRefference`,
			T_PageDetails.`Name`,
			T_PageDetails.`Title`,
			T_PageDetails.`Description`,
			T_PageDetails.`Keywords`,
			T_PageDetails.`Template`";
	}

	public function createPage () {
		global $DB;
		$Stm = $DB->prepare("INSERT INTO T_Pages (`Parent`, `Owner`, `Version`, `Locked`, `Visible`, `Weight`, `PageRefference`) VALUES (:Parent, :Owner, :Version, :Locked, :Visible, :Weight, :PageRefference)");
		$Stm->execute(array(
			':Parent' => $this->parent,
			':Owner' => $this->owner,
			':Version' => $this->version,
			':Locked' => 0,
			':Visible' => $this->visible,
			':Weight' => $this->weight,
			':PageRefference' => $this->pageRefference
		));
		$this->status = $DB->lastInsertId();
		return $Stm->rowCount();
	}

	public function createPageDetails () {
		global $DB;
		$Stm = $DB->prepare("INSERT INTO T_PageDetails (`Page`, `Name`, `Title`, `Description`, `Keywords`, `Template`, `PageRefference`, `Version`) VALUES (:Page, :Name, :Title, :Description, :Keywords, :Template, :PageRefference, :Version)");
		$Stm->execute(array(
			':Page' => $this->status,
			':Name' => $this->name,
			':Title' => $this->title,
			':Description' => $this->description,
			':Keywords' => $this->keywords,
			':Template' => $this->template,
			':PageRefference' => $this->pageRefference,
			':Version' => $this->version
		));
		return $Stm->rowCount();	
	}


	public function clonePage ($id = false, $newID = false) {
		/* By setting newID to -1, initial backup will be created from 0 version of page,  */
		if (isset($id)) {
			if ($newID === -1) {
				$id = $this->status;
			}

			$Page = $this->getPageByID($id);
			$Clone = new Page();

			/* Page and Page Details*/
			$Clone->version = $newID;
			$Clone->pageRefference = $id;

			/* Page */
			$Clone->parent = $Page['Parent'];
			$Clone->owner = User::getUserID();
			$Clone->visible = $Page['Visible'];
			$Clone->weight = $Page['Weight'];

			
			/* Page Details */
			$Clone->name = $Page['Name'];
			$Clone->title = $Page['Title'];
			$Clone->description = $Page['Description'];
			$Clone->keywords = $Page['Keywords'];
			$Clone->template = $Page['Template'];

			$Clone->createPage();

			/* Update status if neccessary */
			if ($newID > 0) {	
				$this->status = $id;
			}

			$Clone->createPageDetails();

			return true;
		}
	}

	public function isLocked($id) {
		global $DB;
		$Stm = $DB->prepare("SELECT T_Pages.`ID` FROM T_Pages WHERE T_Pages.`ID` = :ID AND T_Pages.`Version` = :Version AND T_Pages.`Locked` = :Locked LIMIT 1");
		$Stm->execute(array(
			':ID' => $id,
			':Version' => $this->version,
			':Locked' => 1
		));
		if ($Stm->rowCount() === 1) {
			return true;
		} else {
			return false;
		}
	}

	public function lockPage ($id) {
		global $DB;
		$Stm = $DB->prepare("UPDATE T_Pages SET
			`Locked` = :Locked
			WHERE T_Pages.`ID` = :ID AND T_Pages.`Version` = :Version");
		$Stm->execute(array(
			':Locked' => 1,
			':ID' => $id,
			':Version' => $this->version
		));
		return $Stm->rowCount();
	}

	public function unlockPage ($id) {
		global $DB;
		$Stm = $DB->prepare("UPDATE T_Pages SET
			`Locked` = :Locked
			WHERE T_Pages.`ID` = :ID AND T_Pages.`Version` = :Version");
		$Stm->execute(array(
			':Locked' => 0,
			':ID' => $id,
			':Version' => $this->version
		));
		return $Stm->rowCount();
	}

	public function getPageByID ($id) {
		global $DB;
		$Stm = $DB->prepare("SELECT
		{$this->getPageAttributesDetailed()}
		FROM T_Pages
		LEFT JOIN T_PageDetails
		ON T_Pages.`ID` = T_PageDetails.`Page` WHERE T_Pages.`ID` = :ID AND T_Pages.`Version` = :Version LIMIT 1");			
		$Stm->execute(array(
			':ID' => $id,
			':Version' => $this->version
		));
		return $Stm->fetch(PDO::FETCH_ASSOC);			
	}

	public function getPageByParent ($id) {
		global $DB;
		$Stm = $DB->prepare("SELECT
		{$this->getPageAttributesDetailed()}
		FROM T_Pages
		LEFT JOIN T_PageDetails
		ON T_Pages.`ID` = T_PageDetails.`Page` AND T_Pages.`Version` = T_PagesDetails.`Version` WHERE T_Pages.`Parent` = :Parent AND T_Pages.`Version` = :Version LIMIT 1");			
		$Stm->execute(array(
			':Parent' => $id,
			':Version' => $this->version
		));
		return $Stm->fetch(PDO::FETCH_ASSOC);			
	}


	public function getPageList ($parent = 0) {
		global $DB, $M;
		$Stm = $DB->prepare("SELECT
		{$this->getPageAttributes()},
		(SELECT COUNT(T_PagesB.`ID`) FROM T_Pages T_PagesB WHERE T_Pages.`ID` = T_PagesB.`Parent` AND T_Pages.`Version` = T_PagesB.`Version`) AS numChildPages		
		FROM T_Pages
		LEFT JOIN T_PageDetails
		ON (T_Pages.`ID` = T_PageDetails.`Page` AND T_Pages.`Version` = T_PageDetails.`Version`) WHERE T_Pages.`Parent` = :Parent AND T_Pages.`Version` = :Version");
		$Stm->execute(array(
			':Parent' => $parent,
			':Version' => $this->version
		));
		return $Stm->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getPageListDetailed ($parent = 0) {
		global $DB;
		$Stm = $DB->prepare("SELECT
		{$this->getPageAttributesDetailed()},
		(SELECT COUNT(T_PagesB.`ID`) FROM T_Pages T_PagesB WHERE T_Pages.`ID` = T_PagesB.`Parent` AND T_Pages.`Version` = T_PagesB.`Version`) AS numChildPages
		FROM T_Pages
		LEFT JOIN T_PageDetails
		ON T_Pages.`ID` = T_PageDetails.`Page` WHERE T_Pages.`Parent` = :Parent AND T_Pages.`Version` = :Version");
		$Stm->execute(array(
			':Parent' => $parent,
			':Version' => $this->version
		));
		return $Stm->fetchAll(PDO::FETCH_ASSOC);		
	}

	public function getPageTree ($detailed = false, $parent = 0, $depth = 0) {
		global $M;

		if ($detailed) {
			$pages = self::getPageListDetailed($parent);
		} else {
			$pages = self::getPageList($parent);
		}

		foreach ($pages as $page) {
			$page['Depth'] = $depth;
			$this->pageTree[] = $page;		
			if ($page['numChildPages'] > 0) {
				self::getPageTree($detailed, $page['ID'], $depth + 1);
			}
		}
		$depth = $depth -1;
	}
} 

?>