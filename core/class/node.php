<?php

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

	private function checkModuleRights ($module) {
		global $Rights, $DB;
		$ModuleRights = array();

		$Stm = $DB->prepare("SELECT `Group` FROM T_ModuleRights WHERE `Module` = '".$module."' LIMIT 80");
		$Stm->execute();
		$Res = $Stm->fetchAll(PDO::FETCH_ASSOC);


		foreach ($Res as $MRight) {
			array_push($ModuleRights, $MRight["Group"]);
		}

		foreach ($Rights as $Right) {
			if (in_array($Right, $ModuleRights)) {
				return true;
			}
		}

		return false;
	}

	private function checkNodeRights () {
		global $Rights, $DB;
		$NodeRights = array();

		$Stm = $DB->prepare("SELECT `Group` FROM T_NodeRights WHERE `Node` = '".$this->nodeName."' LIMIT 80");
		$Stm->execute();
		$Res = $Stm->fetchAll(PDO::FETCH_ASSOC);

		foreach ($Res as $NRight) {
			array_push($NodeRights, $NRight["Group"]);
		}

		foreach ($Rights as $Right) {
			if (in_array($Right, $NodeRights)) {
				return true;
			}
		}

		return false;
	}

	private function loadModules () {
		if ($this->node) {
			$slots = self::modulesConcat($this->node['Content'], $this->template['Content']);
			$content = array();
			if ($slots) {
				foreach ($slots as $slot => $modules) {
					$content['slot'][$slot] = '';
					foreach ($modules as $module) {
						$Right = $this->checkModuleRights($module['module']);

						
						ob_start();
						if ($Right) {
							parent::load(DEFAULT_MODULE_PATH.$module['module'].'.php', $module, false);			
						} else {
							parent::load(DEFAULT_MODULE_PATH.'message/show.php', array("html" => "{_'default_module_right_error', sprintf(".$module['module'].")}", "class" => "alert-danger"), false);						
						}
						$view = ob_get_contents(); ob_end_clean();	

						if ($this->stringtable !== false) {
							$Substitute = new Stringtable();
							$view = $Substitute->substitute((array) $this->stringtable, $view);
						}

						$content['slot'][$slot] .= $view;
					}
				}
			}

			$this->modules = $content;
		}
	}

	private function getTemplate ($template = false) {
		if ($this->node) {
			require_once TEMPLATE;
			if ($template === false) {
				$this->template = $Template[$this->node['Template']];	
			} else {
				$this->template = $Template[$template];	
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
		}
	}

	private function loadTemplate() {
		if ($this->node) {
			$this->loadModules();
			if (count($this->modules) > 0)
				$this->template["Content"] = $this->modules['slot'];

			if (!array_key_exists("Config", $this->node))
				$this->node["Config"] = array();
			
			if (!array_key_exists("Scripts", $this->template))
				$this->template["scripts"] = array();

			if (!array_key_exists("Styles", $this->template))
				$this->template["scripts"] = array();

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
			$this->getTemplate("error");
			$this->loadTemplate();
			return $this->view;
		}
	}
}

?>