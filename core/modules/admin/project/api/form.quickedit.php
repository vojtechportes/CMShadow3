<?php
global $M, $Path;

$message = true;
if (array_key_exists('message', $return)) {
	if (! (bool) $return['arguments']['message'])
		$message = false;
}

$id = false;
$canDisplay = true;
$projectExist = true;
$type = 'create';

if ($canDisplay) {
	$Code = new Module();
	$Code->template = '/code';
	$Code->addModule(false, array("html" => '<div class="form-wrapper quick-edit"><div class="clearfix"></div>'));
	$Code->output();

	$form = new Form ('form_project_quick');
	$form->method = 'POST';
	$form->type = 'rows';
	$form->template = '/admin/project/form.quickedit';
	$form->classInput = 'form-control';

	$form->addElement(new FormElement_Text("{_'forms_project_name'}", "form_project_quick-name", array("required" => true, "value" => "{_'projects_project_quick_edit'}{$return['pageName']}")));
	$form->addElement(new FormElement_Text("{_'forms_project_release_date'}", "form_project_quick-release_date", array("required" => true)));
	$form->addElement(new FormElement_Hidden(false, "form_project_quick-pages", array("required" => true, "value" => $return['pageID'])));
	$form->addElement(new FormElement_Submit(false, "submit_project", array("value" => "{_'forms_project_submit'}", "classInput" => "btn btn-block btn-primary")));

	$Output = $form->output($return);

	if ($Output) {
		$Project = new Project();

		/* Project */

		$Project->name = filter_input(INPUT_POST, "form_project_quick-name");
		$Project->description = filter_input(INPUT_POST, "form_project_quick-description");
		$Project->relese_date = filter_input(INPUT_POST, "form_project_quick-release_date");

		/* Project Owners */
		$UserID = User::getUserID();
		$Project->owners = array($UserID);
		$Project->editors = array($UserID);	

		/* Pages */
		$Page = (int) filter_input(INPUT_POST, "pages", FILTER_SANITIZE_STRING);				

		$Page = new Page();
		$Page->version = 0;
		$PagesFailed = array();

		if ($Project->createProject() && $Project->setProjectOwners()) {
			if (!$Page->isLocked($Page)) {
				$Page->lockPage($Page);
				$Page->clonePage($Page, $Project->status);
			} else {
				array_push($PagesFailed, $Page);
			}


			if (empty($PagesFailed)) {
				$message = "{_'forms_project_form_created', sprintf([\"{$Project->name}\", \"/admin/projects/edit/{$Project->getCurrentProjectID()}\", \"#\"])}";
			} else {
				$message = "{_'forms_project_form_created_exception', sprintf([\"{$Project->name}\", \"/admin/projects/edit/{$Project->getCurrentProjectID()}\", \"#\", \"".implode(', ', $PagesFailed)."\"])}";
			}
			$Message = new Module();
			$Message->addModule(new Message(), array("html" => $message, "class" => "alert-success", "OutputStyle" => $return["OutputStyle"], "OutputType" => $return["OutputType"], "Header" => 200));
			$Message->output();	
		} else {
			$Message = new Module();
			$Message->addModule(new Message(), array("html" => "{_'forms_project_form_error', sprintf([\"{$Project->name}\", \"#\"])}", "class" => "alert-danger", "OutputStyle" => $return["OutputStyle"], "OutputType" => $return["OutputType"], "Header" => 200));
			$Message->output();	
		}
	}

	$Code = new Module();
	$Code->template = '/code';
	$Code->addModule(false, array("html" => '<div class="clearfix"></div></div>'));
	$Code->output();
} else {
	if ($projectExist) { $msg = "{_'forms_project_form_cant_modify'}"; } else { $msg = "{_'forms_project_form_doesnt_exist'}"; }
	$Message = new Module();
	$Message->addModule(new Message(), array("html" => $msg, "class" => "alert-danger", "OutputStyle" => $return["OutputStyle"], "OutputType" => $return["OutputType"], "Header" => 200));
	$Message->output();			
}
?>