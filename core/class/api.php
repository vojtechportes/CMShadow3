<?php

Class API {

	public $command;
	public $action;
	public $key;
	public $value;

	public function __construct ($command, $action, $key, $value) {
		$this->command = $command;
		$this->action = $action;
		$this->key = $key;
		$this->value = $value;
	}

	public function proceed () {

	}
}

?>