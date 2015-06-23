<?php

Class User Extends Minimal {
	public $userName;
	public $password;
	public $attempts = DEFAULT_LOGIN_ATTEMPS;
	public $userNamePattern = "/^(?=.*[A-Z]{1,})(?=.*[a-z]{1,})[a-zA-Z0-9.@]{8,20}$/";
	public $passwordPattern = "^/(?=.*[A-Z]{1,})(?=.*[0-9]{1,})(?=.*[a-z]{1,})[a-zA-Z0-9_\-+!?*]{8,30}$/";	
	protected $defaultRole = 4;

	public function setUserSession () {
		global $DB;
		$User = $this->getUserByNamePassword();
		var_dump($User);
		if ($User["name"] !== false && $User["password"] !== false) {
			$_SESSION["UserID"] = $User["id"];
			$_SESSION["UserName"] = $User["name"];
			$_SESSION["UserLoginSession"] = true;
			$_SESSION["LifeTime"] = (time() + 1800);
			$_SESSION["SSID"] = session_id();

			$Stm = $DB->prepare("UPDATE T_User SET `SSID` = :SSID WHERE `ID` = :ID");
			$Stm->execute(array(':SSID' => session_id(), ':ID' => $User["id"]));
			$Res = $Stm->rowCount();		

			if ($Res == 1) {
				return true;
			} else {
				return $User;
			}
		} else {
			return $User;
		}
	}

	private function getUserSession () {
		if ($this->getUserSessionStatus()) {
			return array("UserID" => $_SESSION["UserID"], "UserName" => $_SESSION["UserName"], "UserLoginSession" => $_SESSION["UserLoginSession"], "LifeTime" => $_SESSION["LifeTime"], "SSID" => $_SESSION["SSID"]);
		}
	}

	public function getUserRights () {
		global $DB;
		if ($this->getUserSessionStatus()) {
			$Stm = $DB->prepare("SELECT T_UserRights.`Group` FROM T_UserRights LEFT JOIN T_User ON T_UserRights.`User` = T_User.`ID` AND T_User.`ID` = :ID LIMIT 80");
			$Stm->execute(array(':ID' => $_SESSION["UserID"]));
			return $Res = $Stm->fetch(PDO::FETCH_ASSOC);			
		} else {
			return array("Group" => DEFAULT_USER_GROUP);
		}		
	}

	public function getUserSessionStatus () {
		global $DB;
		if (isset($_SESSION["UserID"])) {
			$ID = $_SESSION["UserID"];
		} else {
			return false;
		}

		$Stm = $DB->prepare("SELECT `SSID` FROM T_User WHERE `ID` = :ID LIMIT 1");
		$Stm->execute(array(':ID' => $ID));
		$Res = $Stm->fetch(PDO::FETCH_ASSOC);

		if (isset($_SESSION["UserLoginSession"]) && isset($_SESSION["UserID"]) && isset($_SESSION["UserName"]) && isset($_SESSION["LifeTime"]) && isset($_SESSION["SSID"]) && $Res['SSID'] == $_SESSION["SSID"])
			if ($_SESSION["UserLoginSession"] === true)
				return true;
			return false;
		return false;
	}

	public function deleteUserSession () {
		global $DB;
		$Stm = $DB->prepare("UPDATE T_User SET `SSID` = '' WHERE `ID` = :ID");
		$Stm->execute(array(':ID' => $_SESSION["UserID"]));
		session_destroy();
		redirect("/admin/");
	}

	public function updateUserSession () {
		if ($this->getUserSessionStatus()) {
			session_destroy();
			$S = getUserSession();
			$_SESSION["UserID"] = $S["id"];
			$_SESSION["UserName"] = $S["name"];
			$_SESSION["UserLoginSession"] = true;
			$_SESSION["LifeTime"] = (time() + 1800);	
			$_SESSION["SSID"] = session_id($S["SSID"]);	
		}
	}

	public function getUserByName () {
		global $DB;
		$Stm = $DB->prepare("SELECT `ID`, `SSID` FROM T_Users WHERE `Name` = :Name LIMIT 1");
		$Stm->execute(array(':Name' => $this->userName));
		return $Res = $Stm->fetch(PDO::FETCH_ASSOC);
	}

	public function setUserAttempts ($attempts) {
		global $DB;
		$Stm = $DB->prepare("UPDATE T_UserAttempts LEFT JOIN T_User ON T_User.`ID` = T_UserAttempts.`ID` AND T_User.`Name` = :Name SET T_UserAttempts.`Attempts` = :Attempts");
		$Stm->execute(array(':Name' => $this->userName, ':Attempts' => $attempts));
		return $Res = $Stm->rowCount();
	}

	public function getUserAttempts () {
		global $DB;
		$Stm = $DB->prepare("SELECT `Attempts` FROM T_UserAttempts LEFT JOIN T_User ON T_User.`ID` = T_UserAttempts.`ID` AND T_User.`Name` = :Name LIMIT 1");
		$Stm->execute(array(':Name' => $this->userName));
		$Res = $Stm->fetch(PDO::FETCH_ASSOC);
		if ($Res) {
			return $Res["Attempts"];
		}
		return false;
	}

	private function getUserByNamePassword () {
		global $DB;
		$Stm = $DB->prepare("SELECT T_User.`ID`, T_User.`SSID`, T_User.`Name`, T_User.`Password`, T_UserAttempts.`Attempts` FROM T_User LEFT JOIN T_UserAttempts ON T_User.`ID` = T_UserAttempts.`ID` AND T_User.`Name` = :Name LIMIT 1");
		$Stm->execute(array(':Name' => $this->userName));
		$Res = $Stm->fetch(PDO::FETCH_ASSOC);
		if ($Res) {
			if (intval($Res["Attempts"]) > 0) {
				if (password_verify($this->password, $Res["Password"])) {
					return array("name" => $Res["Name"], "password" => true, "id" => $Res["ID"]);
				} else {
					return array("name" => $Res["Name"], "password" => false, "attempts" => $Res["Attempts"]);
				}
			} else {
				return array("name" => false, "password" => false, "attempts" => $Res["Attempts"]);
			}
		} else {
			return array("name" => false, "password" => false);
		}
	}
}

?>