<?php
global $M;

$id = $M->extractID($return['Path']);
$_Project = new Project();
$Project = $_Project->getProjectByID($id);
$ProjectRights = array();

if ((bool) $Project['HasAdminRights'])
	$ProjectRights[] = 1;
if ((bool) $Project['HasEditorRights'])
	$ProjectRights[] = 2;

if ($val = filter_input(INPUT_POST, 'changeState', FILTER_VALIDATE_INT)) {
	$ProjectWorkflow = new ProjectWorkflow();
	$ProjectWorkflow->state = $val;
	$ProjectWorkflow->id = $id;
	$ProjectWorkflow->projectRights = $ProjectRights;
	$ProjectWorkflow->changeState();
	$Project = $_Project->getProjectByID($id);
}

if (empty($Project) || (int) $Project['HasRights'] === 0) {
	if (!empty($Project)) { $msg = "{_'forms_project_form_cant_modify'}"; } else { $msg = "{_'forms_project_form_doesnt_exist'}"; }
	
	$Message = new Module();
	$Message->addModule(new Message(), array("html" => $msg, "class" => "alert-danger", "OutputStyle" => $return["OutputStyle"], "OutputType" => $return["OutputType"], "Header" => 200));
	$Message->output();				
} else {
	$Workflow = $_Project->getProjectWorkflow()[$Project['Status']];
	$Workflow = $_Project->checkProjectWorkflowRights($Workflow, $ProjectRights);

	$return['workflow'] = $Workflow;
	$return['status'] = (int) $Project['Status'];
	
	$Module = new Module();
	$Module->template = '/admin/project/workflow';
	$Module->addModule(false, $return);
	$Module->output();
}

?>