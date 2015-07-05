<?php
global $M;

$type = filter_input(INPUT_GET, "type", FILTER_SANITIZE_STRING);
if (!isset($type))
	$type = 'create';

$parent = array();
array_unshift($parent, "{_'forms_page_template_root'}");

$test = new Page();
$M->debug($test->getStatus());

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
$form->addElement(new FormElement_Text("{_'forms_page_weight'}", "weight", array("required" => false)));
$form->addElement(new FormElement_Select("{_'forms_page_template'}", "template", array("required" => true, "options" => $template)));
$form->addElement(new FormElement_Checkbox("{_'forms_page_visible'}", "visible", array("required" => false, "value" => "1", "classInput" => " ")));
$form->addElement(new FormElement_Submit(false, "submit", array("value" => "{_'forms_page_submit'}", "classInput" => "btn btn-block btn-primary")));

$Output = $form->output();

?>