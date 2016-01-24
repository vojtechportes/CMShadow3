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
			$Template = new Layout();
			$Template = $Template->getTemplateByID($id);

			if (!$Template)
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
		$Message->addModule(new Message(), array("html" => "{_'forms_template_form_help'}", "class" => "alert-info", "OutputStyle" => $return["OutputStyle"], "OutputType" => $return["OutputType"], "Header" => 200));
		$Message->output();
	}

	$_Layouts = array();
	$Layout = new Layout();
	$Layouts = $Layout->getAllLayouts();

	foreach ($Layouts as $layout) {
		$_Layouts[$layout['ID']] = $layout['Name'];
	};

	if (count($_Layouts) === 0) {
		$Message = new Module();
		$Message->addModule(new Message(), array("html" => "{_'forms_template_form_no_layout'}", "class" => "alert-danger", "OutputStyle" => $return["OutputStyle"], "OutputType" => $return["OutputType"], "Header" => 200));
		$Message->output();		
	} else {
		$OutputStyle = new FileSystem(true, false);
		$OutputStyle->path = DEFAULT_TEMPLATE_PATH.PRODUCTION_OUTPUT;
		$OutputStyle->pathStrip = true;
		$OutputStyle->skipRoot = true;
		$OutputStyle->maxDepth = 1;

		$_OutputStyle = $OutputStyle->output();
		$OutputStyle = array();

		foreach ($_OutputStyle as $folder) {
			$OutputStyle[$folder] = $folder;
		}

		$OutputType = new FileSystem(false, true);
		$OutputType->path = DEFAULT_TEMPLATE_PATH.PRODUCTION_OUTPUT;
		$OutputType->pathStrip = true;
		$OutputType->extensionStrip = true;
		$OutputType->skipRoot = true;
		$OutputType->maxDepth = 1;
		$OutputType->fileMask = '.tpl';

		$_OutputType = $OutputType->output();
		$OutputType = array();

		foreach ($_OutputType as $file) {
			$OutputType[$file] = $file;
		}

		$Styles = new FileSystem(true, false);
		$Styles->path = DEFAULT_ASSETS_PATH.PRODUCTION_OUTPUT;
		$Styles->pathStrip = true;
		$Styles->skipRoot = true;
		$Styles->maxDepth = 1;

		$_Styles  = $Styles->output();
		$Styles  = array();

		foreach ($_Styles  as $folder) {
			$Styles[$folder] = $folder;
		}

		$Pages = new TemplatePages();
		$_Pages = $Pages->getAllTemplatePages();
		$Pages = array();

		foreach ($_Pages as $page) {
			$Pages[$page['ID']] = $page['Name'];
		}

		$form = new Form ('form_template');
		$form->method = 'POST';
		$form->type = 'rows';
		$form->template = '/admin/project/form';
		$form->classInput = 'form-control';

		if ($type === 'create') {
			$form->addElement(new FormElement_Text("{_'forms_template_name'}", "name", array("required" => true)));
			$form->addElement(new FormElement_Select("{_'forms_template_layout'}", "layout", array("required" => true, "options" => $_Layouts)));
			$form->addElement(new FormElement_Select("{_'forms_template_output_style'}", "output_style", array("required" => true, "options" => $OutputStyle)));
			$form->addElement(new FormElement_Select("{_'forms_template_output_type'}", "output_type", array("required" => true, "options" => $OutputType)));
			$form->addElement(new FormElement_Select("{_'forms_template_style'}", "style", array("required" => true, "options" => $Styles)));
			$form->addElement(new FormElement_Select("{_'forms_template_template_pages'}", "template_pages", array("required" => true, "options" => $Pages, "multiple" => true)));
			$form->addElement(new FormElement_Submit(false, "submit_template", array("value" => "{_'forms_template_submit'}", "classInput" => "btn btn-block btn-primary")));
		} else {
			/**/
			$form->addElement(new FormElement_Submit(false, "submit_template", array("value" => "{_'forms_template_submit_edit'}", "classInput" => "btn btn-block btn-primary")));			
		}

		$Output = $form->output($return);

		if ($Output) {
			$Template = new Template();

			/* Project */

			if ($type === 'edit')
				$Template->id = $id;

			$Template->name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_STRING);
			$Template->layout = filter_input(INPUT_POST, "layout", FILTER_SANITIZE_NUMBER_INT);
			$Template->outputStyle = filter_input(INPUT_POST, "output_style", FILTER_SANITIZE_STRING);
			$Template->outputType = filter_input(INPUT_POST, "output_type", FILTER_SANITIZE_STRING);
			$Template->style = filter_input(INPUT_POST, "style", FILTER_SANITIZE_STRING);
			$Template->pages = filter_input(INPUT_POST, "template_pages", FILTER_SANITIZE_STRING);

			if ($type !== 'edit')
				$Template->user = User::getUserID();

			switch ($type) {
				case 'create':
					if ($Template->createTemplate() && $Template->setTemplatePages(true)) {
						$Message = new Module();
						$Message->addModule(new Message(), array("html" => "{_'forms_template_form_created', sprintf([\"{$Template->name}\", \"/admin/templates/edit/{$Template->getCurrentTemplateID()}\", \"#\"])}", "class" => "alert-success", "OutputStyle" => $return["OutputStyle"], "OutputType" => $return["OutputType"], "Header" => 200));
						$Message->output();	
					} else {
						$Message = new Module();
						$Message->addModule(new Message(), array("html" => "{_'forms_template_form_error', sprintf([\"{$Template->name}\", \"#\"])}", "class" => "alert-danger", "OutputStyle" => $return["OutputStyle"], "OutputType" => $return["OutputType"], "Header" => 200));
						$Message->output();	
					}
					break;
				case 'edit':
					if ($Layout->updateLayout()) {
						$Message = new Module();
						$Message->addModule(new Message(), array("html" => "{_'forms_template_form_updated', sprintf([\"{$Template->name}\", \"#\"])}", "class" => "alert-success", "OutputStyle" => $return["OutputStyle"], "OutputType" => $return["OutputType"], "Header" => 200));
						$Message->output();
					} else {
						$Message = new Module();
						$Message->addModule(new Message(), array("html" => "{_'forms_template_form_update_error', sprintf([\"{$Template->name}\", \"#\"])}", "class" => "alert-danger", "OutputStyle" => $return["OutputStyle"], "OutputType" => $return["OutputType"], "Header" => 200));
						$Message->output();	
					}
					break;
			}
		}
	}

	$Code = new Module();
	$Code->template = '/code';
	$Code->addModule(false, array("html" => '<div class="clearfix"></div></div>'));
	$Code->output();
} else {
	if ($projectExist) { $msg = "{_'forms_template_form_cant_modify'}"; } else { $msg = "{_'forms_template_form_doesnt_exist'}"; }
	$Message = new Module();
	$Message->addModule(new Message(), array("html" => $msg, "class" => "alert-danger", "OutputStyle" => $return["OutputStyle"], "OutputType" => $return["OutputType"], "Header" => 200));
	$Message->output();			
}
?>