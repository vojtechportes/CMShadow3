<?php

Class FormElement_Hidden Extends FormElement {
	public function output () {
		$str = '';

		$str = '<input class="'.$this->getAttribute("classInput").'" id="'.$this->getAttribute("id").'" name="'.$this->getAttribute("name").'" type="hidden"';
		if ((bool) $this->getAttribute("required"))
			$str .= ' required';
		$str .= ' value="'.$this->getAttribute("value").'"';
		$str .= '>';

		return $str;
	}
}

?>