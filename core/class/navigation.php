<?php

Class Navigation Extends Module {
	public $name;
	protected $items;
	public $templateOutput = DEFAULT_OUTPUT;
	public $template = '/admin/navigation/show';	

	public function __construct ($name) {
		if (!empty($name)) {
			$this->name = $name;	
			$this->items = $this->getItemsByName();
			$this->setOrder();
			$this->extendOutput("NavigationItems", $this->items);
		}
	}

	protected function getItemsByName () {
		if (!empty($this->name)) {
			$Nodes = new Node ();
			return $Nodes->getAllNodes('["Config"]["NavigationShow"]["'.$this->name.'"]["Visible"]');
		} else {
			return false;
		}
	}

	protected function setOrder () {
		$items = array();
		$config = array();
		$title = '';
		$weightDefault = 50;

		foreach ($this->items as $key => $item) {
			$navigationConfig = $item["Config"]["NavigationShow"]["$this->name"];
			if (!array_key_exists("Weight", $navigationConfig)) {
				$weight = $weightDefault;
			} else {
				$weight = $navigationConfig["Weight"];
			}

			if (!array_key_exists("Title", $navigationConfig)) {
				$title = $item["Config"]["Title"];
			} else {
				$title = $navigationConfig["Title"];
			}

			$items[$weight.$key] = array(
				"Path" => $item["Path"],
				"Title" => $title
			);
		}

		ksort($items);
		$this->items = $items;
	}

	public function output () {
			//parent::output();
	}
}

?>