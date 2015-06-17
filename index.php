<?php

session_start();

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

global $DB, $Template, $M, $Path, $LoggedIn, $Rights;

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

/*
* CMS Autoloader
* ==============
*/

function cms_autoload($class_name) {
	if (!strstr(strtolower($class_name), 'less'))
    	include '/core/class/'.strtolower($class_name).'.php';
}

spl_autoload_register('cms_autoload');

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

	$Node = new Node ();
	$Node = $Node->output();

	//echo $M->sanitize($Node, 'html');

	echo $Node;

}

?>