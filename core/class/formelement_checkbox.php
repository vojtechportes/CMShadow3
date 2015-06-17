<?php

Class FormElement_Checkbox Extends FormElement {
	public function output () {
		$str = '';
		if (empty($this->getAttribute("checked"))) {
			$checked = false;
		} else { 
			$checked = $this->getAttribute("checked");
		}

		$str = '<input class="'.$this->getAttribute("classInput").'" id="'.$this->getAttribute("id").'" name="'.$this->getAttribute("name").'" type="checkbox" '.(($checked !== false) ? ' checked' : '').' value="'.$this->getAttribute("value").'">';

		return $str;
	}
}

?>