<?php

Class FormElement_TextHidden Extends FormElement {
	public function output () {
		$str = '';

		$str = '<input class="'.$this->getAttribute("classInput").'" id="'.$this->getAttribute("id").'" name="'.$this->getAttribute("name").'" type="hidden" value="'.$this->getAttribute("value").'">';

		return $str;
	}
}

?>