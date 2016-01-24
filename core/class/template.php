<?php

Class Template Extends Minimal {

	public $id;
	public $layout;
	public $name;
	public $outputStyle; /* template output folder (eg default-html) */
	public $outputType; /* skeleton file (eg plain.tpl, default.tpl) */
	public $style; /* style output folder */
	public $user;
	public $createdAt;
	public $modifiedAt;
	public $page;
	public $pages;
	private $status;

	private function getAttributes () {
		return "
		T_Templates.`ID`,
		T_Templates.`Name`,
		T_Templates.`Layout`,
		T_Templates.`OutputStyle`,
		T_Templates.`OutputType`,
		T_Templates.`Style`,
		T_Templates.`User`,
		T_Templates.`CreatedAt`,
		T_Templates.`ModifiedAt`";
	}

	private function getPageAttributes ($rename = false) {
		if ($rename) {
			return "
			T_TemplatePages.`ID` AS `PageID`,
			T_TemplatePages.`Name` AS `PageName`,
			T_TemplatePages.`User` AS `PageUser`,
			T_TemplatePages.`CreatedAt` AS `PageCreatedAt`,
			T_TemplatePages.`ModifiedAt` AS `PageModifiedAt`";
		} else {
			return "
			T_TemplatePages.`ID`,
			T_TemplatePages.`Name`,
			T_TemplatePages.`User`,
			T_TemplatePages.`CreatedAt`,
			T_TemplatePages.`ModifiedAt`";			
		}
	}

	private function getRefferenceAttributes () {
		return "
		T_TemplatePageRefference.`ID`,
		T_TemplatePageRefference.`Page`,
		T_TemplatePageRefference.`Template`";
	}

	private function extractTemplate ($data) {
		global $M;

		$Template = array();

		foreach ($data as $key => $value) {
			if (!array_key_exists($value['ID'], $Template)) {
				$Template[$value['ID']] = array();
			}

			if (!array_key_exists('Template', $Template[$value['ID']])) {
				$Template[$value['ID']]['Template'] = array(
					'ID' => (int) $value['ID'],
					'Name' => $value['Name'],
					'Layout' => (int) $value['Layout'],
					'OutputStyle' => $value['OutputStyle'],
					'OutputType' => $value['OutputType'],
					'Style' => $value['Style'],
					'User' => (int) $value['User'],
					'CreatedAt' => $value['CreatedAt'],
					'ModifiedAt' => $value['ModifiedAt'],
					'NumPages' => (int) $value['numPages']
				);
			}

			if ($Template[$value['ID']]['Template']['NumPages'] > 0) {
				if (!array_key_exists('TemplatePages', $Template[$value['ID']])) {
					$Template[$value['ID']]['TemplatePages'] = array();
				}

				$Template[$value['ID']]['TemplatePages'][] = array(
					'ID' => $value['PageID'],
					'Name' => $value['PageName'],
					'User' => $value['PageUser'],
					'CreatedAt' => $value['PageCreatedAt'],
					'ModifiedAt' => $value['PageModifiedAt']
				);
			} else {
				if (!array_key_exists('TemplatePages', $Template[$value['ID']])) {
					$Template[$value['ID']]['TemplatePages'] = false;
				}				
			}
		}

		unset($data);

		return $Template;		
	}

	public function getCurrentTemplateID () {
		return $this->status;
	}	

	public function getTemplates ($limit = array(0, 20), $order = 'DESC') {
		global $DB, $M;

		$Stm = $DB->prepare("SELECT
			{$this->getAttributes()},
			{$this->getPageAttributes(true)},
			(SELECT COUNT(T_TPRB.`ID`)
				FROM T_TemplatePageRefference T_TPRB
				WHERE T_TPRB.`Template` = T_Templates.`ID`) AS numPages
			FROM T_Templates
			LEFT JOIN T_TemplatePageRefference ON T_TemplatePageRefference.`Template` = T_Templates.`ID`
			LEFT JOIN T_TemplatePages ON T_TemplatePages.`ID` = T_TemplatePageRefference.`Page`
			ORDER BY T_Templates.`ID` {$order}
			LIMIT {$limit[0]}, {$limit[1]}");	

		$Stm->execute();

		$Data = $Stm->fetchAll(PDO::FETCH_ASSOC);
		return $this->extractTemplate($Data);
	}

	public function getTemplateCount () {
		global $DB;

		$Stm = $DB->prepare("SELECT
		COUNT(T_Templates.`ID`) AS TemplateCount
		FROM T_Templates");
		$Stm->execute();

		return $Stm->fetch(PDO::FETCH_ASSOC);		
	}

	public function getTemplateByID ($id) {
		global $DB;

		$Stm = $DB->prepare("SELECT
			{$this->getAttributes()},
			{$this->getPageAttributes(true)},
			(SELECT COUNT(T_TPRB.`ID`)
				FROM T_TemplatePageRefference T_TPRB
				WHERE T_TPRB.`Template` = T_Templates.`ID`) AS numPages
			FROM T_Templates
			LEFT JOIN T_TemplatePageRefference ON T_TemplatePageRefference.`Template` = T_Templates.`ID`
			LEFT JOIN T_TemplatePages ON T_TemplatePages.`ID` = T_TemplatePageRefference.`Page`
			WHERE T_Templates.`ID` = :ID");

		$Stm->execute(array(
			':ID' => $id
		));	

		$Data = $Stm->fetchAll(PDO::FETCH_ASSOC);
		return $this->extractTemplate(array($Data));		
	}

	public function createTemplate () {
		global $DB;
		$Stm = $DB->prepare("INSERT INTO T_Templates
			(`Name`, `Layout`, `OutputStyle`, `OutputType`, `Style`, `User`)
			VALUES
			(:Name, :Layout, :OutputStyle, :OutputType, :Style, :User)");
		$Stm->execute(array(
			':Name' => $this->name,
			':Layout' => $this->layout,
			':OutputStyle' => $this->outputStyle,
			':OutputType' => $this->outputType,
			':Style' => $this->style,
			':User' => $this->user
		));

		$this->status = $DB->lastInsertId();
		return $Stm->rowCount();
	}

	public function updateTemplate () {
		global $DB;

		$Stm = $DB->prepare("UPDATE T_Templates SET
			`Name` = :Name,
			`Layout` = :Layout,
			`OutputStyle` = :OutputStyle,
			`OutputType` = :OutputType,
			`Style` = :Style
			WHERE T_Templates.`ID` = :ID");
		$Stm->execute(array(
			':Name' => $this->name,
			':Layout' => $this->layout,
			':OutputStyle' => $this->outputStyle,
			':OutputType' => $this->outputType,
			':Style' => $this->style,
			':ID' => $this->id
		));

		return $Stm->rowCount();
	}

	public function cloneTemplate () {

	}

	public function deleteTemplate () {
		global $DB;

		$Stm = $DB->prepare("DELETE FROM T_Templates WHERE T_Templates.`ID` = :ID");
		$Stm->execute(array(
			':ID' => $this->id
		));

		return $Stm->rowCount();
	}

	public function setTemplatePage ($newTemplate = false) {
		global $DB;

		$Stm = $DB->prepare("INSERT INTO T_TemplatePageRefference
			(`Page`, `Template`)
			VALUES
			(:Page, :Template)");

		if ($newTemplate) {
			$Stm->execute(array(
				':Page' => $this->page,
				':Template' => $this->status
			));
		} else {
			$Stm->execute(array(
				':Page' => $this->page,
				':Template' => $this->id
			));
		}

		return $Stm->rowCount();
	}

	public function setTemplatePages ($newTemplate = false) {
		global $DB;

		$Stm = $DB->prepare("INSERT INTO T_TemplatePageRefference
			(`Page`, `Template`)
			VALUES
			(:Page, :Template)");

		if ($newTemplate) {
			foreach ($this->pages as $page) {
				$Stm->execute(array(
					':Page' => $page,
					':Template' => $this->status
				));
			}
		} else {
			foreach ($this->pages as $page) {
				$Stm->execute(array(
					':Page' => $page,
					':Template' => $this->id
				));
			}
		}

		return $Stm->rowCount();		
	}

	public function unsetTemplatePage () {
		global $DB;

		$Stm = $DB->prepare("DELETE FROM T_TemplatePageRefference
			WHERE T_TemplatePageRefference.`ID` = :ID");
		$Stm->execute(array(
			':ID' => $this->id
		));

		return $Stm->rowCount();
	}

	public function unsetTemplatePages () {
		global $DB;

		$Stm = $DB->prepare("DELETE FROM T_TemplatePageRefference
			WHERE T_TemplatePageRefference.`ID` = :ID");

		foreach ($this->pages as $page) {
			$Stm->execute(array(
				':ID' => $page
			));
		}

		return $Stm->rowCount();		
	}

}

?>