<?php
global $Path, $M;

if (filter_input(INPUT_GET, "error")) {
	$error = filter_input(INPUT_GET, "error");
	
	$User = new User();
	$User->userName = filter_input(INPUT_GET, "user");
	$Attempts = $User->getUserAttempts();

	switch ($error) {
		case "auth":
				$Message = new Module();
				$Message->addModule(new Message(), array("html" => "{_'form_login_error_auth'}", "class" => "alert-danger"));
				$Message->output();			
			break;
		case "authName":
				$Message = new Module();
				$Message->addModule(new Message(), array("html" => "{_'form_login_error_auth_name', sprintf($Attempts)}", "class" => "alert-danger"));
				$Message->output();	
			break;
		case "blocked":
				$Message = new Module();
				$Message->addModule(new Message(), array("html" => "{_'form_login_error_auth_blocked'}", "class" => "alert-danger"));
				$Message->output();		
			break;		
	}
}

$form = new Form ();
$form->method = 'POST';
$form->type = 'linear';
$form->template = '/auth/form';
$form->action = BASE_PATH.'preview/';
$form->classInput = 'form-control';

$form->addElement(new FormElement_Text("{_'forms_login_user_name'}", "username", array("required" => true)));
$form->addElement(new FormElement_Password("{_'forms_login_password'}", "password", array("required" => true)));
$form->addElement(new FormElement_Submit(false, "{_'forms_login_submit'}", array("value" => "Submit", "classInput" => "btn btn-block btn-primary")));

$Output = $form->output();

if ($Output) {
	$User = new User();
	$User->userName = filter_input(INPUT_POST, "username");
	$User->password = filter_input(INPUT_POST, "password");
	$Result = $User->setUserSession();

	if ($Result === true) {
			redirect(ADMIN_PATH);	
	} else {
		if ($Result['attempts'] > 0) {
			if ($Result['name'] === false && $Result['password'] === false) {		
				redirect($Path, "?error=auth");
			} else if ($Result['name'] || $Result['password'] === false) {
				$User->setUserAttempts($Result['attempts'] - 1);
				if ($Result['attempts'] - 1 > 0) {
					redirect($Path, "?error=authName&user=".$Result['name']);
				} else {
					redirect($Path, "?error=blocked");
				} 
			}
		} else {
			redirect($Path, "?error=blocked");			
		}
	}
}

?>