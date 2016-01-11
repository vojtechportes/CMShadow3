<?php

$Stringtable = array(
	/* Layout List */
    'layouts_layout_list_th_id' => 'ID',     
    'layouts_layout_list_th_name' => 'Name',
    'layouts_layout_list_th_createdAt' => 'Created At',
    'layouts_layout_list_th_modifiedAt' => 'Modified At',
    'layouts_layout_list_th_actions' => 'Actions',
    'layouts_controls_create_layout' => 'Create Layout',
    'layouts_controls_create_slot' => 'Create Slot',    

    /**/

    'layouts_layout_delete' => 'Delete layout',
    'layouts_layout_delete_message_question' => '<p>Are you sure you want to delete layout "%s"? If layout is used in template, it will be removed also from tempalte and you will need to assign new one.</p>',
    'layouts_layout_delete_message_success' => '<p>Layout "%s" was successfully deleted.</p>',
    'layouts_layout_delete_message_error' => '<p>Unexpected error occured. Layout "%s" was not deleted. Please <a data-api-reload href="%s">try it again later</a></p>',

    /**/

    'layouts_layout_delete_slot' => 'Delete slot',
    'layouts_layout_delete_slot_message_question' => '<p>Are you sure you want to delete slot "%s"? If this slot contains any nested slots, those will be deleted as well.</p>',
    'layouts_layout_delete_slot_message_success' => '<p>Slot "%s" was successfully deleted.</p>',
    'layouts_layout_delete_slot_message_error' => '<p>Unexpected error occured. Slot "%s" was not deleted. Please <a data-api-reload href="%s">try it again later</a></p>'

);

?>