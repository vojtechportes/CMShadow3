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

	private function validate () {
		foreach (get_object_vars($this) as $variable) {
			if ($variable === false)
				return false;
		}

		if (method_exists(__CLASS__, $this->command))
			return true;
		return false;
	}

	public function proceed () {
		if ($this->validate()) {
			var_dump($this->command);
			$reflection = new ReflectionMethod(__CLASS__, $this->command);
			$reflection->setAccessible(true);
			return $reflection->invoke(new API, $this->action, $this->key, $this->value);
		} else {
			return false;
		}
	}

	protected function settingModuleRightsAssign ($action, $key, $value) {
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

	protected function settingNodeRightsAssign ($action, $key, $value) {
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