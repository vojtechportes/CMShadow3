<?php

function print_slot($slot, $array) {
	if (array_key_exists($slot, $array))
		echo $array[$slot];
}

function print_property($property, $array, $format = false) {
	if (array_key_exists($property, $array)) {
		if (!$format) {
			echo $array[$property];
		} else {
			printf($format, $array[$property]);
		}
	}
	echo '';
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
		header("Location: http://$host$uri$path$query");
	} else {
		return;
	}
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

function json_validate ($string) {
	json_decode($string);
	return (json_last_error() == JSON_ERROR_NONE);
}

?>