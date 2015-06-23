<?php
switch ($return['Header']) {
	case '200':
		echo header('HTTP/1.0 200 OK');
		break;
	case '400':
		if (array_key_exists("uberFail", $return)) {
			echo header('HTTP/1.0 406 Not Acceptable');
		} else {
			echo header('HTTP/1.0 400 Bad Request');			
		}
		var_dump($return);
		break;
	default:
		echo header('HTTP/1.0 403 Forbidden');
	break;
}
?>
{"text": "<?php echo $return['html'] ?>", "class": "alert <?php echo $return['class'] ?>"}