<?php
global $M, $Path;

if ($M->_GET("success")) {
	$Message = new Module();
	$Message->addModule(new Message(), array("html" => "{_'forms_page_form_created', sprintf([\"{$M->_GET("name")}\", \"{$M->_GET("path")}\"])}", "class" => "alert-success", "OutputStyle" => $return["OutputStyle"], "OutputType" => $return["OutputType"], "Header" => 200));
	$Message->output();
} else if ($M->_GET("error")) {
	$Message = new Module();
	$Message->addModule(new Message(), array("html" => "{_'forms_page_form_error', sprintf([\"{$M->_GET("name")}\", \"{$M->_GET("path")}\"])}", "class" => "alert-error", "OutputStyle" => $return["OutputStyle"], "OutputType" => $return["OutputType"], "Header" => 200));
	$Message->output();		
} else {
	$Message = new Module();
	$Message->addModule(new Message(), array("html" => "{_'forms_page_form_help'}", "class" => "alert-info", "OutputStyle" => $return["OutputStyle"], "OutputType" => $return["OutputType"], "Header" => 200));
	$Message->output();

	$type = filter_input(INPUT_GET, "type", FILTER_SANITIZE_STRING);
	if (!isset($type))
		$type = 'create';

	$parent = array('0' => "{_'forms_page_parent_root'}") + array();

	$template = array();

	$form = new Form ();
	$form->method = 'POST';
	$form->type = 'rows';
	$form->template = '/admin/page/form';
	$form->classInput = 'form-control';

	$form->addElement(new FormElement_Select("{_'forms_page_parent'}", "parent", array("required" => true, "options" => $parent)));
	$form->addElement(new FormElement_Text("{_'forms_page_name'}", "name", array("required" => true)));
	$form->addElement(new FormElement_Text("{_'forms_page_title'}", "title", array("required" => true)));
	$form->addElement(new FormElement_Text("{_'forms_page_keywords'}", "keywords", array("required" => false)));
	$form->addElement(new FormElement_Textarea("{_'forms_page_description'}", "description", array("required" => false)));
	$form->addElement(new FormElement_Text("{_'forms_page_weight'}", "weight", array("required" => false, "value" => "50")));
	$form->addElement(new FormElement_Select("{_'forms_page_template'}", "template", array("required" => false, "options" => $template)));
	$form->addElement(new FormElement_Checkbox("{_'forms_page_visible'}", "visible", array("required" => false, "value" => "1", "classInput" => " ")));
	$form->addElement(new FormElement_Submit(false, "submit", array("value" => "{_'forms_page_submit'}", "classInput" => "btn btn-block btn-primary")));

	$Output = $form->output();

	if ($Output) {
		$Page = new Page();

		/* Page */
		$Page->parent = filter_input(INPUT_POST, "parent");
		$Page->owner = User::getUserID();
		$Page->visible = filter_input(INPUT_POST, "visible");
		$Page->weight = filter_input(INPUT_POST, "weight");

		/* PageDetails */
		$Page->name = filter_input(INPUT_POST, "name");	
		$Page->title = filter_input(INPUT_POST, "title");	
		$Page->description = filter_input(INPUT_POST, "description");	
		$Page->keywords = filter_input(INPUT_POST, "keywords");	
		$Page->template = filter_input(INPUT_POST, "template");
		
		if ($Page->createPage() && $Page->createPageDetails()) {
			redirect("{$Path}?success&name={$Page->name}&path={$Path}");
		} else {
			redirect("{$Path}?error&name={$Page->name}&path={$Path}");
		}
	}
}
?>