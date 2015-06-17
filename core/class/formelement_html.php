<?php

Class FormElement_HTML Extends FormElement {
	public function output () {
		$str = $this->getAttribute("html");

		return $str;
	}
}

?>