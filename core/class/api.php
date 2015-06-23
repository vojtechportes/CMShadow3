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
		$success 	= array('key' => $this->key, 'action' => $this->action, 'status' => 1);
		$fail		= array('key' => $this->key, 'action' => $this->action, 'status' => 0);
		if ($this->validate()) {
			$reflection = new ReflectionMethod(__CLASS__, $this->command);
			$reflection->setAccessible(true);
			$r = $reflection->invoke(new API, $this->action, $this->key, $this->value);
			if ($r !== false)
				return $success;
			return $fail;
		} else {
			return $fail;
		}
	}

	protected function settingsModuleRightsAssign ($action, $key, $value) {
		switch ($action) {
			case 'set':
				$UR = new UserRights();
				$UR->setModuleRights($key, $value);
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