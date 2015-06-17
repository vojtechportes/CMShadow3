<?php

Class Stringtable Extends Minimal {

	private $strings;
	private $placeholder = "(\{[!]?_'([^{}]+)'(?:,\s?)?(?:(sprintf)\(([^()]+)\)|(replace)\(([^()]+),\s?([^()]+)\))?\})";
	public $source = "file";

	public function load ($path) {
		if ($this->source == 'file') {
			if (file_exists(DEFAULT_STRINGTABLE_PATH.$path.'.php')) {
				require_once DEFAULT_STRINGTABLE_PATH.$path.'.php';
				$this->strings[] = $Stringtable;
			}
		} else if ($this->source == 'database') {

		}
	}

	public function substitute (array $array, $string) {
		return preg_replace_callback($this->placeholder, function($matches) use ($array) {
				if (array_key_exists($matches[1], $array)) {
					$args = array_values(array_filter($matches));
					if (count($args) > 2) {
						//parent::debug($args);
						switch($args[2]) {
							case "sprintf":
									return  sprintf($array[$args[1]], $args[3]);							
								break;
							case "replace":
									return  str_replace($args[3], $args[4], $array[$args[1]]);
								break;
						}
					}
					return  $array[$args[1]];
				} else {
					return '';
				}
		}, $string);	
	}

	public function output () {
		$output = array();
		foreach ($this->strings as $strings) {
			$output = array_merge($output, $strings);
		}
		return $output;
	}
}