<?php
global $M, $Path;
if ($M->_GET("success") && $M->_GET("form") === 'project-form') {
	$Message = new Module();
	$Message->addModule(new Message(), array("html" => "{_'forms_project_form_created', sprintf([\"{$M->_GET("name")}\", \"/admin/projects/edit/{$M->_GET("projectID")}\", \"{$M->_GET("path")}?form_open=project-form-element\"])}", "class" => "alert-success", "OutputStyle" => $return["OutputStyle"], "OutputType" => $return["OutputType"], "Header" => 200));
	$Message->output();
} else if ($M->_GET("error") && $M->_GET("form") === 'project-form') {
	$Message = new Module();
	$Message->addModule(new Message(), array("html" => "{_'forms_project_form_error', sprintf([\"{$M->_GET("name")}\", \"{$M->_GET("path")}\"])}", "class" => "alert-danger", "OutputStyle" => $return["OutputStyle"], "OutputType" => $return["OutputType"], "Header" => 200));
	$Message->output();		
} else {
	if ($M->_GET('form_open') === 'project-form-element') {
		$return = concat_property('class', ' in', $return);
	}

	$Code = new Module();
	$Code->template = '/code';
	$Code->addModule(false, array("html" => '<div class="form-wrapper '.print_property('class', $return, false, true).'"><div class="clearfix"></div>'));
	$Code->output();

	$Message = new Module();
	$Message->addModule(new Message(), array("html" => "{_'forms_project_form_help'}", "class" => "alert-info", "OutputStyle" => $return["OutputStyle"], "OutputType" => $return["OutputType"], "Header" => 200));
	$Message->output();

	$type = filter_input(INPUT_GET, "type", FILTER_SANITIZE_STRING);
	if (!isset($type))
		$type = 'create';

	$Users = new UserList();
	$UserList = $Users->getUsers(array(4, 3));
	$_userlist = array();
	if (count($UserList) > 0) {
		foreach ($UserList as $user) {
			$_userlist[$user['ID']] = $user['Name'];
		}
	}

	$form = new Form ('form_project');
	$form->method = 'POST';
	$form->type = 'rows';
	$form->template = '/admin/project/form';
	$form->classInput = 'form-control';

	$form->addElement(new FormElement_Text("{_'forms_project_name'}", "name", array("required" => true)));
	$form->addElement(new FormElement_Text("{_'forms_project_release_date'}", "release_date", array("required" => true)));
	$form->addElement(new FormElement_Textarea("{_'forms_project_description'}", "description", array("required" => true)));	
	$form->addElement(new FormElement_Select("{_'forms_project_owners'}", "owners[]", array("required" => true, "multiple" => true, "options" => $_userlist)));
	$form->addElement(new FormElement_Select("{_'forms_project_editors'}", "editors[]", array("required" => false, "multiple" => true, "options" => $_userlist)));
	$form->addElement(new FormElement_Submit(false, "submit_project", array("value" => "{_'forms_project_submit'}", "classInput" => "btn btn-block btn-primary")));

	$Output = $form->output($return);

	if ($Output) {
		$Project = new Project();

		/* Project */

		$Project->name = filter_input(INPUT_POST, "name");
		$Project->description = filter_input(INPUT_POST, "description");
		$Project->relese_date = filter_input(INPUT_POST, "release_date");

		/* Project Owners */
		$Project->owners = filter_input(INPUT_POST, "owners", FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
		$Project->editors = filter_input(INPUT_POST, "editors", FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);		

		if ($Project->createProject() && $Project->setProjectOwners()) {
			redirect("{$Path}?success&name={$Project->name}&path={$Path}&projectID={$LastID}&form=project-form");
		} else {
			redirect("{$Path}?error&name={$Project->name}&path={$Path}&form=project-form");
		}
	}

	$Code = new Module();
	$Code->template = '/code';
	$Code->addModule(false, array("html" => '<div class="clearfix"></div></div>'));
	$Code->output();
}
?>