<?php

Class Page Extends Minimal {

	public function getStatus () {
		return parent::getLastID('Pages');
	}

}

?>