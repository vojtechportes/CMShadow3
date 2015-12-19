<?php

Class Project Extends Minimal {

	public $id;
	public $name;
	public $description;
	public $owners;
	public $editors;
	public $releasedate;
	public $state;
	public $status;

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
			"7" => array("{_'projects_status_type_7'}", "badge-warning"),
			"8" => array("{_'projects_status_type_8'}", "badge-info")					
		);
	}

	public function getProjectWorkflow () {
		return array(
			"1" => array(2 => "{_'projects_status_type_2_action'}",3 => "{_'projects_status_type_3_action'}",4 => "{_'projects_status_type_4_action'}"),
			"2" => array(1 => "{_'projects_status_type_1_action'}"),
			"3" => array(5 => "{_'projects_status_type_5_action'}", 1 => "{_'projects_status_type_1_action'}"),
			"4" => array(6 => "{_'projects_status_type_6_action'}", 1 => "{_'projects_status_type_1_action'}"),
			"5" => array(7 => "{_'projects_status_type_7_action'}", 8 => "{_'projects_status_type_8_action'}"),
			"6" => array(),
			"7" => array(),
			"8" => array()
		);
	}

	protected function getProjectWorkflowRights () {
		return array(
			"1" => array(1,2),
			"2" => array(1,2),
			"3" => array(1,2),
			"4" => array(1,2),
			"5" => array(1),
			"6" => array(1),
			"7" => array(1),
			"8" => array(1)
		);
	}

	public function checkProjectWorkflowRights ($workflow, $projectRights) {
		global $M;

		$Rights = $this->getProjectWorkflowRights();
	
		if (!empty($workflow) && !empty($projectRights)) {
			foreach ($workflow as $key => $item) {
				$Roles = $Rights[$key];
				$_roles = array();
				
				foreach ($projectRights as $right) {
					if (in_array($right, $Roles)) {
						$_roles[] = true;
					}
				}

				if (count($_roles) < 1)
					unset($workflow[$key]);
			}
		}	

		return $workflow;
	}

	public function getCurrentProjectID () {
		return $this->status;
	}

	public function createProject () {
		global $DB;
		$Stm = $DB->prepare("INSERT INTO T_Projects
			(`Name`, `Description`, `ReleaseDate`)
			VALUES (:Name, :Description, :ReleaseDate)");
		$Stm->execute(array(
			':Name' => $this->name,
			':Description' => $this->description,
			':ReleaseDate' => $this->releasedate
		));
		$this->status = $DB->lastInsertId();
		return $Stm->rowCount();
	}

	public function updateProject ($owners = false) {
		global $DB;

		$Stm = $DB->prepare("UPDATE T_Projects SET
			`Name` = :Name,
			`Description` = :Description,
			`ReleaseDate` = :ReleaseDate
			WHERE `ID` = :ID");
		$Stm->execute(array(
			':Name' => $this->name,
			':Description' => $this->description,
			':ReleaseDate' => $this->releasedate,
			':ID' => $this->id
		));

		$this->status = $this->id;
		return $Stm->rowCount();
	}

	public function lockProject () {
		global $DB;

		$Stm = $DB->prepare("UPDATE T_Projects SET
			`Locked` = :Locked
			WHERE `ID` = :ID");
		$Stm->execute(array(
			':Locked' => 1,
			':ID' => $this->id
		));

		$this->status = $this->id;
		return $Stm->rowCount();
	}

	public function unlockProject () {
		global $DB;

		$Stm = $DB->prepare("UPDATE T_Projects SET
			`Locked` = :Locked
			WHERE `ID` = :ID");
		$Stm->execute(array(
			':Locked' => 0,
			':ID' => $this->id
		));

		$this->status = $this->id;
		return $Stm->rowCount();
	}

	public function changeProjectState () {
		global $DB;

		$Stm = $DB->prepare("UPDATE T_Projects SET
			`Status` = :Status
			WHERE `ID` = :ID");
		$Stm->execute(array(
			':Status' => $this->state,
			':ID' => $this->id
		));

		$this->status = $this->id;
		return $Stm->rowCount();
	}

	private function concatProjectOwners () {
		$result = array();
		foreach ($this->owners as $k => $owner) {
			$result[] = array($owner, 1);
		}

		if (is_array($this->editors)) {
			foreach ($this->editors as $k => $editor) {
				$result[] = array($editor, 2);
			}
		}

		return $result;
	}

	public function setProjectOwners ($remove = false) {
		global $DB;		

		if ($remove)
			$this->removeProjectOwners();

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

	private function removeProjectOwners () {
		global $DB;

		$Stm = $DB->prepare("DELETE FROM T_ProjectOwners WHERE `Project` = :Project");
		$Stm->execute(array(
			':Project' => $this->status
		));

		return $Stm->rowCount();
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
			AS HasRights,
			(SELECT COUNT(`ID`)
				FROM T_ProjectOwners
				WHERE T_ProjectOwners.`Project` = T_Projects.`ID`
				AND T_ProjectOwners.`User` = :UserID
				AND T_ProjectOwners.`Role` = :RoleAdmin
				LIMIT 1)
			AS HasAdminRights,
			(SELECT COUNT(`ID`)
				FROM T_ProjectOwners
				WHERE T_ProjectOwners.`Project` = T_Projects.`ID`
				AND T_ProjectOwners.`User` = :UserID
				AND T_ProjectOwners.`Role` = :RoleEditor
				LIMIT 1)
			AS HasEditorRights						
			FROM T_Projects
			WHERE T_Projects.`ID` = :ID
			LIMIT 1");
		$Stm->execute(array(
			":UserID" => $UserID,
			":ID" => $id,
			":RoleAdmin" => 1,
			":RoleEditor" => 2
		));
		return $Stm->fetch(PDO::FETCH_ASSOC);
	}

	public function getProjectOwners ($id, $role = false) {
		global $DB;
		if ($role) {
			$Stm = $DB->prepare("SELECT
				T_ProjectOwners.`User`,
				T_User.`Name`
				FROM T_ProjectOwners
				LEFT JOIN T_User ON T_User.`ID` = T_ProjectOwners.`User`
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