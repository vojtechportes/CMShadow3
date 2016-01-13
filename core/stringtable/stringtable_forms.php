<?php

$Stringtable = array(
	/* Login Form */
	'forms_login_heading' => 'Login',
	'forms_login_user_name' => 'User name',	
	'forms_login_password' => 'Password',
	'forms_login_submit' => 'Login',
	'form_login_error_auth' => 'Wrong password or user name.',
	'form_login_error_auth_name' => 'Wrong password, <strong>%s</strong> attempts are remaining.<br>If you exceed remaining number of attempts, your account will be blocked.',
	'form_login_error_auth_blocked' => 'Sorry, your account was blocked.<br>To restore your account, please, contact system administrator.',
	
	/* Page Form */
	'forms_page_submit' => 'Save',
	'forms_page_name' => 'Name',
	'forms_page_title' => 'Title',
	'forms_page_visible' => 'Visible',
	'forms_page_description' => 'Description',
	'forms_page_keywords' => 'Keywords',
	'forms_page_weight' => 'Weight',
	'forms_page_parent' => 'Parent page',
	'forms_page_template' => 'Template',
	'forms_page_parent_root' => '--- Root ---',
	'forms_page_form_help' => 'Page title will be used for search engines. Page name will be used eg for facebook or twitter page name. Description will be used for both in same way.',
	'forms_page_form_created' => 'Page "%s" was successfully created. <a data-api-reload href="%s">Create another.</a>',
	'forms_page_form_error' => 'Unexpected error ocured. Page "%s" was not created. Please, <a data-api-reload href="%s">try it again later.</a>',		

	/* Project Form */
	'forms_project_submit' => 'Create',
	'forms_project_submit_edit' => 'Edit',
	'forms_project_name' => 'Name',
	'forms_project_release_date' => 'Release date',
	'forms_project_description' => 'Description',
	'forms_project_owners' => 'Owners',
	'forms_project_editors' => 'Editors',
	'forms_project_pages' => 'Pages',	
	'forms_project_form_help' => 'To edit page you will need to create project or to add page to existing one. Page will be versioned in project. You can also set roles in project.',
	'forms_project_form_created' => 'Project "%s" was succesfully created. To add more pages to project, <a href="%s">open project</a>. You can also <a data-api-reload href="%s">create another project.</a>',
	'forms_project_form_created_exception' => 'Project "%s" was succesfully created. To add more pages to project, <a href="%s">open project</a>. You can also <a data-api-reload href="%s">create another project.</a><br><br>Following pages were not added to project because they are already in different project or locked: "%s"',
	'forms_project_form_quick_created' => 'Project "%s" was succesfully created. To add more pages to project, <a href="%s">open project</a>. To close the window, <a data-dismiss="modal" href="%s">click here.</a>',
	'forms_project_form_quick_created_exception' => 'Project "%s" was succesfully created. To add more pages to project, <a href="%s">open project</a>. To close the window, <a data-dismiss="modal" href="%s">click here.</a><br><br>Following pages were not added to project because they are already in different project or locked: "%s"',
	'forms_project_form_error' => 'Unexpected error ocured. Project "%s" was not created. Please <a data-api-reload href="%s">try it again later.</a>',
	'forms_project_form_cant_modify' => 'Sorry, you can\'t modify this project. You don\'t have enough rights.',
	'forms_project_form_doesnt_exist' => 'Sorry, you can\'t modify this project. This project doesn\'t exist. You might have wrong project ID or project was deleted.',	
	'forms_project_form_updated' => 'Project "%s" was successfully updated. <a data-api-reload href="%s">Change more.</a>',
	'forms_project_form_updated_exception' => 'Project "%s" was successfully updated. <a data-api-reload href="%s">Change more.</a>.<br><br>Following pages were not added to project because they are already in different project or locked: "%s"',
	'forms_project_form_update_error' => 'Unexpected error ocured. Project "%s" was not updated. Please <a data-api-reload href="%s">try it again later.</a>',
	'forms_project_form_state_changed' => 'Project state was successfully changed.',
	'forms_project_form_state_change_failed' => 'Sorry, you are not allowed to change state of this project.',

	/* Layout Form */
	'forms_layout_submit' => 'Create',
	'forms_layout_submit_edit' => 'Edit',
	'forms_layout_name' => 'Name',

	/* Layout Slot Form */

	'forms_layout_slot_submit' => 'Add',
	'forms_layout_slot_submit_edit' => 'Edit',
	'forms_layout_slot_name' => 'Name',
	'forms_layout_form_help' => 'Layout is part of template and is saying where on page you can place modules and how the page will be structured. Part of layout are also slots, that are creating page structure. You will be able to add them after layout is created.',
	'forms_layout_form_error' => 'Unexpected error ocured. Layout "%s" was not created. Please <a data-api-reload href="%s">try it again later.</a>',
	'forms_layout_form_update_error' => 'Unexpected error ocured. Project "%s" was not updated. Please <a data-api-reload href="%s">try it again later.</a>',
	'forms_layout_form_cant_modify' => 'Sorry, you are not allowed to modify this layout.',
	'forms_layout_form_doesnt_exist' => 'Sorry, you can\'t modify this layout. This layout doesn\'t exist. You might have wrong ID or layout was deleted.',
	'forms_layout_form_created' => 'Layout "%s" was succesfully created. To add slots to layout, <a href="%s">open layout</a>. You can also <a data-api-reload href="%s">create another layout.</a>',
	'forms_layout_form_updated' => 'Layout "%s" was successfully updated. <a data-api-reload href="%s">Change more.</a>',
	'forms_layout_slot_parent_root' => '--- Root ---',
	'forms_layout_slot_parent' => 'Parent',
	'forms_layout_slot_weight' => 'Weight',
	'forms_layout_slot_class' => 'Class',
	'forms_layout_slot_identifier' => 'ID (CSS)',
	'forms_layout_slot_data' => 'Data attributes',

	'forms_layout_slot_form_help' => 'Slot is part of layout and is making page structure. You can nest the slots, add classes, data attributes or ids.',
	'forms_layout_slot_form_success' => 'Slot "%s" was succesfully added. You can <a data-dismiss="modal" href="%s">close this window</a> or <a data-api-reload href="%s">add another one.</a>',
	'forms_layout_slot_form_error' => 'Unexpected error ocured. Slot "%s" was not created. Please <a data-api-reload href="%s">try it again later.</a>',
	'forms_layout_slot_form_update_success' => 'Slot "%s" was succesfully updated. You can <a data-dismiss="modal" href="%s">close this window</a> or <a data-api-reload href="%s">you can change more.</a>',	
	'forms_layout_slot_form_update_error' => 'Unexpected error occured. Slot "%s" was not updated. Please <a data-api-reload href="%s">try it again later.</a>',
	'forms_layout_slot_form_update_exception' => 'Unexpected error occured. Slot "%s" was updated, but some of nested slots was not. Please <a data-api-reload href="%s">try it again later.</a>',	
)

?>