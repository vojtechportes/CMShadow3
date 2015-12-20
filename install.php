<?php

/*
* CMShadow 3 Installer v1.0.0 
* ===========================
*/

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

global $DB, $M, $Path;

/*
* CMS Autoloader
* ==============
*/

function cms_autoload($class_name) {
	if (!strstr(strtolower($class_name), 'less'))
    	include '/core/class/'.strtolower($class_name).'.php';
}

spl_autoload_register('cms_autoload');

/*
* Config
* ======
*/

require_once '/core/config.php';

$M = new Minimal ();
$Path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$Path = '/'.preg_replace("/^".str_replace("/", "\/",BASE_PATH)."/i", "", $Path);
if ($Path !== "/" && substr($Path, -1) !== '/')
	$Path = $Path."/";

/*
* Database class declaration + connection settings
* ================================================
*/

$DB = new PDO("mysql:host=".DB_HOST, DB_USER_NAME, DB_PASSWORD);
$DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$DBName = "`".str_replace("`","``",DB_NAME)."`";
$DB->query("CREATE DATABASE IF NOT EXISTS $DBName CHARACTER SET utf8 COLLATE utf8_general_ci");
$DB->query("USE $DBName");

$Install = new Install();
$Install->source = '/core/database.php';
$Status = $Install->DBInstall();

$M->debug($Status);

?>