<?php

Class API Extends Minimal {

	public $command;
	public $action;
	public $key;
	public $value;

	public function __construct ($command = false, $action = false, $key = false, $value = false) {
		$this->command = $command;
		$this->action = $action;
		$this->key = $key;
		$this->value = $value;
	}

	private function checkAPIRights ($command) {
		global $Rights;
		$APIRights = array();

		$Res = UserRights::getAPIRights($command);

		foreach ($Res as $CRight) {
			array_push($APIRights, $CRight["Group"]);
		}

		foreach ($Rights['Group'] as $Right) {
			if (in_array($Right, $APIRights)) {
				return true;
			}
		}

		return false;
	}

	private function validate () {
		if ($this->checkAPIRights($this->command)) {
			foreach (get_object_vars($this) as $variable) {
				if ($variable === false)
					return false;
			}

			if (method_exists(__CLASS__, $this->command))
				return true;
			return false;
		} else {
			return false;
		}
	}

	public function proceed () {
		$result = array('key' => $this->key, 'action' => $this->action);

		if ($this->validate()) {
			$reflection = new ReflectionMethod(__CLASS__, $this->command);
			$reflection->setAccessible(true);
			$r = $reflection->invoke(new API, $this->action, $this->key, $this->value);
			if ($r !== false)
				return $result + array('status' => 1);
			return $result + array('status' => 0);
		} else {
			return $result + array('status' => -1);
		}
	}

	protected function settingsModuleRightsAssign ($action, $key, $value) {
		switch ($action) {
			case 'set':
				$UR = new UserRights();
				return $UR->setModuleRights($key, $value);
				break;
			case 'delete':
				$UR = new UserRights();
				return $UR->deleteModuleRights($key, $value);
				break;
		}
	}	

	protected function settingsNodeRightsAssign ($action, $key, $value) {
		switch ($action) {
			case 'set':
				$UR = new UserRights();
				return $UR->setNodeRights($key, $value);
				break;
			case 'delete':
				$UR = new UserRights();
				return $UR->deleteNodeRights($key, $value);
				break;
		}
	}	

	protected function settingsAPIRightsAssign ($action, $key, $value) {
		switch ($action) {
			case 'set':
				$UR = new UserRights();
				return $UR->setAPIRights($key, $value);
				break;
			case 'delete':
				$UR = new UserRights();
				return $UR->deleteAPIRights($key, $value);
				break;
		}
	}
}

?>