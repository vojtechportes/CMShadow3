<?php

Class Project Extends Minimal {

	public $name;
	public $description;
	public $owners;
	public $editors;
	public $releasedate;
	private $status;

	private function getProjectAttributes () {
		return "
		T_Projects.`ID`,		
		T_Projects.`Name`,
		T_Projects.`Description`,
		T_Projects.`ReleaseDate`,
		T_Projects.`Status`,
		T_Projects.`CreatedAt`,
		T_Projects.`ModifiedAt`";		
	}

	public function getProjectStatusList () {
		return array(
			"1" => array("{_'projects_status_type_1'}", "badge-info"),
			"2" => array("{_'projects_status_type_2'}", "badge-empty"),
			"3" => array("{_'projects_status_type_3'}", "badge-primary"),
			"4" => array("{_'projects_status_type_4'}", "badge-warning"),
			"5" => array("{_'projects_status_type_5'}", "badge-success"),
			"6" => array("{_'projects_status_type_6'}", "badge-warning"),
			"7" => array("{_'projects_status_type_7'}", "badge-warning")							
		);
	}

	public function createProject () {
		global $DB;
		$Stm = $DB->prepare("INSERT INTO T_Projects (`Name`, `Description`, `ReleaseDate`) VALUES (:Name, :Description, :ReleaseDate)");
		$Stm->execute(array(
			':Name' => $this->name,
			':Description' => $this->description,
			':ReleaseDate' => $this->releasedate
		));
		$this->status = $DB->lastInsertId();
		return $Stm->rowCount();
	}

	public function updateProject ($owners = false) {

	}

	private function concatProjectOwners () {
		$result = array();
		foreach ($this->owners as $owner) {
			$result[] = array($owner, 1);
		}

		if (is_array($this->editors)) {
			foreach ($this->editors as $editor) {
				$result[] = array($editor, 2);
			}
		}

		return $result;
	}

	public function setProjectOwners () {
		global $DB;		
		$owners = $this->concatProjectOwners();
		$Stm = $DB->prepare("INSERT INTO T_ProjectOwners (`Project`, `User`, `Role`) VALUES (:Project, :User, :Role)");
		
		foreach ($owners as $owner) {
			$Stm->execute(array(
				':Project' => $this->status,
				':User' => $owner[0],
				':Role' => $owner[1]
			));
		}

		return $Stm->rowCount();
	}

	public function addProjectOwners () {

	}

	public function removeProjectOwners () {

	}

	public function getProjects ($limit = array(0, 20), $order = 'DESC') {
		global $DB, $M;
		$UserID = User::getUserID();

		$Stm = $DB->prepare("SELECT
			{$this->getProjectAttributes()},
			(SELECT COUNT(`ID`)
				FROM T_ProjectOwners
				WHERE T_ProjectOwners.`Project` = T_Projects.`ID`
				AND T_ProjectOwners.`User` = :UserID
				LIMIT 1)
			AS HasRights
			FROM T_Projects
			ORDER BY T_Projects.`ID` {$order}
			LIMIT {$limit[0]}, {$limit[1]}");
		$Stm->execute(array(
			":UserID" => $UserID
		));
		return $Stm->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getProjectCount ($filter = false) {
		global $DB;
		$Stm = $DB->prepare("SELECT
		COUNT(T_Projects.`ID`) AS ProjectCount
		FROM T_Projects");
		$Stm->execute();
		return $Stm->fetch(PDO::FETCH_ASSOC);
	}

	public function getProjectByID  ($id) {
		global $DB, $M;
		$UserID = User::getUserID();

		$Stm = $DB->prepare("SELECT
			{$this->getProjectAttributes()},
			(SELECT COUNT(`ID`)
				FROM T_ProjectOwners
				WHERE T_ProjectOwners.`Project` = T_Projects.`ID`
				AND T_ProjectOwners.`User` = :UserID
				LIMIT 1)
			AS HasRights
			FROM T_Projects
			WHERE T_Projects.`ID` = :ID
			LIMIT 1");
		$Stm->execute(array(
			":UserID" => $UserID,
			":ID" => $id
		));
		return $Stm->fetch(PDO::FETCH_ASSOC);
	}

	public function getProjectOwners ($id, $role = false) {
		global $DB;
		if ($role) {
			$Stm = $DB->prepare("SELECT
				T_ProjectOwners.`User`
				FROM T_ProjectOwners
				WHERE T_ProjectOwners.`Project` = :ID AND T_ProjectOwners.`Role` = :Role");
			$Stm->execute(array(
				":ID" => $id,
				":Role" => $role
			));
			return $Stm->fetchAll(PDO::FETCH_ASSOC);		
		} else {
			return false;
		}		
	}

	public function getProjectByUser ($id) {

	}

	public function getProjectByUsers ($users) {

	}

}

?>