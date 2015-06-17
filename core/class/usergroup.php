<?php

Class UserGroup Extends Minimal {

	public function getGroups () {
		global $DB;
		$Stm = $DB->prepare('SELECT `ID`, `Name` FROM T_UserGroups LIMIT 100');
		$Stm->execute();
		return $Stm->fetchAll(PDO::FETCH_ASSOC);
	}
}

?>