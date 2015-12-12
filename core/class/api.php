<?php

Class API Extends Minimal {

	public $command;
	public $action;
	public $key;
	public $value;
	public $type;
	public $arguments = array();
	public $module;
	protected $output = false;

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

			/*foreach (get_object_vars($this) as $variable) {
				if ($variable === false)
					return false;
			}*/

			if (method_exists(__CLASS__, $this->command))
				return true;
			return false;
		} else {
			return false;
		}
	}

	public function proceed () {
		$result = array('key' => $this->key, 'action' => $this->action, 'arguments' => $this->arguments);
		if ($this->validate()) {
			$r = $this->{$this->command}();

			if ($r !== false)
				return $result + array('status' => 1) + array("output" => $this->output);
			return $result + array('status' => 0);
		} else {
			return $result + array('status' => -1);
		}
	}

	protected function settingsModuleRightsAssign () {
		switch ($this->action) {
			case 'set':
				$UR = new UserRights();
				return $UR->setModuleRights($this->key, $this->value);
				break;
			case 'delete':
				$UR = new UserRights();
				return $UR->deleteModuleRights($this->key, $this->value);
				break;
		}
	}	

	protected function settingsNodeRightsAssign () {
		switch ($this->action) {
			case 'set':
				$UR = new UserRights();
				return $UR->setNodeRights($this->key, $this->value);
				break;
			case 'delete':
				$UR = new UserRights();
				return $UR->deleteNodeRights($this->key, $this->value);
				break;
		}
	}	

	protected function settingsAPIRightsAssign () {
		switch ($this->action) {
			case 'set':
				$UR = new UserRights();
				return $UR->setAPIRights($this->key, $this->value);
				break;
			case 'delete':
				$UR = new UserRights();
				return $UR->deleteAPIRights($this->key, $this->value);
				break;
		}
	}

	protected function gadgets () {
		$class = "Gadgets";

		switch ($this->action) {
			case 'get':
				switch ($this->type) {
					case 'available':
						$type = 'getAvailableGadgets';
						break;
					default:
						$type = 'getGadgets';
						break;
				}

				$method = new ReflectionMethod($class, $type);
				$method->setAccessible(true);
				$this->output = $method->invoke(new $class, $this->arguments);
				break;
		}
	}

	protected function loadModule () {

		if (Module::checkModuleRights($this->module)) {
			ob_start();

			/* in parent::load static function, $this->arguments was originally compacted for some reason (compact($this->arguments)) */

			if (!$this->arguments)
				$this->arguments = array();

			parent::load(DEFAULT_MODULE_PATH.$this->module.'.php', $this->arguments + array("OutputStyle" => "default-html", "OutputType" => "HTML", "StripSlashes" => true, "Dependencies" => array()), false);
			$output = ob_get_contents(); ob_end_clean();
			$this->output = array("__html" => $output, "__stripslashes" => false);
		}
	}
}

?>