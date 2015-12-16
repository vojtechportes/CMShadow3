<?php
global $M;

if (!array_key_exists('projectAuth', $return))
	$return['projectAuth'] = false;

$id = $M->extractID($return['Path']);
$_Project = new Project();
$Project = $_Project->getProjectByID($id);
$StatusList = $_Project->getProjectStatusList();
$Owners = $_Project->getProjectOwners($id, 1);
$Editors = $_Project->getProjectOwners($id, 2);

if (empty($Project) || (int) $Project['HasRights'] === 0) {
	if (!empty($Project)) { $msg = "{_'forms_project_form_cant_modify'}"; } else { $msg = "{_'forms_project_form_doesnt_exist'}"; }
	
	$Message = new Module();
	$Message->addModule(new Message(), array("html" => $msg, "class" => "alert-danger", "OutputStyle" => $return["OutputStyle"], "OutputType" => $return["OutputType"], "Header" => 200));
	$Message->output();		
} else {
	$Module = new Module();
	$Module->template = '/admin/project/details';
	$Module->addModule(false, $return + array('Project' => $Project, 'StatusList' => $StatusList, "Users" => array("Owners" => $Owners, "Editors" => $Editors)));
	$Module->output();	
}

?>