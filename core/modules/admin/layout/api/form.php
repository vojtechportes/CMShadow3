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

if (!array_key_exists('Path', $return))
	$return['Path'] = $Path;

if (array_key_exists('type', $return)) {
	if ($return['type'] === 'edit') {
		$type = 'edit';
		$return = concat_property('class', ' in', $return);
		$id = $M->extractID($return['Path']);

		if ($id) {
			$Layout = new Layout();
			$Layout = $Layout->getLayoutByID($id);

			if (!$Layout)
				$canDisplay = false;
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
		$Message->addModule(new Message(), array("html" => "{_'forms_layout_form_help'}", "class" => "alert-info", "OutputStyle" => $return["OutputStyle"], "OutputType" => $return["OutputType"], "Header" => 200));
		$Message->output();
	}

	$form = new Form ('form_project');
	$form->method = 'POST';
	$form->type = 'rows';
	$form->template = '/admin/project/form';
	$form->classInput = 'form-control';

	if ($type === 'create') {
		$form->addElement(new FormElement_Text("{_'forms_layout_name'}", "name", array("required" => true)));
		$form->addElement(new FormElement_Submit(false, "submit_layout", array("value" => "{_'forms_layout_submit'}", "classInput" => "btn btn-block btn-primary")));
	} else {
		$form->addElement(new FormElement_Text("{_'forms_layout_name'}", "name", array("required" => true, "value" => $Layout['LayoutName'])));
		$form->addElement(new FormElement_Submit(false, "submit_layout", array("value" => "{_'forms_layout_submit_edit'}", "classInput" => "btn btn-block btn-primary")));			
	}

	$Output = $form->output($return);

	if ($Output) {
		$Layout = new Layout();

		/* Project */

		if ($type === 'edit')
			$Layout->id = $id;

		$Layout->name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_STRING);
		if ($type !== 'edit')
			$Layout->user = User::getUserID();

		switch ($type) {
			case 'create':
				if ($Layout->createLayout()) {
					$Message = new Module();
					$Message->addModule(new Message(), array("html" => "{_'forms_layout_form_created', sprintf([\"{$Layout->name}\", \"/admin/layout/edit/{$Layout->getCurrentLayoutID()}\", \"#\"])}", "class" => "alert-success", "OutputStyle" => $return["OutputStyle"], "OutputType" => $return["OutputType"], "Header" => 200));
					$Message->output();	
				} else {
					$Message = new Module();
					$Message->addModule(new Message(), array("html" => "{_'forms_layout_form_error', sprintf([\"{$Layout->name}\", \"#\"])}", "class" => "alert-danger", "OutputStyle" => $return["OutputStyle"], "OutputType" => $return["OutputType"], "Header" => 200));
					$Message->output();	
				}
				break;
			case 'edit':
				if ($Layout->updateLayout()) {
					$Message = new Module();
					$Message->addModule(new Message(), array("html" => "{_'forms_layout_form_updated', sprintf([\"{$Layout->name}\", \"#\"])}", "class" => "alert-success", "OutputStyle" => $return["OutputStyle"], "OutputType" => $return["OutputType"], "Header" => 200));
					$Message->output();
				} else {
					$Message = new Module();
					$Message->addModule(new Message(), array("html" => "{_'forms_layout_form_update_error', sprintf([\"{$Layout->name}\", \"#\"])}", "class" => "alert-danger", "OutputStyle" => $return["OutputStyle"], "OutputType" => $return["OutputType"], "Header" => 200));
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
	if ($projectExist) { $msg = "{_'forms_layout_form_cant_modify'}"; } else { $msg = "{_'forms_layout_form_doesnt_exist'}"; }
	$Message = new Module();
	$Message->addModule(new Message(), array("html" => $msg, "class" => "alert-danger", "OutputStyle" => $return["OutputStyle"], "OutputType" => $return["OutputType"], "Header" => 200));
	$Message->output();			
}
?>