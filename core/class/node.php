<?php

/* Requiring TEMPLATE and HIEARCHY should be moved somewhere else... */

Class Node Extends Minimal {

	protected $hiearchy;
	protected $template;
	protected $stringtable;
	protected $modules;
	protected $view;
	protected $_tpl;
	protected $tpl;
	protected $node;
	protected $nodes = array();
	protected $nodeName;
	protected $blacklist = ["Template", "Content", "Config"];

	public function __construct () {
		global $Path, $M;
		$this->getHiearchy();
	}

	private function getHiearchy () {
		global $Path;
		$AdminPath = "/^".preg_quote(ADMIN_PATH, "/")."/";
		if (preg_match($AdminPath, $Path)) {
			require HIEARCHY;
			$this->hiearchy = $Hiearchy;
		} else {
			$this->hiearchy = false;
		}
	}

	private function getTemplatePath () {
		global $M;

		if (array_key_exists('OutputStyle', $this->template))
			return $this->template['OutputStyle'];
		return DEFAULT_OUTPUT;
	}

	private function getNode (array $array, $depth) {
		global $Path, $Template, $M;

		foreach($array as $path => $node) {
			if (is_array($node))
				if (array_key_exists("Template", $node))
					$this->_tpl[$depth] = $node["Template"];

			if ($path !== $Path && self::countSubpages($node) > 0) {				
				self::getNode($node, ($depth + 1));
			} else if ($path === $Path) {
				$selected = false;

				if (!array_key_exists("Content", $node))
					$node["Content"] = array();

				if (!array_key_exists("Template", $node)) {
					foreach ($this->_tpl as $key => $val) {
						if ($depth - 1 > $key || $selected === false) {
							$selected = true;
							$this->tpl = $val;
						}
					}
					$node['Template'] = $this->tpl;
				}

				$this->node = $node;
				$this->nodeName = $path;
			}
		}
	}

	private function getNodes (array $array, $depth, $filter = true) {
		foreach ($array as $path => $node) {			
			if (in_array($path, $this->blacklist) === false) {
				if (!array_key_exists("Config", $node))
					$node["Config"] = array();

				if ((bool) $filter) {
					if (array_search_multi($node, $filter)) {
						$this->nodes[] = array("Config" => $node["Config"], "Path" => $path);
					}
					if (is_array($node)) {
						self::getNodes($node, $depth, $filter);
					}	
				} else {
					$this->nodes[] = array("Config" => $node["Config"], "Path" => $path);
					if (is_array($node)) {
						self::getNodes($node, $depth, $filter);
					}		
				}	
			}	
		}
	}

	public function getAllNodes ($filter = true) {
		if ($this->hiearchy)
			$this->getNodes($this->hiearchy, 0, $filter);
		return $this->nodes;
	}

	private function countSubpages ($array) {
		if (is_array($array)) {
			$i = 0;
			foreach ($array as $key => $val) {
				switch($key) {
					case 'Template':
						unset($array[$key]);
						break;
					case 'Content':
					case 'Properties':
						unset($array[$key]);
						break;
					default:
						$i++;
						break;
				}
			}

			return $i;
		} else {
			return 0;
		}
	}

	private function isVisible () {

	}

	private static function modulesConcat ($node, $template) {
		$slots = array_merge_recursive((array) $node, (array) $template);
		$output = array();

		if (count($slots) > 0) {
			foreach ($slots as $name => $slot) {
				if (count($slot) > 0) {
					foreach ($slot as $key => $val) {
						if (!array_key_exists("weight", $val))
							$val["weight"] = 50;
						$output[$name][$val['weight'].$key] = $val;
					}

					ksort($output[$name]);
				} else {
					$output[$name] = array();
				}
			}
			
			return $output;
		} else {
			return false;
		}
	}

	private function checkNodeRights () {
		global $Rights;
		$NodeRights = array();

		$Res = UserRights::getNodeRights($this->nodeName);

		foreach ($Res as $NRight) {
			array_push($NodeRights, $NRight["Group"]);
		}

		foreach ($Rights['Group'] as $Right) {
			if (in_array($Right, $NodeRights)) {
				return true;
			}
		}

		return false;
	}

	private function loadModules () {
		if ($this->node) {
			global $M;

			$slots = self::modulesConcat($this->node['Content'], $this->template['Content']);
			$content = array();
			if ($slots) {
				foreach ($slots as $slot => $modules) {
					$content['slot'][$slot] = '';
					foreach ($modules as $module) {
						$Right = Module::checkModuleRights($module['module']);
						$OutputStyle = $this->getTemplatePath();
						$Encode = false;

						$OutputType = $this->template['OutputType'];

						if ($this->template['OutputType'] === 'JSON')
								$Encode = true;

						ob_start();
						if ($Right) {
							parent::load(DEFAULT_MODULE_PATH.$module['module'].'.php', $module + array("OutputStyle" => $OutputStyle, "OutputType" => $OutputType), false);			
						} else {
							parent::load(DEFAULT_MODULE_PATH.'message/show.php', array("html" => "{_'default_module_right_error', sprintf(".$module['module'].")}", "class" => "alert-danger", "OutputStyle" => $OutputStyle, "OutputType" => $OutputType), false);						
						}

						$view = ob_get_contents(); ob_end_clean();


						if ($this->stringtable !== false) {
							$Substitute = new Stringtable();							
							$view = $Substitute->substitute((array) $this->stringtable, $view, $Encode);

						}

						//var_dump($view);

						if ($OutputType === 'JSON') {
							if (json_validate($view))
								$content['slot'][$slot][] = json_decode($view);
						} else {
							$content['slot'][$slot] .= $view;
						}
					}

					if ($OutputType === 'JSON') {
						if (gettype($content['slot'][$slot]) === "array") {
							$content['slot'][$slot] = json_encode(array_filter($content['slot'][$slot]));
						} else {
							$content['slot'][$slot] = json_encode(array());	
						}
					}

				}
			}

			$this->modules = $content;
		}
	}

	private function getTemplate ($template = false, $info = false) {
		if ($this->node) {
			require TEMPLATE;
			if (!$info) {
				if ($template === false) {
					$this->template = $Template[$this->node['Template']];	
				} else {
					if (array_key_exists($template, $Template)) {
						$this->template = $Template[$template];	
					} else {
						$this->template = false;
					}
				}
				if (array_key_exists('Stringtables', $this->template)) {
					$Stringtables = new Stringtable();
					foreach ($this->template['Stringtables'] as $stringtable) {
						$Stringtables->load($stringtable);
					}

					$this->stringtable = $Stringtables->output();
				} else {
					$this->stringtable = false;
				}
			} elseif (!$template || $info) {
				if (array_key_exists($template, $Template)) {
					$_template = $Template[$template];	
					if (!array_key_exists("OutputType", $_template))
						$_template["OutputType"] = "HTML";
					return $_template;
				} else {
					return false;
				}
			}				
		}
	}

	private function loadTemplate() {
		if ($this->node) {
			if (!array_key_exists("Scripts", $this->template))
				$this->template["Scripts"] = array();

			if (!array_key_exists("Styles", $this->template))
				$this->template["Styles"] = array();

			if (!array_key_exists("OutputType", $this->template))
				$this->template["OutputType"] = "HTML";		

			$this->loadModules();

			if (count($this->modules) > 0)
				$this->template["Content"] = $this->modules['slot'];

			if (!array_key_exists("Config", $this->node))
				$this->node["Config"] = array();	

			ob_start();
			parent::load(DEFAULT_TEMPLATE_PATH.$this->template['Output'], $this->template + array("NodeConfig" => $this->node["Config"]));
			$view = ob_get_contents(); ob_end_clean();

			if ($this->stringtable !== false) {
				$Substitute = new Stringtable();
				$view = $Substitute->substitute((array) $this->stringtable, $view);
			}

			$this->view = $view;
		}
		
	}

	public function output () {
		global $Path, $LoggedIn, $M;
		if ($this->hiearchy)
			$this->getNode($this->hiearchy, 0);

		if ($this->nodeName == LOGIN_PATH && $LoggedIn)
			redirect(ADMIN_PATH, "?source=login");
		if ($this->nodeName != LOGIN_PATH && !$LoggedIn)
			redirect(LOGIN_PATH, "?source=".$_SERVER["REQUEST_URI"]);

		if ($this->node && $this->checkNodeRights()) {
			$this->getTemplate();
			$this->loadTemplate();

			return $this->view;
		} else {
			if ($this->getTemplate($this->node['Template'], true)['OutputType'] === 'JSON') {
				$Template = $this->getTemplate("error-json");
			} else {
				$Template = $this->getTemplate("error");
			}
			$this->loadTemplate();
			return $this->view;
		}
	}
}

?>