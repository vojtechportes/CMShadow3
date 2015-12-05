<?php

Class FormElement_Select Extends FormElement {
	public function output () {
		$str = '';
		$options = $this->getAttribute("options");
		if (empty($this->getAttribute("selected"))) {
			$selected = false;
		} else {
			$selected = $this->getAttribute("selected");
		}

		if ($this->getAttribute("multiple")) {
			$multiple = "multiple";
		} else {
			$multiple = "";
		}

		$str = '<select class="'.$this->getAttribute("classInput").'" '.$multiple.' id="'.$this->getAttribute("id").'" name="'.$this->getAttribute("name").'"';
		if ((bool) $this->getAttribute("required"))
			$str .= ' required';
		$str .= '>';

		foreach ($options as $value => $text) {
			$str .= '<option value="'.$value.'"';
			if ($value == $selected && $selected !== false) {
				$str .= ' selected';
			}
			$str .= '>'.$text.'</option>';
		}

		$str .= '</select>';

		return $str;
	}
}

?>