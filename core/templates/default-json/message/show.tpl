<?php
switch ($return['Header']) {
	case '200':
		echo header('HTTP/1.0 200 OK');
		break;
	case '400':
		echo header('HTTP/1.0 400 Bad Request');			
		break;
	case '403':
	default:
		echo header('HTTP/1.0 403 Forbidden');
		break;
}
?>
{"text": "<?php echo $return['html'] ?>", "class": "alert <?php echo $return['class'] ?>"}