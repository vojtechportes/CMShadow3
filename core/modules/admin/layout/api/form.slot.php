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

		$Slot = new Layout();
		$Slot = $Slot->getLayoutSlotByID((int) $return['slotId']);
	} else if ($return['type'] === 'create') {
		$id = $M->extractID($return['Path']);	
	}
} else { 
	$id = $M->extractID($return['Path']);	
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

	$Layout = new Layout();
	$Layout->getLayoutSlotsTree(0, $id);
	$Tree = $Layout->slotsTree;
	$slots = array("0" => "{_'forms_layout_slot_parent_root'}");

	foreach ($Tree as $slot) {
		$slots[$slot['ID']] = str_repeat('. ', $slot['Depth']).$slot['Name']; 
	}

	$form = new Form ('form_layout');
	$form->method = 'POST';
	$form->type = 'rows';
	$form->template = '/admin/layout/form.slot';
	$form->classInput = 'form-control';

	if ($type === 'create') {
		$form->addElement(new FormElement_Text("{_'forms_layout_slot_name'}", "name", array("required" => true)));
		$form->addElement(new FormElement_Select("{_'forms_layout_slot_parent'}", "parent", array("required" => true, "options" => $slots)));
		$form->addElement(new FormElement_Text("{_'forms_layout_slot_weight'}", "weight", array("required" => false)));
		$form->addElement(new FormElement_Text("{_'forms_layout_slot_class'}", "class", array("required" => false)));
		$form->addElement(new FormElement_Text("{_'forms_layout_slot_identifier'}", "identifier", array("required" => false)));
		$form->addElement(new FormElement_Text("{_'forms_layout_slot_data'}", "data", array("required" => false)));

		$form->addElement(new FormElement_Submit(false, "submit_project", array("value" => "{_'forms_layout_slot_submit'}", "classInput" => "btn btn-block btn-primary")));
	} else {
		$form->addElement(new FormElement_Text("{_'forms_layout_slot_name'}", "name", array("required" => true, "value" => $Slot['Name'])));
		$form->addElement(new FormElement_Select("{_'forms_layout_slot_parent'}", "parent", array("required" => true, "options" => $slots, "selected" => array($Slot['Parent']), "disabled" => array($Slot['ID']))));
		$form->addElement(new FormElement_Text("{_'forms_layout_slot_weight'}", "weight", array("required" => false, "value" => $Slot['Weight'])));
		$form->addElement(new FormElement_Text("{_'forms_layout_slot_class'}", "class", array("required" => false, "value" => $Slot['Class'])));
		$form->addElement(new FormElement_Text("{_'forms_layout_slot_identifier'}", "identifier", array("required" => false, "value" => $Slot['Identifier'])));
		$form->addElement(new FormElement_Text("{_'forms_layout_slot_data'}", "data", array("required" => false, "value" => $Slot['Data'])));

		$form->addElement(new FormElement_Submit(false, "submit_project", array("value" => "{_'forms_layout_slot_submit_edit'}", "classInput" => "btn btn-block btn-primary")));
	}

	$Output = $form->output($return);

	if ($Output) {
		$Layout = new Layout();

		/* Layout Slot */

		if ($type === 'edit') {
			$Layout->id = $Slot['ID'];
		}
			
		$Layout->layout = $id;
		$Layout->parent = filter_input(INPUT_POST, "parent", FILTER_SANITIZE_NUMBER_INT);
		$Layout->weight = filter_input(INPUT_POST, "weight", FILTER_SANITIZE_NUMBER_INT);
		$Layout->slotName = filter_input(INPUT_POST, "name", FILTER_SANITIZE_STRING);
		$Layout->class = filter_input(INPUT_POST, "class", FILTER_SANITIZE_STRING);
		$Layout->identifier = filter_input(INPUT_POST, "identifier", FILTER_SANITIZE_STRING);
		$Layout->data = filter_input(INPUT_POST, "data", FILTER_SANITIZE_STRING);

		$Parent = '';
		if ($Layout->parent !== 0) {
			$Parent = $Layout->getLayoutSlotByID($Layout->parent);
			$ParentPath = $Parent['Path'];
		}

		$Layout->path = $ParentPath.get_seo_name($Layout->slotName).'--';

		if ($type === 'edit') {
			$changeChildPath = false;
			if ($Layout->path === $Slot['Path']) {
				$Layout->path = $Slot['Path'];
			} else {
				if ((int) $Slot['numChildSlots'] > 0) {
					$changeChildPath = true;
				}
			}
		}

		switch ($type) {
			case 'create':
				if ($Layout->createLayoutSlot()) {
					$Message = new Module();
					$Message->addModule(new Message(), array("html" => "{_'forms_layout_slot_form_success', sprintf([\"{$Layout->slotName}\", \"#\", \"#\"])}", "class" => "alert-success", "OutputStyle" => $return["OutputStyle"], "OutputType" => $return["OutputType"], "Header" => 200));
					$Message->output();	
				} else {
					$Message = new Module();
					$Message->addModule(new Message(), array("html" => "{_'forms_layout_slot_form_error', sprintf([\"{$Layout->slotName}\", \"#\"])}", "class" => "alert-danger", "OutputStyle" => $return["OutputStyle"], "OutputType" => $return["OutputType"], "Header" => 200));
					$Message->output();	
				}
				break;
			case 'edit':
				
				if ($Layout->updateLayoutSlot()) {
					if ($changeChildPath) {
						if($Layout->updateLayoutSlotChildPaths()) {
							$Message = new Module();
							$Message->addModule(new Message(), array("html" => "{_'forms_layout_slot_form_update_success', sprintf([\"{$Layout->slotName}\", \"#\", \"#\"])}", "class" => "alert-success", "OutputStyle" => $return["OutputStyle"], "OutputType" => $return["OutputType"], "Header" => 200));
							$Message->output();				
						} else {
							$Message = new Module();
							$Message->addModule(new Message(), array("html" => "{_'forms_layout_slot_form_update_exception', sprintf([\"{$Project->name}\", \"#\"])}", "class" => "alert-danger", "OutputStyle" => $return["OutputStyle"], "OutputType" => $return["OutputType"], "Header" => 200));
							$Message->output();		
						}
					} else {
						$Message = new Module();
						$Message->addModule(new Message(), array("html" => "{_'forms_layout_slot_form_update_success', sprintf([\"{$Layout->slotName}\", \"#\", \"#\"])}", "class" => "alert-success", "OutputStyle" => $return["OutputStyle"], "OutputType" => $return["OutputType"], "Header" => 200));
						$Message->output();	
					}
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