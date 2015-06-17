<?php

Class FormElement_Submit Extends FormElement {
	public function output () {
		$str = '';

		$str = '<input class="'.$this->getAttribute("classInput").'" id="'.$this->getAttribute("id").'" name="'.$this->getAttribute("name").'" type="submit" value="'.$this->getAttribute("value").'">';

		return $str;
	}
}

?>