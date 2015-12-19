<?php

Class FormElement_Textarea Extends FormElement {
	public function output () {
		$str = '';

		$str = '<textarea class="'.$this->getAttribute("classInput").'" style="'.$this->getAttribute("styleInput").'" id="'.$this->getAttribute("id").'" name="'.$this->getAttribute("name").'"';
		if ((bool) $this->getAttribute("required"))
			$str .= ' required';
		if ($this->getAttribute("placeholder"))
			 $str .= ((bool) $this->getAttribute("placeholder") ? ' placeholder="'.$this->getAttribute("placeholder").'"' : '');
		$str .= '>'.$this->getAttribute("value").'</textarea>';

		return $str;
	}
}

?>