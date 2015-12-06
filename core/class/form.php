<?php

/*
* Form class
* ==========
*
* public variables
* ----------------
* method = POST (default) || GET
* action = form action ('/' default)
* classInput
* classLabel
* templateOutput = DEFAULT_OUTPUT (default)
* template = /form/form (default)
* errorMessage = array
*
* public methods
* --------------
* addElement ((element) $object)
* output ()
*
* constructor
* -----------
* new Form((string) $formName)
*
* addElement objects
* ------------------
* FormElement_Checkbox ((string) $label, (string) $name, (array) $properties)
* FormElement_HTML (...)
* FormElement_Radio (...)
* FormElement_Select (...)
* FormElement_Submit (...)
* FormElement_Text (...)
* FormElement_TextHidden (...)
*
* addElement object properties
*
* $properties["html"] = (string) - only argument for FormElement_HTML
* $properties["value"] = (string) input value = argument for elements FormElement_Text
* $properties["placeholder"] - (string) = argument for elements FormElement_Text
* $properties["required"] = (bool) TRUE || FALSE - argument for all elements except FormElement_HTML
* $properties["options"] = (array) ["value" => "text" [,...]] - array of options for FormElement_Select
* $properties["selected"] = (string) value name - Selected option for FormElement_Select
* $properties["checked"] = (bool) TRUE || FALSE - FormElement_Checkbox or FormElement_Radio
* $properties["validation"] = validation configuration for all element except FormElement_HTML
* $properties["validation"]["equalTo"] = (string) name of element
* $properties["validation"]["minLength"] = (int)
* $properties["validation"]["maxLength"] = (int)
* $properties["validation"]["pattern"] = (string) regex pattern
*/

Class Form Extends Minimal {

	public $method = 'POST';
	public $action = '/';
	public $classInput = '';
	public $classLabel = '';
	public $type = 'linear';
	public $templateOutput = DEFAULT_OUTPUT;
	public $errorMessages = array("required" => DEFAULT_ERROR_FORM_VALIDATION_REQUIRED, "minLength" => DEFAULT_ERROR_FORM_VALIDATION_MAXLENGTH, "maxLength" => DEFAULT_ERROR_FORM_VALIDATION_MAXLENGTH, "pattern" => DEFAULT_ERROR_FORM_VALIDATION_PATTERN, "equalTo" => DEFAULT_ERROR_FORM_VALIDATION_EQUALTO);
	public $template = '/form/form';
	protected $defaultTemplate = '/form/form';	
	protected $elements = array();
	protected $attributes = array();
	protected $id;
	protected $name;
	protected $filterInput;

	public function __construct ($name = 'form') {
		$this->id = preg_replace("/\W/", "-", $name);

		if ($this->method == "POST") {		
			$this->filterInput = INPUT_POST;
		} else {
			$this->filterInput = INPUT_GET;			
		}

		$submitted = filter_input($this->filterInput, $this->id.'-form_submitted_element');	

		$this->name = $name;

		$this->submitted = (($submitted) ? true : false);	
	}

	public function configure (array $properties = null) {
		if (!empty($properties)) {

			foreach ($properties as $key => $val) {
				$this->setAttribute($key, $val);
			}		
		}
	}

	public function addElement (FormElement $element) {
		if (empty($element->getAttribute("id")))
			$element->setAttribute("id", $this->id.'-element-'.sizeof($this->elements));
		if (empty($element->getAttribute("classInput")))
			$element->setAttribute("classInput", $this->classInput);
		if (empty($element->getAttribute("classLabel")))
			$element->setAttribute("classLabel", $this->classLabel);

		$element->setAttribute("className", get_class($element));

		$this->elements[] = $element;
	}

	public function setAttribute ($attribute, $value) {
		$this->attributes[$attribute] = $value;
	}

	public function getAttribute ($attribute) {
		if (isset($this->attributes[$attribute])) {
			return $this->attributes[$attribute];
		}
	}

	public function isValid ($arguments) {
		global $M;

		$isValid["result"] = true;
		$isValid["equalTo"] = true;
		$isValid["maxLength"] = true;
		$isValid["minLength"] = true;
		$isValid["pattern"] = true;
		$isValid["required"] = true;

		if ($arguments["required"]) {
			if (is_array($arguments["value"])) {
				if (!empty($arguments["value"]) == 0) {
					$isValid["result"] = false;
					$isValid["required"] = false;
				}
			} else {
				if (strlen($arguments["value"]) == 0) {
					$isValid["result"] = false;
					$isValid["required"] = false;
				}				
			}
		}

		if ($arguments["validation"]) {
			foreach ($arguments["validation"] as $method => $value) {
				switch ($method) {
					case 'minLength':
						$isValid["minLength"] = true;
						if (strlen($arguments["value"]) < $value) {
							$isValid["result"] = false;
							$isValid["minLength"] = false;
						}
						break;
					case 'maxLength':
						$isValid["maxLength"] = true;
						if (strlen($arguments["value"]) > $value) {
							$isValid["result"] = false;
							$isValid["maxLength"] = false;
						}
						break;
					case 'equalTo':
						$isValid["equalTo"] = true;
						if ($arguments["value"] != filter_input($this->filterInput, $value, FILTER_SANITIZE_SPECIAL_CHARS)) {
							$isValid["result"] = false;
							$isValid["equalTo"] = false;
						}
						break;
					case 'pattern':
						$isValid["pattern"] = true;
						if (!preg_match($value, $arguments["value"])) {
							$isValid["result"] = false;
							$isValid["pattern"] = false;
						}
						break;
				}
			}
		}

		return $isValid;
	}

	public function validate () {
		global $M;
		$result = true;
		$errorMessages = array();
		$postData = array();

		foreach ($this->elements as $element) {
			$className = $element->getAttribute("className");
			if ($className != 'FormElement_HTML') {
				if ($className === 'FormElement_Select') {
						$value = filter_input($this->filterInput, str_replace('[]', '', $element->getAttribute("name")), FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
				} else {
						$value = filter_input($this->filterInput, $element->getAttribute("name"), FILTER_SANITIZE_SPECIAL_CHARS);
				}
				switch ($className) {
					case 'FormElement_Radio':
					case 'FormElement_Checkbox':
						$element->setAttribute("checked", false);
						if ($value === $element->getAttribute("value")) {
							$element->setAttribute("checked", true);
						}
						break;
					case 'FormElement_Select':
						$element->setAttribute("selected", $value);
						break;	
					case 'FormElement_Text':
					case 'FormElement_Textarea':
					case 'FormElement_HiddenText':
					case 'FormElement_Hidden':
						$element->setAttribute("value", $value);						
						break;
				}

				$formData[][$element->getAttribute("name")] = $value;

				$isValid = $this->isValid(array("value" => $value, "name" => $element->getAttribute("name"), "validation" => $element->getAttribute("validation"), "required" => $element->getAttribute("required") || false));
				
				if ($result !== false) {
					if ($isValid["result"]) {
						$result = true;
					} else {
						$result = false;	
					}				
				}

				if ($isValid["result"] === false)
					$element->setAttribute("hasError", true);

				if ($isValid["required"] === false)
					$errorMessages[] = $this->errorMessages["required"];

				if ($isValid["pattern"] === false)
					$errorMessages[] = sprintf($this->errorMessages["pattern"], $element->getAttribute("validation")["pattern"]);

				if ($isValid["minLength"] === false)
					$errorMessages[] = sprintf($this->errorMessages["minLength"], $element->getAttribute("validation")["minLength"]);

				if ($isValid["maxLength"] === false)
					$errorMessages[] = sprintf($this->errorMessages["minLength"], $element->getAttribute("validation")["maxLength"]);

				if ($isValid["equalTo"] === false)
					$errorMessages[] = sprintf($this->errorMessages["equalTo"], $element->getAttribute("validation")["equalTo"]);

				$element->setAttribute("errorMessages", $errorMessages);

			}
		}

		return array("result" => $result, "data" => $formData);
	}

	public function output ($return) {
		$view = array();
		$view['views'] = array();
		$view['object'] = $this;
		$validateName = $this->id.'-form_submitted_element';
		$this->elements[] = new FormElement_TextHidden(false, $validateName, array("id" => $validateName, "required" => true, "value" => 1, "className" => "FormElement_TextHidden"));		
		$showForm = true;

		if ($this->submitted) {
			$validation = $this->validate();
			if ($validation["result"]) {
				if ($this->method == "POST") {
					/*$ch = curl_init();

					curl_setopt($ch, CURLOPT_URL, $this->action);
					curl_setopt($ch, CURLOPT_POST, 1);
					curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($validation["data"]));
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

					$server_output = curl_exec ($ch);

					curl_close ($ch);

					var_dump(CURLOPT_RETURNTRANSFER);*/

					return true;
				} else if ($this->method == "GET") {
					//header("Location: ".$this->action."?".http_build_query($validation["data"]));	*/	

				}

				$showForm = false;	
			} else {

			}
		}		

		if ($showForm) {
			foreach ($this->elements as $element) {		
				$className = $element->getAttribute("className");
				if ($className == 'FormElement_HTML') {
					ob_start();
					echo $element->getAttribute("html");
				} else {
					if ($className == 'FormElement_TextHidden' || $className == 'FormElement_Hidden') {
						$output = array('input' => $element->output());
						$type = "hidden";
					} else {
						$output = array('input' => $element->output(), 'label' => $element->outputLabel(), 'object' => $element);	
						$type = $this->type;
					}
					ob_start();
					$template = DEFAULT_TEMPLATE_PATH.$this->templateOutput.$this->template.'.'.$type;
					if (!file_exists($template)) {
						$template = DEFAULT_TEMPLATE_PATH.$this->templateOutput.$this->defaultTemplate.'.'.$type;
					}
					parent::load($template, $output);
				}
				$view['views'][] = ob_get_contents(); ob_end_clean();
			}

			ob_start();
			parent::load(DEFAULT_TEMPLATE_PATH.$this->templateOutput.$this->template, $view + array('moduleReturn' => $return));
			$html = ob_get_contents(); ob_end_clean();
			echo $html;
		}
	}
}

?>