<?php

Class UserList Extends User {

	private function getUserAttributes () {
		return "
			T_User.`ID`,
			T_User.`Name`";
	}

	private function getGroupsQuery ($groups = false) {
		$query = '';
		if (is_array($groups)) {
			foreach ($groups as $group) {
				$query[] = "T_UserRights.`Group` != {$group}";
			}
			$query = implode(" AND ", $query);
		}
		return $query;
	} 

	public function getUsers ($exclude) {
		global $DB, $M;

		$Stm = $DB->prepare("SELECT
		{$this->getUserAttributes()},
		(SELECT COUNT(T_UserRights.`Group`) FROM T_UserRights WHERE {$this->getGroupsQuery($exclude)} AND T_User.`ID` = T_UserRights.`User`) AS IsAllowed
		FROM T_User HAVING IsAllowed > 0");			
		$Stm->execute();
		return $Stm->fetchAll(PDO::FETCH_ASSOC);		
	}

	public function getUsersByGroup ($group) {

	}
}

?>