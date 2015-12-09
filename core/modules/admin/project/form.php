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

	$message = true;
	if (array_key_exists('message', $return)) {
		if (!$return['message'])
			$message = false;
	}

	$id = false; $canDisplay = true; $projectExist = true;
	if (array_key_exists('type', $return)) {
		if (!$return['type'] === 'edit') {
			$type = 'create';
		} else {
			$id = $M->extractID($Path);
			if ($id) {
				$_Project = new Project();
				$Project = $_Project->getProjectByID($id);
				if (empty($Project) || (int) $Project['HasRights'] === 0) {
					if (empty($Project))
						$projectExist = false;
					$canDisplay = false;
				} else {
					$OwnerValues = $_Project->getProjectOwners($Project['ID'], 1);
					$OwnerValues = flatten_array($OwnerValues);
					$EditorValues = $_Project->getProjectOwners($Project['ID'], 2);
					$EditorValues = flatten_array($EditorValues);
				}
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
			$Message->addModule(new Message(), array("html" => "{_'forms_project_form_help'}", "class" => "alert-info", "OutputStyle" => $return["OutputStyle"], "OutputType" => $return["OutputType"], "Header" => 200));
			$Message->output();
		}

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

		if ($type === 'create') {
			$form->addElement(new FormElement_Text("{_'forms_project_name'}", "name", array("required" => true)));
			$form->addElement(new FormElement_Text("{_'forms_project_release_date'}", "release_date", array("required" => true)));
			$form->addElement(new FormElement_Textarea("{_'forms_project_description'}", "description", array("required" => true)));	
			$form->addElement(new FormElement_Select("{_'forms_project_owners'}", "owners[]", array("required" => true, "multiple" => true, "options" => $_userlist)));
			$form->addElement(new FormElement_Select("{_'forms_project_editors'}", "editors[]", array("required" => false, "multiple" => true, "options" => $_userlist)));
			$form->addElement(new FormElement_Submit(false, "submit_project", array("value" => "{_'forms_project_submit'}", "classInput" => "btn btn-block btn-primary")));
		} else {
			$form->addElement(new FormElement_Text("{_'forms_project_name'}", "name", array("required" => true, "value" => $Project['Name'])));
			$form->addElement(new FormElement_Text("{_'forms_project_release_date'}", "release_date", array("required" => true, "value" => $Project['ReleaseDate'])));
			$form->addElement(new FormElement_Textarea("{_'forms_project_description'}", "description", array("required" => true, "value" => $Project['Description'])));	
			$form->addElement(new FormElement_Select("{_'forms_project_owners'}", "owners[]", array("required" => true, "multiple" => true, "options" => $_userlist, "selected" => $OwnerValues)));
			$form->addElement(new FormElement_Select("{_'forms_project_editors'}", "editors[]", array("required" => false, "multiple" => true, "options" => $_userlist, "selected" => $EditorValues)));
			$form->addElement(new FormElement_Submit(false, "submit_project", array("value" => "{_'forms_project_submit_edit'}", "classInput" => "btn btn-block btn-primary")));			
		}



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
	} else {
		$Message = new Module();
		if ($projectExist) { $msg = "{_'forms_project_form_cant_modify'}"; } else { $msg = "{_'forms_project_form_doesnt_exist'}"; }
		$Message->addModule(new Message(), array("html" => $msg, "class" => "alert-danger", "OutputStyle" => $return["OutputStyle"], "OutputType" => $return["OutputType"], "Header" => 200));
		$Message->output();			
	}
}
?>