<?php

$Code = new Module();
$Code->template = '/code';
$Code->addModule(false, array("html" => '<div class="modal-body">'));
$Code->output();

if (!array_key_exists('deleteConfirm', $return)) {

	$Message = new Module();
	$Message->addModule(new Message(), array("html" => "{_'layouts_layout_delete_slot_message_question', sprintf([\"{$return['slotName']}\"])}", "class" => "alert-info", "OutputStyle" => $return["OutputStyle"], "OutputType" => $return["OutputType"], "Header" => 200));
	$Message->output();

	$Code = new Module();
	$Code->template = '/code';
	$Code->addModule(false, array("html" => '</div><div class="modal-footer"><button data-delete-confirm class="btn btn-primary">{_\'default_yes\'}</button> <button class="btn btn-secondary" data-dismiss="modal">{_\'default_no\'}</button></div>'));
	$Code->output();

} else {

	$Layout = new Layout();
	if ($Slots = $Layout->deleteLayoutSlot((int) $return["slotId"])) {
		$_slots = array();
		foreach ($Slots as $slot) {
			$_slots[] = $slot['Name'];
		}

		$_slots = implode(', ', $_slots);

		$Message = new Module();
		$Message->addModule(new Message(), array("html" => "{_'layouts_layout_delete_slot_message_success', sprintf([\"{$_slots}\"])}", "class" => "alert-info", "OutputStyle" => $return["OutputStyle"], "OutputType" => $return["OutputType"], "Header" => 200));
		$Message->output();

	} else {

		$Message = new Module();
		$Message->addModule(new Message(), array("html" => "{_'layouts_layout_delete_slot_message_error', sprintf([\"{$return['slotName']}\"])}", "class" => "alert-info", "OutputStyle" => $return["OutputStyle"], "OutputType" => $return["OutputType"], "Header" => 200));
		$Message->output();

	}

	$Code = new Module();
	$Code->template = '/code';
	$Code->addModule(false, array("html" => '</div><div class="modal-footer"><button class="btn btn-primary" data-dismiss="modal">{_\'default_modal_close\'}</button></div>'));
	$Code->output();

}

?>