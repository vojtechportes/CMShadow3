<?php

Class FormElement Extends Form {
	
	public function __construct ($label, $name, array $properties = null) {
		$config = array(
			'label' => $label,
			'name'	=> $name
		);

		if (is_array($properties)) {
			$config = array_merge($config, $properties);
		}

		$this->configure($config);
	}

	public function outputLabel () {
		if (empty($this->getAttribute("showLabel"))) {
			$str = '';

			$str = '<label for="'.$this->getAttribute("id").'" class="'.trim($this->getAttribute("classLabel")).'">'.$this->getAttribute("label").'</label>';
			
			return $str;
		}
	}
}

?>