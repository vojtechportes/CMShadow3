<?php

global $M;

$ModuleRights = array();
$NodeRights = array();
$APIRights = array();

$MRights = file_get_contents(DEFAULT_CORE_PATH."modulerights.conf.json", false);
$MRights = (array) json_decode($MRights);

$NRights = file_get_contents(DEFAULT_CORE_PATH."noderights.conf.json", false);
$NRights = (array) json_decode($NRights);

$ARights = file_get_contents(DEFAULT_CORE_PATH."apirights.conf.json", false);
$ARights = (array) json_decode($ARights);

$Files = new FileSystem (false, true);
$Files->path = DEFAULT_MODULE_PATH;
$Files->separator = '/';
$Files->pathStrip = true;
$Files->extensionStrip = true;
$Modules = $Files->output();

foreach ($Modules as $Module) {
	if (array_key_exists($Module, $MRights)) {
		foreach ($MRights[$Module] as $Right) {
			$ModuleRights[] = array("Module" => $Module, "Group" => $Right);
		}
	} else {
		$ModuleRights[] = array("Module" => $Module, "Group" => 1);		
	}
}

foreach ($NRights as $Node => $Groups) {
	foreach ($Groups as $Group) {
		$NodeRights[] = array("Node" => $Node, "Group" => $Group);
	}
}

foreach ($ARights as $Command => $Groups) {
	foreach ($Groups as $Group) {
		$APIRights[] = array("Command" => $Command, "Group" => $Group);
	}
}

$Database = array(
	array(
		"query" => "CREATE TABLE IF NOT EXISTS `T_Install` (`Status` INT(11) NOT NULL) ENGINE = MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci"
	),
	array(
		"query" => "CREATE TABLE IF NOT EXISTS `T_UserGroups` (`ID` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT, `Name` CHAR(60) NOT NULL) ENGINE = MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci"
	),
	array(
		"exec-repeat(\$Groups as \$Group)" => "INSERT INTO `T_UserGroups` (`Name`) VALUES ('\$Group[\"Name\"]')"
	),
	array(
		"query" => "CREATE TABLE IF NOT EXISTS `T_User` (`ID` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT, `SSID` CHAR(120), `Name` CHAR(60) NOT NULL, `Password` TEXT(300) NOT NULL) ENGINE = MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci"
	),
	array(
		"exec-repeat(\$Users as \$User)" => "INSERT INTO `T_User` (`Name`, `Password`) VALUES ('\$User[\"Name\"]', '\$User[\"Password\"]')"
	),
	array(
		"query" => "CREATE TABLE IF NOT EXISTS `T_UserAttempts` (`ID` INT(11) NOT NULL PRIMARY KEY, `Attempts` INT(3) NOT NULL) ENGINE = MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci"
	),
	array(
		"exec-repeat(\$UserAttempts as \$Attempt)" => "INSERT INTO `T_UserAttempts` (`ID`, `Attempts`) VALUES ('\$Attempt[\"ID\"]', '\$Attempt[\"Attempts\"]')"
	),			
	array(
		"query" => "CREATE TABLE IF NOT EXISTS `T_UserRights` (`ID` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT, `User` INT(11) NOT NULL, `Group` INT(11) NOT NULL)"
	),
	array(
		"exec-repeat(\$UserRights as \$Right)" => "INSERT INTO `T_UserRights` (`User`, `Group`) VALUES (\$Right[\"User\"], \$Right[\"Group\"])"
	),	
	array(
		"query" => "CREATE TABLE IF NOT EXISTS `T_ModuleRights` (`ID` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT, `Module` CHAR(120), `Group` INT(11) NOT NULL) ENGINE = MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci"	
	),
	array(
		"exec-repeat(\$ModuleRights as \$Module)" => "INSERT INTO `T_ModuleRights` (`Module`, `Group`) VALUES ('\$Module[\"Module\"]', \$Module[\"Group\"])"
	),
	array(
		"query" => "CREATE TABLE IF NOT EXISTS `T_NodeRights` (`ID` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT, `Node` TEXT(600), `Group` INT(11) NOT NULL) ENGINE = MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci"	
	),
	array(
		"exec-repeat(\$NodeRights as \$Node)" => "INSERT INTO `T_NodeRights` (`Node`, `Group`) VALUES ('\$Node[\"Node\"]', \$Node[\"Group\"])"
	),
	array(
		"query" => "CREATE TABLE IF NOT EXISTS `T_APIRights` (`ID` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT, `Command` TEXT(600), `Group` INT(11) NOT NULL) ENGINE = MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci"	
	),
	array(
		"exec-repeat(\$APIRights as \$Command)" => "INSERT INTO `T_APIRights` (`Command`, `Group`) VALUES ('\$Command[\"Command\"]', \$Command[\"Group\"])"
	),	
	array(
		"query" => "CREATE TABLE IF NOT EXISTS `T_ConfigCategories` (`ID` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT, `Title` TEXT(600)  NOT NULL, `Path` TEXT(300) NOT NULL, `Icon` CHAR(100)) ENGINE = MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci"	
	),		
	array(
		"exec-repeat(\$ConfigCategories as \$Category)" => "INSERT INTO `T_ConfigCategories` (`Title`, `Path`, `Icon`) VALUES ('\$Category[\"Title\"]', '\$Category[\"Path\"]', '\$Category[\"Icon\"]')"
	),
	array(
		"query" => "CREATE TABLE IF NOT EXISTS `T_Gadgets` (`ID` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT, `Gadget` TEXT(600) NOT NULL, `User` INT(11) NOT NULL, `Weight` INT(5) NOT NULL DEFAULT 50) ENGINE = MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci"	
	),	
	array(
		"query" => "CREATE TABLE IF NOT EXISTS `T_Pages` (`ID` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT, `PageRefference` INT(11) NOT NULL DEFAULT 0, `Parent` INT(11) NOT NULL, `Owner` INT(11) NOT NULL, `ChangedBy` INT(11) NOT NULL, `Version` INT(11) NOT NULL DEFAULT 0, `Locked` INT(1) NOT NULL DEFAULT 0, `Visible` INT(1) NOT NULL DEFAULT 0, `URL` TEXT(400) NOT NULL, `Weight` INT(5) NULL DEFAULT 50, `CreatedAt` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, `ModifiedAt` TIMESTAMP DEFAULT 0 ON UPDATE CURRENT_TIMESTAMP) ENGINE = MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci"	
	),	
	array(
		"query" => "CREATE TABLE IF NOT EXISTS `T_PageDetails` (`ID` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT, `PageRefference` INT(11) NOT NULL DEFAULT 0, `Version` INT(11) NOT NULL DEFAULT 0, `Page` INT(11) NOT NULL, `Name` CHAR(200) NULL DEFAULT NULL, `Title` CHAR(200) NULL DEFAULT NULL, `Description` TEXT(600) NULL DEFAULT NULL, `Keywords` TEXT(600) NULL DEFAULT NULL, `Template` INT(11) DEFAULT 0) ENGINE = MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci"
	),
	array(
		"query" => "CREATE TABLE IF NOT EXISTS `T_Projects` (`ID` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT, `Name` TEXT(200) NOT NULL, `Description` TEXT(1000) NULL, `Locked` INT(1) NOT NULL DEFAULT 0, `ReleaseDate` TIMESTAMP DEFAULT 0, `Status` INT(1) NOT NULL DEFAULT 1,  `CreatedAt` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, `ModifiedAt` TIMESTAMP DEFAULT 0 ON UPDATE CURRENT_TIMESTAMP) ENGINE = MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci"	
	),	
	array(
		"query" => "CREATE TABLE IF NOT EXISTS `T_ProjectOwners` (`ID` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT, `Project` INT(11) NOT NULL, `User` INT(11) NOT NULL, `Role` INT(11)) ENGINE = MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci"
	)				
);

$DatabaseValues = array(
	"Groups" => array(
		array("Name" => "Admin"),
		array("Name" => "Editor"),
		array("Name" => "User"),
		array("Name" => "Visitor")					
	),
	"Users" => array(
		array(
			"Name" => "admin",
			"Password" => password_hash("admin", PASSWORD_BCRYPT)
		)
	),
	"UserAttempts" => array(
		array(
			"ID" => 1,
			"Attempts" => DEFAULT_LOGIN_ATTEMPS
		)
	),	
	"UserRights" => array(
		array(
			"User" => 1,
			"Group" => 1
		)
	),
	"ModuleRights" => $ModuleRights,
	"NodeRights" => $NodeRights,
	"APIRights" => $APIRights,	
	"ConfigCategories" => array(
		array(
			"Title" => "{_\'pages_settings-module-rights_title\'}",
			"Path" => "/admin/settings/module-rights/",
			"Icon" => "glyphicon glyphicon-th-large"
		),
		array(
			"Title" => "{_\'pages_settings-node-rights_title\'}",
			"Path" => "/admin/settings/node-rights/",
			"Icon" => "glyphicon glyphicon-th-list"
		),
		array(
			"Title" => "{_\'page_settings-api-rights_title\'}",
			"Path" => "/admin/settings/api-rights/",
			"Icon" => "glyphicon glyphicon-share"
		),
		array(
			"Title" => "{_\'pages_settings-config-pages_title\'}",
			"Path" => "/admin/settings/pages/",
			"Icon" => "glyphicon glyphicon-file"
		),
		array(
			"Title" => "{_\'pages_settings-admin-home_title\'}",
			"Path" => "/admin/settings/admin-home/",
			"Icon" => "glyphicon glyphicon-home"
		),
		array(
			"Title" => "{_\'pages_settings-config-triggers_title\'}",
			"Path" => "/admin/settings/triggers/",
			"Icon" => "glyphicon glyphicon-fire"
		)
	)
)

?>