<?php

	Class Heading Extends Minimal {

		public $level;
		public $html;
		public $class;
		public $id;
		public $templateOutput = DEFAULT_OUTPUT;
		public $template = '/heading/show';

		public function __construct ($level, $html, $class, $id) {
			$this->level = $level;
			$this->html = $html;
			$this->class = $class;
			$this->id = $id;
		}

		public function output () {
			$output = array(
				"level" => $this->level,
				"html" => $this->html,
				"class" => $this->class,
				"id" => $this->id,
				"object" => $this
			);

			ob_start();
			parent::load(DEFAULT_TEMPLATE_PATH.$this->templateOutput.$this->template, $output);
			$html = ob_get_contents(); ob_end_clean();
			echo $html;
		}

	}

?>