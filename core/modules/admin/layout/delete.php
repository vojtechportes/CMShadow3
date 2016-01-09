<?php

$Code = new Module();
$Code->template = '/code';
$Code->addModule(false, array("html" => '<div class="modal-body">'));
$Code->output();

if (!array_key_exists('deleteConfirm', $return)) {

	$Message = new Module();
	$Message->addModule(new Message(), array("html" => "{_'layouts_layout_delete_message_question', sprintf([\"{$return['layoutName']}\"])}", "class" => "alert-info", "OutputStyle" => $return["OutputStyle"], "OutputType" => $return["OutputType"], "Header" => 200));
	$Message->output();

	$Code = new Module();
	$Code->template = '/code';
	$Code->addModule(false, array("html" => '</div><div class="modal-footer"><button data-delete-confirm class="btn btn-primary">{_\'default_yes\'}</button> <button class="btn btn-secondary" data-dismiss="modal">{_\'default_no\'}</button></div>'));
	$Code->output();

} else {

	$Layout = new Layout();
	$Layout->id = (int) $return["layoutId"];
	if ($Layout->deleteLayout()) {

		$Message = new Module();
		$Message->addModule(new Message(), array("html" => "{_'layouts_layout_delete_message_success', sprintf([\"{$return['layoutName']}\"])}", "class" => "alert-info", "OutputStyle" => $return["OutputStyle"], "OutputType" => $return["OutputType"], "Header" => 200));
		$Message->output();

	} else {

		$Message = new Module();
		$Message->addModule(new Message(), array("html" => "{_'layouts_layout_delete_message_error', sprintf([\"{$return['layoutName']}\"])}", "class" => "alert-info", "OutputStyle" => $return["OutputStyle"], "OutputType" => $return["OutputType"], "Header" => 200));
		$Message->output();

	}

	$Code = new Module();
	$Code->template = '/code';
	$Code->addModule(false, array("html" => '</div><div class="modal-footer"><button class="btn btn-primary" data-dismiss="modal">{_\'default_modal_close\'}</button></div>'));
	$Code->output();

}

?>