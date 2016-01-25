<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.use_trans_sid', 0);
ini_set('session.cache_limiter', 'nocache');
ini_set('session.cookie_path', '/');

/*
* CMS Autoloader
* ==============
*/

global $Register;

require_once '/core/phpids/Register.php';

$Register = array();
$Register += $IDSRegister;


spl_autoload_register(function ($class_name) {
	global $Register;

	if (explode('\\', $class_name)[0] === 'IDS') {
		if (array_key_exists($class_name, $Register)) {
			include $Register[$class_name];
		}
	} else {
		if (!strstr(strtolower($class_name), 'less'))
    		include '/core/class/'.strtolower($class_name).'.php';
	}
});

/* IDS not working properly, need to be replaced by EXPOSE PHP IDS */

//require_once '/core/phpids/IDS.php';

if (!session_id()) {
	session_start();
}

//phpinfo();

/**/

function print_var_name($var) {
    foreach($GLOBALS as $var_name => $value) {
        if ($value === $var) {
            return $var_name;
        }
    }

    return false;
}

/**/

/*
* Global variables
* ================
*/

global $DB, $Template, $M, $Path, $LoggedIn, $Rights, $Node, $ForceOutput;

$ForceOutput = true;

/*
* Config
* ======
*/

require_once '/core/config.php';

/*
* Functions
* =========
*/

require_once '/core/functions.php';

$M = new Minimal ();
$Path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if ($Path !== "/" && substr($Path, -1) !== '/')
	$Path = $Path."/";


/*
* LESS Parser settings + Autoloader registration
* ==============================================
*/

require_once '/core/class/lib/Less/Autoloader.php';
Less_Autoloader::register();
require_once '/core/class/lessc.inc.php';

$Less = new lessc ();
$Less->setFormatter("compressed");
$Less->checkedCompile(DEFAULT_LESS_PATH, DEFAULT_CSS_PATH);


/*
* Database class declaration + connection settings
* ================================================
*/

$DBConnection = true;

try {

	$DB = new PDO("mysql:host=".DB_HOST, DB_USER_NAME, DB_PASSWORD);
	$DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$DBName = "`".str_replace("`","``",DB_NAME)."`";
	$DB->query("USE $DBName");

} catch (Exception $e) {
	$DBConnection = false;
	$M->load(DEFAULT_MODULE_PATH.'default/error/show.php', array('html' => DEFUALT_ERROR_DB_CONNECTION), false);
}


/*
* Node class declaration + Loader
* ===============================
*/

if ($DBConnection) {

	/*
	* Login status
	* ============
	*/

	$User = new User();
	$LoggedIn = $User->getUserSessionStatus();	
	$Rights = $User->getUserRights();

	ob_start();
	$Node = new Node ();
	$Node = $Node->output();
	//echo $M->sanitize($Node, 'html');
	echo $Node;
	$Node = ob_get_contents(); ob_end_clean();

	echo $Node;

}

?>