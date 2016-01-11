<?php
global $M, $Path;

if ((int) $M->_POST('form_open') === 1) {
	$return = concat_property('class', ' in', $return);
}

$message = true;
if (array_key_exists('message', $return)) {
	if (! (bool) $return['arguments']['message'])
		$message = false;
}

$id = false;
$canDisplay = true;
$projectExist = true;
$type = 'create';

if (array_key_exists('type', $return)) {
	if ($return['type'] === 'edit') {
		$type = 'edit';
		$return = concat_property('class', ' in', $return);
		$id = $M->extractID($return['Path']);
		if ($id) {
			/*load slot info*/
		}
	}
}

if ($canDisplay) {
	$Code = new Module();
	$Code->template = '/code';
	$Code->addModule(false, array("html" => '<div class="form-wrapper '.print_property('class', $return, false, true).'"><div class="clearfix"></div>'));
	$Code->output();

	if ($message) {
		$Message = new Module();
		$Message->addModule(new Message(), array("html" => "{_'forms_layout_slot_form_help'}", "class" => "alert-info", "OutputStyle" => $return["OutputStyle"], "OutputType" => $return["OutputType"], "Header" => 200));
		$Message->output();
	}

	$slots = array("0" => "{_'forms_layout_slot_parent_root'}");

	$form = new Form ('form_layout');
	$form->method = 'POST';
	$form->type = 'rows';
	$form->template = '/admin/layout/form.slot';
	$form->classInput = 'form-control';

	if ($type === 'create') {
		$form->addElement(new FormElement_Text("{_'forms_layout_slot_name'}", "name", array("required" => true)));
		$form->addElement(new FormElement_Select("{_'forms_layout_slot_parent'}", "parent[]", array("required" => true, "options" => $slots)));
		$form->addElement(new FormElement_Text("{_'forms_layout_slot_weight'}", "weight", array("required" => false)));
		$form->addElement(new FormElement_Text("{_'forms_layout_slot_class'}", "class", array("required" => false)));
		$form->addElement(new FormElement_Text("{_'forms_layout_slot_identifier'}", "identifier", array("required" => false)));
		$form->addElement(new FormElement_Text("{_'forms_layout_slot_data'}", "data", array("required" => false)));

		$form->addElement(new FormElement_Submit(false, "submit_project", array("value" => "{_'forms_project_submit'}", "classInput" => "btn btn-block btn-primary")));
	} else {
		/* Edit fields*/
	}



	$Output = $form->output($return);

	if ($Output) {
		$Project = new Project();

		/* Project */

		if ($type === 'edit')
			$Project->id = $id;

		$Project->name = filter_input(INPUT_POST, "name");
		$Project->description = filter_input(INPUT_POST, "description");
		$Project->relese_date = filter_input(INPUT_POST, "release_date");

		/* Project Owners */
		$Project->owners = filter_input(INPUT_POST, "owners", FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
		$Project->editors = filter_input(INPUT_POST, "editors", FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);	

		/* Pages */
		$Pages = filter_input(INPUT_POST, "pages", FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);				

		$Page = new Page();
		$Page->version = 0;
		$PagesFailed = array();

		switch ($type) {
			case 'create':
				if (/*$Project->createProject() && $Project->setProjectOwners()*/ false) {

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
				break;
			case 'edit':
				if ($Project->updateProject() && $Project->setProjectOwners(true)) {
					if (!empty($Pages)) {
						foreach ($Pages as $page) {
							$page = (int) $page;
							if (!$Page->isLocked($page)) {
								$Page->lockPage($page);
								$Page->clonePage($page, $Project->id);
							} else {
								array_push($PagesFailed, $page);
							}
						}
					}

					if (empty($PagesFailed)) {
						$message = "{_'forms_project_form_updated', sprintf([\"{$Project->name}\", \"#\"])}";
					} else {
						$message = "{_'forms_project_form_updated_exception', sprintf([\"{$Project->name}\", \"#\", \"".implode(', ', $PagesFailed)."\"])}";
					}

					$Message = new Module();
					$Message->addModule(new Message(), array("html" => $message, "class" => "alert-success", "OutputStyle" => $return["OutputStyle"], "OutputType" => $return["OutputType"], "Header" => 200));
					$Message->output();
				} else {
					$Message = new Module();
					$Message->addModule(new Message(), array("html" => "{_'forms_project_form_update_error', sprintf([\"{$Project->name}\", \"#\"])}", "class" => "alert-danger", "OutputStyle" => $return["OutputStyle"], "OutputType" => $return["OutputType"], "Header" => 200));
					$Message->output();	
				}
				break;
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