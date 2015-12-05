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

	public function substitute (array $array, $string, $encode = false) {
		return preg_replace_callback($this->placeholder, function($matches) use ($array, $encode) {				
				if (array_key_exists($matches[1], $array)) {
					$args = array_values(array_filter($matches));
					if (count($args) > 2) {
						switch($args[2]) {
							case "sprintf":
									if (substr($args[3], 0, 2) === '[\\') {
										$args[3] = str_replace('\\"', '"', $args[3]);
										$args[3] = str_replace('\\\\"', '\\"', $args[3]);
									}
									if (json_validate($args[3])) {
										if (!empty($args[3])) {
											$args[3] = json_decode($args[3]);
											array_unshift($args[3], $array[$args[1]]);
											return addslashes(call_user_func_array("sprintf", $args[3]));
										}
									} else {
										return  addslashes(sprintf($array[$args[1]], $args[3]));
									}					
								break;
							case "replace":
									return  addslashes(str_replace($args[3], $args[4], $array[$args[1]]));
								break;
						}
					}
					if ($encode) {
						return  htmlentities($array[$args[1]]);
					} else {
						return  $array[$args[1]];
					}
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

?>