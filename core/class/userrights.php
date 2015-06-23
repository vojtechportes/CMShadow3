<?php

Class UserRights Extends Minimal {

	private static function validate (...$arguments) {
		foreach ($arguments as $argument) {
			if (!isset($arguments))
				return false;
		}

		return true;
	}

	public static function getModuleRights ($key, $value = false, $count = false) {
		global $DB;

		if ($value !== false) {
			$Stm = $DB->prepare("SELECT `Group` FROM T_ModuleRights WHERE `Module` = :Module AND `Group` = :Group LIMIT 1");
			$Stm->execute(array(':Module' => $key, ':Group' => $value));
		} else {
			$Stm = $DB->prepare("SELECT `Group` FROM T_ModuleRights WHERE `Module` = :Module LIMIT 80");	
			$Stm->execute(array(':Module' => $key));
		}

		if ($count)
			return $Stm->fetchColumn();
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

		if (self::getModuleRights($key, $value, true) === false && self::validate($key, $value)) {
			$Stm = $DB->prepare("INSERT INTO T_ModuleRights (`Module`, `Group`) VALUES (:Module, :Group)");
			$Stm->execute(array(':Module' => $key, ':Group' => $value));
			return $Stm->rowCount();
		}

		return false;
	}

	public static function deleteModuleRights ($key, $value) {
		global $DB;

		if (self::getModuleRights($key, $value, true) === $value && self::validate($key, $value)) {
			$Stm = $DB->prepare("DELETE FROM T_ModuleRights WHERE `Module` = :Module AND `Group` = :Group");
			$Stm->execute(array(':Module' => $key, ':Group' => $value));
			return $Stm->rowCount();
		}
		
		return false;
	}

	public static function getNodeRights ($key, $value = false, $count = false) {
		global $DB;

		if ($value !== false) {
			$Stm = $DB->prepare("SELECT `Group` FROM T_NodeRights WHERE `Node` = :Node AND `Group` = :Group LIMIT 1");
			$Stm->execute(array(':Node' => $key, ':Group' => $value));
		} else {
			$Stm = $DB->prepare("SELECT `Group` FROM T_NodeRights WHERE `Node` = :Node LIMIT 80");
			$Stm->execute(array(':Node' => $key));			
		}

		if ($count)
			return $Stm->fetchColumn();
		return $Stm->fetchAll(PDO::FETCH_ASSOC);
	}

	public static function getAllNodeRights () {
		global $DB;

		$Stm = $DB->prepare("SELECT `Node`, `Group` FROM T_NodeRights");
		$Stm->execute();
		return $Stm->fetchAll(PDO::FETCH_ASSOC);
	}

	public static function setNodeRights ($key, $value) {
		global $DB;

		if (self::getNodeRights($key, $value, true) === false && self::validate($key, $value)) {
			$Stm = $DB->prepare("INSERT INTO T_NodeRights (`Node`, `Group`) VALUES (:Node, :Group)");
			$Stm->execute(array(':Node' => $key, ':Group' => $value));
			return $Stm->rowCount();
		}

		return false;
	}

	public static function deleteNodeRights ($key, $value) {
		global $DB;

		if (self::getNodeRights($key, $value, true) === $value && self::validate($key, $value)) {
			$Stm = $DB->prepare("DELETE FROM T_NodeRights WHERE `Node` = :Node AND `Group` = :Group");
			$Stm->execute(array(':Node' => $key, ':Group' => $value));
			return $Stm->rowCount();
		}
		
		return false;
	}

	public static function getAPIRights ($key, $value = false, $count = false) {
		global $DB;

		if ($value !== false) {
			$Stm = $DB->prepare("SELECT `Group` FROM T_APIRights WHERE `Command` = :Command AND `Group` = :Group LIMIT 1");
			$Stm->execute(array(':Command' => $key, ':Group' => $value));
		} else {
			$Stm = $DB->prepare("SELECT `Group` FROM T_APIRights WHERE `Command` = :Command LIMIT 80");
			$Stm->execute(array(':Command' => $key));	
		}

		if ($count)
			return $Stm->fetchColumn();
		return $Stm->fetchAll(PDO::FETCH_ASSOC);
	}

	public static function getAllAPIRights () {
		global $DB;
		
		$Stm = $DB->prepare("SELECT `Command`, `Group` FROM T_APIRights");
		$Stm->execute();
		return $Stm->fetchAll(PDO::FETCH_ASSOC);	
	}

	public static function setAPIRights ($key, $value) {
		global $DB;

		if (self::getAPIRights($key, $value, true) === false && self::validate($key, $value)) {
			$Stm = $DB->prepare("INSERT INTO T_APIRights (`Command`, `Group`) VALUES (:Command, :Group)");
			$Stm->execute(array(':Command' => $key, ':Group' => $value));
			return $Stm->rowCount();
		}

		return false;
	}

	public static function deleteAPIRights ($key, $value) {
		global $DB;

		if (self::getAPIRights($key, $value, true) === $value && self::validate($key, $value)) {
			$Stm = $DB->prepare("DELETE FROM T_APIRights WHERE `Command` = :Command AND `Group` = :Group");
			$Stm->execute(array(':Command' => $key, ':Group' => $value));
			return $Stm->rowCount();
		}
		
		return false;
	}

}

?>