<?php

Class FormElement_Radio Extends FormElement {
	public function output () {
		$str = '';
		if (empty($this->getAttribute("checked"))) {
			$checked = false;
		} else { 
			$checked = $this->getAttribute("checked");
		}		

		$str = '<input class="'.$this->getAttribute("classInput").'" id="'.$this->getAttribute("id").'" name="'.$this->getAttribute("name").'" type="radio" '.(($checked !== false) ? ' checked' : '').' value="'.$this->getAttribute("value").'">';

		return $str;
	}
}

?>