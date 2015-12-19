<?php
global $M, $Path;

if ((int) $M->_POST('form_open') === 1) {
	$return = concat_property('class', ' in', $return);
}

$Code = new Module();
$Code->template = '/code';
$Code->addModule(false, array("html" => '<div class="form-wrapper '.print_property('class', $return, false, true).'"><div class="clearfix"></div>'));
$Code->output();

$Message = new Module();
$Message->addModule(new Message(), array("html" => "{_'forms_page_form_help'}", "class" => "alert-info", "OutputStyle" => $return["OutputStyle"], "OutputType" => $return["OutputType"], "Header" => 200));
$Message->output();

$type = filter_input(INPUT_GET, "type", FILTER_SANITIZE_STRING);
if (!isset($type))
	$type = 'create';

$Pages = new Page();
$Pages->getPageTree();
$Pages = $Pages->pageTree;

$_Pages = array();
if (!empty($Pages)) {
	foreach ($Pages as $page) {
		$_Pages[$page['ID']] = str_repeat('. ', $page['Depth']).$page['Title'];
	}
}

$parent = array('0' => "{_'forms_page_parent_root'}") + $_Pages;

$template = array();

$form = new Form ('form_page');
$form->method = 'POST';
$form->type = 'rows';
$form->template = '/admin/page/form';
$form->classInput = 'form-control';

$form->addElement(new FormElement_Select("{_'forms_page_parent'}", "form_page-parent", array("required" => true, "options" => $parent)));
$form->addElement(new FormElement_Text("{_'forms_page_name'}", "form_page-name", array("required" => true)));
$form->addElement(new FormElement_Text("{_'forms_page_title'}", "form_page-title", array("required" => true)));
$form->addElement(new FormElement_Text("{_'forms_page_keywords'}", "form_page-keywords", array("required" => false)));
$form->addElement(new FormElement_Textarea("{_'forms_page_description'}", "form_page-description", array("required" => false)));
$form->addElement(new FormElement_Text("{_'forms_page_weight'}", "form_page-weight", array("required" => false, "value" => "50")));
$form->addElement(new FormElement_Select("{_'forms_page_template'}", "form_page-template", array("required" => false, "options" => $template)));
$form->addElement(new FormElement_Checkbox("{_'forms_page_visible'}", "form_page-visible", array("required" => false, "value" => "1", "classInput" => " ")));
$form->addElement(new FormElement_Submit(false, "submit_page", array("value" => "{_'forms_page_submit'}", "classInput" => "btn btn-block btn-primary")));

$Output = $form->output($return);

if ($Output) {		
	$Page = new Page();

	/* Page */
	$Page->parent = filter_input(INPUT_POST, "form_page-parent");
	$Page->owner = User::getUserID();
	$Page->visible = filter_input(INPUT_POST, "form_page-visible");
	$Page->weight = filter_input(INPUT_POST, "form_page-weight");

	/* PageDetails */
	$Page->name = filter_input(INPUT_POST, "form_page-name");	
	$Page->title = filter_input(INPUT_POST, "form_page-title");	
	$Page->description = filter_input(INPUT_POST, "form_page-description");	
	$Page->keywords = filter_input(INPUT_POST, "form_page-keywords");	
	$Page->template = filter_input(INPUT_POST, "form_page-template");
	
	if ($Page->createPage() && $Page->createPageDetails() && $Page->clonePage(false, -1)) {
		$Message = new Module();
		$Message->addModule(new Message(), array("html" => "{_'forms_page_form_created', sprintf([\"{$Page->name}\", \"#\"])}", "class" => "alert-success", "OutputStyle" => $return["OutputStyle"], "OutputType" => $return["OutputType"], "Header" => 200));
		$Message->output();
	} else {
		$Message = new Module();
		$Message->addModule(new Message(), array("html" => "{_'forms_page_form_error', sprintf([\"{$Page->name}\", \"#\"])}", "class" => "alert-danger", "OutputStyle" => $return["OutputStyle"], "OutputType" => $return["OutputType"], "Header" => 200));
		$Message->output();	
	}

}

$Code = new Module();
$Code->template = '/code';
$Code->addModule(false, array("html" => '<div class="clearfix"></div></div>'));
$Code->output();
?>