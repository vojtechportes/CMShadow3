<?php

Class Gadgets Extends Module {

	public $templateOutput = DEFAULT_OUTPUT;
	public $template = '';	
	protected $module;
	protected $output = array();

	public function __construct ($action) {
		switch ($action) {
			case 'getGadgets':
				$this->template = '/admin/gadget/show';
				$this->output = $this->getGadgets() + $this->getAvailableGadgets();
				break;

			case 'getAvailableGadgets':
				$this->template = '/admin/gadget/showavailable';
				$this->output = $this->getAvailableGadgets();
				break;
		}
	}

	private function getGadgets($count = false) {
		global $DB;
		$output = array();

		if (isset($_SESSION["UserID"])) {
			$ID = $_SESSION["UserID"];
		} else {
			return false;
		}

		$Stm = $DB->prepare("SELECT `Gadget`, `Weight` FROM T_Gadgets WHERE `User` = :User");
		$Stm->execute(array(':User' => $ID));
		$output["GadgetsCount"] = $Stm->fetchColumn();
		$output["Gadgets"] = $Stm->fetchAll(PDO::FETCH_ASSOC);

		return $output;
	}

	private function getAvailableGadgets () {
		$output = array();

		$output["GadgetsAvailable"] = array(
			"pages",
			"pages-locked",
			"pages-my",
			"pages-disabled",
			"pages-pending",
			"users",
			"users-pending",
			"users-disabled",
			"noticeboard"
		);

		return $output;
	}

}

?>