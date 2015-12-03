<?php

Class Minimal {

	public function debug ($data = false, $identifier = "default") {
		echo "<pre style=\"padding: 20px; margin: 20px; border: 1px solid #EEE;\"><strong>Debug: (".$identifier.")</strong><p style=\"margin: 25px 0; padding: 25px 0; border-top: 1px solid #EEE; border-bottom: 1px solid #EEE;\">", var_dump((!$data) ? $this : $data), "</p><strong>Backtrace:</strong><p style=\"color: #777;\">", debug_print_backtrace(), "</p></pre>";
	}

	public function filter ($str) {
		return htmlspecialchars($str);
	}	

	public function sanitize ($string, $type = 'HTML') {
		switch ($type) {
			case 'html':
			    $search = array(
			        '/\>[^\S ]+/s', //strip whitespaces after tags, except space
			        '/[^\S ]+\</s', //strip whitespaces before tags, except space
			        '/(\s)+/s'  // shorten multiple whitespace sequences
			        );
			    $replace = array(
			        '>',
			        '<',
			        '\\1'
			        );
				$string = preg_replace($search, $replace, $string);
				break;
		}
	    return $string;
	}	

	public function load ($path, $return = '.', $ext = true) {
		if ($ext) {
			$extension = '.tpl';
		} else {
			$extension = '';
		}

		if ($__result = include $path.$extension) {
			if (is_array($return)) {
				$result = compact($return);
			} else if ($__return = '.') {
				$result = compact(array_diff(array_keys(get_defined_vars()),
					array('GLOBALS', 'return')));
			} else {
				$result = array();
			}
			array_unshift($result, $__result);
			return $result;
		}			
		return array($__result);				
	}

	public static function getLastID ($Table) {
		global $DB;

		$Stm = $DB->prepare("SELECT `AUTO_INCREMENT` FROM  INFORMATION_SCHEMA.TABLES WHERE `TABLE_SCHEMA` = :Database AND `TABLE_NAME` = :Table");
		$Stm->execute(array(':Database' => DB_NAME, ':Table' => DB_PREFIX.$Table));
		$Res = $Stm->fetch(PDO::FETCH_ASSOC);
		if (is_array($Res))
			return $Res['AUTO_INCREMENT'];
		return false;
	}

	private function checkVariableState ($value) {
		if ($value === NULL) {
			return false;
		} else if ($value === "") {
			return true;
		} else {
			return $value;
		}
	}

	public function _GET ($key) {
		return $this->checkVariableState(filter_input(INPUT_GET, $key, FILTER_SANITIZE_STRING));

	}

	public function _POST ($key) {
		return $this->checkVariableState(filter_input(INPUT_POST, $key, FILTER_SANITIZE_STRING));
	}

}

?>