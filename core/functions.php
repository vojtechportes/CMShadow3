<?php

function print_slot($slot, $array) {
	if (array_key_exists($slot, $array))
		echo $array[$slot];
}

function print_property($property, $array, $format = false, $return = false) {
	if (array_key_exists($property, $array)) {
		if (!$format) {
			if ($return) {
				return  $array[$property];
			} else {
				$val =  $array[$property];

				if ($val === true) {
					$val = 'true';
				} else if ($val === false) {
					$val = 'false';
				}

				echo $val;
			}
		} else {
			if ($return) {
				return sprintf($format, $array[$property]);
			} else {
				printf($format, $array[$property]);				
			}
		}
	}
}

function concat_property($property, $str, $array) {
	if (array_key_exists($property, $array)) {
		if ($array[$property].$str !== $array[$property])
			$array[$property] = $array[$property].$str;
	}
	return $array;
}

function redirect($path, $query) {
	global $Path;

	$location = filter_input(INPUT_GET, "source", FILTER_SANITIZE_URL);
	$host  = $_SERVER['HTTP_HOST'];
	parse_str(substr($location,1), $output);

	if ($location) {
		$path = $location;
	}
	
	$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');	
	if ($uri.'/' !== $Path) {
		header("Location:".SERVER_PROTOCOL."://$host$uri$path$query");
		die();
	} else {
		return;
	}
}

function flatten_array (array $array) {
    $return = array();
    array_walk_recursive($array, function($a) use (&$return) { $return[] = $a; });
    return $return;
}

function array_search_multi ($array, $key, $depth = 0) {
	preg_match_all("/\[[\"\']([^\[\]]+)[\"\']\]/", $key, $keys);
	if ($depth <= count($keys[1]) - 1) {
		$actual_key = $keys[1][$depth];		
		if (array_key_exists($actual_key, $array)) {	
			if (is_array($array[$actual_key])) {
				return array_search_multi($array[$actual_key], $key, $depth + 1);
			} else {
				if (count($keys[1]) - 1 === $depth) {
					return $array[$actual_key];
				} else {
					return false;
				}
			}
		} else {
			return false;
		}
	}
}

function array_parent_sort($idField, $parentField, $els, $parentID = 0, &$result = array(), &$depth = 0){
    foreach ($els as $key => $value) {
        if ($value[$parentField] == $parentID){
            $value['depth'] = $depth;
            array_push($result, $value);
            unset($els[$key]);
            $oldParent = $parentID; 
            $parentID = $value[$idField];
            $depth++;
            array_parent_sort($idField,$parentField, $els, $parentID, $result, $depth);
            $parentID = $oldParent;
            $depth--;
        }
    }
    return $result;
}

function json_validate ($string) {
	json_decode($string);
	return (json_last_error() == JSON_ERROR_NONE);
}

function replace_dc ($name) {
	$orig = array('á','č','ď','é','ě','í','ň','ó','ř','š','ť','ú','ů','ý','ž','ö','ü','ä','ë','Á','Č','Ď','É','Ě','Í','Ň','Ó','Ř','Š','Ť','Ú','Ů','Ý','Ž','Ö','Ü','Ä','Ë');
	$repl = array('a','c','d','e','e','i','n','o','r','s','t','u','u','y','z','o','u','a','e','a','c','d','e','e','i','n','o','r','s','t','u','u','y','z','o','u','a','e');
	
	return str_replace($orig, $repl, $name);
}

function get_seo_name ($name, $allow_dot = true, $path_mode = false, $max_len = 250, $spaces = false) {
	if ($name == '') {
		return '';
	} else {
		$name = replace_dc($name);
		$ret = iconv('UTF-8', 'UTF-8//IGNORE', $name);		// remove invalid non-utf8

		if ($path_mode) {
			$ret = str_replace('_', '?', $ret);		// release underscore
			$ret = str_replace('--', '_', $ret);		// ... for double dash
		}

		$ret   = iconv('UTF-8', 'ASCII//TRANSLIT', $ret);
		$ret   = trim(strtolower($ret));
		
		if ($allow_dot) { $dot = '.'; } else { $dot = ''; };
		if ($path_mode) { $pm_ch = '/_'; } else { $pm_ch = ''; };

		$ret   = preg_replace('|[\'"`]|i', '', $ret);
		$ret   = preg_replace('|[^0-9a-z'.$dot.$pm_ch.'-]|i', '-', $ret);
		$ret   = preg_replace('|-+|', '-', $ret);
		$ret   = str_replace('$', '--', $ret);

		if ($path_mode) {
			$ret = str_replace('_', '--', $ret);		// return double dash
		}

		$ret   = trim($ret, '-');

		if ($spaces) { $ret = str_replace('-', ' ', $ret); }
		if ($ret == '') {
			return '-';
		} else {
			if ($max_len > 0) {
				return substr($ret, 0, $max_len);
			} else {
				return $ret;
			}
		}
	}
}

?>