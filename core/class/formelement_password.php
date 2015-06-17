<?php

Class FormElement_Password Extends FormElement {
	public function output () {
		$str = '';

		$str = '<input class="'.$this->getAttribute("classInput").'" id="'.$this->getAttribute("id").'" name="'.$this->getAttribute("name").'" type="password"';
		if ((bool) $this->getAttribute("required"))
			$str .= ' required';
		if ($this->getAttribute("placeholder"))
			 $str .= ((bool) $this->getAttribute("placeholder") ? ' placeholder="'.$this->getAttribute("placeholder").'"' : '');
		$str .= ' value="'.$this->getAttribute("value").'"';
		$str .= '>';

		return $str;
	}
}

?>