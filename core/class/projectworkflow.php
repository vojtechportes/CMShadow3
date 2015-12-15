<?php

Class ProjectWorkflow Extends Project {
	public $state;
	public $projectRights;

	public function checkProjectWorkflowRights () {
		$Rights = $this->getProjectWorkflowRights()[$this->state];

		foreach ($this->projectRights as $right) {
			if (in_array($right, $Rights))
				return true;
			return false;
		}
	}

	public function changeState () {
		switch ($this->state) {
			case '1':
			case '2':
				/* Will unlock the project if project is locked */
				$this->changeProjectState();
				break;

			case '3':
			case '4':
				/* In this two states, project is locked and is waiting for actions 5 or 6 */
				$this->changeProjectState();
				break;

			case '5':
				/* Will release project, copy all changes to zero version of pages. This change can be taken back by rollback. Project can be also reopened. */
				$this->changeProjectState();
				break;

			case '6':
				/* Will remove all pages from project and unlock zero version of pages so they are available. This step is final. */
				$this->changeProjectState();
				break;

			case '7':
				/* Will rollback release of the project if is last project made and keep project in state ready for release */
				$this->state = 3;
				$this->changeProjectState();
				break;

			case '8':
				/* Will create new project and keep pages locked or fail if pages are in different project already */
				$this->state = 1;
				$this->changeProjectState();
				break;
		}
	}

}

?>