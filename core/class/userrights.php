<?php

Class UserRights Extends Minimal {

	public static function getModuleRights ($name) {
		global $DB;

		$Stm = $DB->prepare("SELECT `Group` FROM T_ModuleRights WHERE `Module` = :Module LIMIT 80");
		$Stm->execute(array(':Module' => $name));
		return $Stm->fetchAll(PDO::FETCH_ASSOC);
	}

	public static function getAllModuleRights () {
		global $DB;

		$Stm = $DB->prepare("SELECT `Module`, `Group` FROM T_ModuleRights");
		$Stm->execute();
		return $Stm->fetchAll(PDO::FETCH_ASSOC);			
	}

	public static function setModuleRights ($key, $value) {
		global $DB;
	}

	public static function deleteModuleRights ($key, $value) {

	}

	public static function getNodeRights ($name) {
		global $DB;

		$Stm = $DB->prepare("SELECT `Group` FROM T_NodeRights WHERE `Node` = :Node LIMIT 80");
		$Stm->execute(array(':Node' => $name));
		return $Stm->fetchAll(PDO::FETCH_ASSOC);
	}

	public static function getAllNodeRights () {
		global $DB;

		$Stm = $DB->prepare("SELECT `Node`, `Group` FROM T_NodeRights");
		$Stm->execute();
		return $Stm->fetchAll(PDO::FETCH_ASSOC);
	}

	public static function setNodeRights ($key, $value) {

	}

	public static function deleteNodeRights ($key, $value) {

	}

	public static function getAPIRights ($name) {

	}

	public static function getAllAPIRights () {
		global $DB;
		
		$Stm = $DB->prepare("SELECT `Command`, `Group` FROM T_APIRights");
		$Stm->execute();
		return $Stm->fetchAll(PDO::FETCH_ASSOC);	
	}

	public static function setAPIRights ($key, $value) {

	}

	public static function deleteAPIRights ($key, $value) {

	}

}

?>