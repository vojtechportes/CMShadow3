<?php

	Class Install Extends Minimal {
		private $version;
		public $source;

		public function DBInstall () {
			global $DB;
			$status = array();

			require_once $this->source;

			$Stm = $DB->prepare("SELECT * FROM information_schema.tables WHERE table_schema = '".DB_NAME."' AND table_name = 'T_Install' LIMIT 1;");
			$Stm->execute();
			$Res = $Stm->fetchAll(PDO::FETCH_COLUMN, 0);

			if (count($Res) == 0) {
				foreach ($Database as $item) {
					foreach ($item as $key => $val) {
						if ($key == 'query') {
							$status[] = (bool) $DB->query($val);
						} else if (preg_match("/exec-repeat\(.*\)/", $key)) {
							preg_match("/exec-repeat\((.*) as (.*)\)/", $key, $FVars);
							preg_match("/VALUES\s(\(.*\))/", $val, $Vals);				
							preg_match_all("/([$][a-zA-Z_]*(\[(?:[\"])?([a-zA-Z_0-9]*)(?:[\"])?\])*)/", $Vals[1], $Vars);
							$Haystack = substr($FVars[1], 1);
							$Needle = $FVars[2];
							$Query = array();

							foreach ($DatabaseValues[$Haystack] as $j => $$Needle) {				
								$Array = $$Needle;
								$Query[$j] = $Vals[1];
								$Queries = array();
								
								foreach ($Vars[count($Vars) - 1] as $k => $Value) {
									$Query[$j] = str_replace($Vars[0][$k], $Array[$Value], $Query[$j]);
								}
							}
							$val = preg_replace("/VALUES\s(\(.*\))/", 'VALUES '.str_replace('$', '\\$', implode($Query, ', ')), $val);
							$status[] = $DB->exec($val);
						}
					}
				}
			}

			return $status;

		}
	}

?>