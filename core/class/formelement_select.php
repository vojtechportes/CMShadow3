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

		if (empty($this->getAttribute("disabled"))) {
			$disabled = false;
		} else {
			$disabled = $this->getAttribute("disabled");
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
			if ($value === $selected || in_array($value, $selected) && $selected !== false) {
				$str .= ' selected';
			}

			if ($value === $disabled || in_array($value, $disabled) && $disabled !== false) {
				$str .= ' disabled';
			}
			$str .= '>'.$text.'</option>';
		}

		$str .= '</select>';

		return $str;
	}
}

?>