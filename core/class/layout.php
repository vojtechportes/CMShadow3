<?php

Class Layout Extends Minimal {

	public $id;
	public $name;
	public $createdAt;
	public $modifiedAt;
	public $layout;
	public $parent = 0;
	public $user;
	public $slotName;
	public $path;
	public $class;
	public $identifier;
	public $data;
	public $slotTree;

	private function getAttributes () {
		return "
			T_Layouts.`ID` AS `LayoutID`,
			T_Layouts.`User`,			
			T_Layouts.`Name` AS `LayoutName`,
			T_Layouts.`CreatedAt`,
			T_Layouts.`ModifiedAt`";
	}

	private function getSlotAttributes () {
		return "
			T_LayoutSlots.`ID`,
			T_LayoutSlots.`Layout`,
			T_LayoutSlots.`Parent`,
			T_LayoutSlots.`Weight`,
			T_LayoutSlots.`Name`,
			T_LayoutSlots.`Path`,
			T_LayoutSlots.`Class`,
			T_LayoutSlots.`Identifier`,
			T_LayoutSlots.`Data`";
	}	

	private function extractLayout ($data) {
		$Layout = array();
		$Layout['Slots'] = array();

		$Layout['Layout'] = array(
			'ID' => $data[0]['LayoutID'],
			'User' => $data[0]['User'],
			'Name' => $data[0]['LayoutName'],
			'CreatedAt' => $data[0]['CreatedAt'],
			'ModifiedAt' => $data[0]['ModifiedAt']
		);

		foreach ($Layout as $key => $slot) {
			$Layout['Slots'][$key] = $slot;
			unset($Layout['Slots'][$key]['LayoutID']);
			unset($Layout['Slots'][$key]['User']);
			unset($Layout['Slots'][$key]['LayoutName']);
			unset($Layout['Slots'][$key]['CreatedAt']);
			unset($Layout['Slots'][$key]['ModifiedAt']);
		}

		return $Layout;
	}

	public function getLayoutByIdDetailed ($id) {
		global $DB;
		$Stm = $DB->prepare("SELECT
		{$this->getAttributes()},
		{$this->getSlotAttributes()},
		(SELECT COUNT(T_LayoutSlotsB.`ID`)
			WHERE T_LayoutSlots.`ID` = T_LayoutSlotsB.`Parent`
			AND T_LayoutSlots.`Layout` = T_LayoutSlotsB.`Layout`) AS numChildSlots		
		FROM T_Layouts
		LEFT JOIN T_LayoutSlots
		ON T_Layouts.`ID` = T_LayoutSlots.`Layout`
		WHERE T_Layouts.`ID` = :ID
		ORDER BY T_LayoutSlots.`Weight` ASC, T_LayoutSlots.`Path` ASC");			
		$Stm->execute(array(
			':ID' => $id
		));
		$Data = $Stm->fetchAll(PDO::FETCH_ASSOC);
		return $this->extractLayout($Data);

	}

	public function getLayoutById ($id) {
		global $DB;
		$Stm = $DB->prepare("SELECT
		{$this->getSlotAttributes()}
		FROM T_LayoutSlots
		WHERE T_LayoutSlots.`ID` = :ID
		LIMIT 1");			
		$Stm->execute(array(
			':ID' => $id
		));
		return $Stm->fetch(PDO::FETCH_ASSOC);		
	}

	public function getLayoutSlotById () {
		global $DB;
		$Stm = $DB->prepare("SELECT
		{$this->getAttributes()}
		FROM T_Layouts
		WHERE T_Layouts.`ID` = :ID
		LIMIT 1");			
		$Stm->execute(array(
			':ID' => $id
		));
		return $Stm->fetch(PDO::FETCH_ASSOC);	
	}

	public static function getLayoutSlotsByParent ($parent = 0, $id = false) {
		if ($id) {
			global $DB;
			$Stm = $DB->prepare("SELECT
				{$this->getSlotAttributes()},
				(SELECT COUNT(T_LayoutSlotsB.`ID`)
					WHERE T_LayoutSlots.`ID` = T_LayoutSlotsB.`Parent`
					AND T_LayoutSlots.`Layout` = T_LayoutSlotsB.`Layout`) AS numChildSlots
				WHERE T_LayoutSlots.`Parent` = :Parent AND T_LayoutSlots.`Layout` = :ID
				ORDER BY T_LayoutSlots.`Weight` ASC, T_LayoutSlots.`Path` ASC");
			$Stm->execute(array(
				':Parent' => $parent,
				':ID' => $id
			));

			return $Stm->fetchAll(PDO::FETCH_ASSOC);
		}
		return false;
	}

	public function getLayoutSlotsTree ($parent = 0, $id = false, $depth = 0) {
		$slots = self::getLayoutSlotsByParent($parent, $id);

		foreach ($slots as $slot) {
			$slot['Depth'] = $depth;
			$this->slotsTree[] = $slot;		
			if ($slot['numChildSlots'] > 0) {
				self::getLayoutSlotsTree($slot['ID'], $id, $depth + 1);
			}
		}

		$depth = $depth -1;
	}

	public function createLayout () {
		global $DB;
		$Stm = $DB->prepare("INSERT INTO T_Layouts (`Name`, `User`) VALUES (:Name, :User)");
		$Stm->execute(array(
			':Name' => $this->name,
			':User' => $this->user
		));

		return $Stm->rowCount();
	}

	public function createLayoutSlot () {
		global $DB;
		$Stm = $DB->prepare("INSERT INTO T_Layouts
			(`Layout`, `Parent`, `Weight`, `Name`, `Path`, `Class`, `Identifier`, `Data`)
			VALUES (:Layout, :Parent, :Weight, :Name, :Path, :Class, :Identifier, :Data)");
		$Stm->execute(array(
			':Layout' => $this->layout,
			':Parent' => $this->parent,
			':Weight' => $this->weight,
			':Name' => $this->name,
			':Path' => $this->path,
			':Class' => $this->class,
			':Identifier' => $this->identifier,
			':Data' => $this->data
		));

		return $Stm->rowCount();
	}

	public function updateLayoutSlot () {
		global $DB;

		$Stm = $DB->prepare("UPDATE T_LayoutSlots SET
			`Parent` = :Parent,
			`Weight` = :Weight,
			`Name` = :Name,
			`Path` = :Path,
			`Class` = :Class,
			`Identifier` = :Identifier,
			`Data` = :Data WHERE T_LayoutSlots.`ID` = :ID");
		$Stm->execute(array(
			':Parent' => $this->parent,
			':Weight' => $this->weight,
			':Name' => $this->name,
			':Path' => $this->path,
			':Class' => $this->class,
			':Identifier' => $this->identifier,
			':Data' => $this->data
		));

		return $Stm->rowCount();
	}

	public function deleteLayoutSlot () {
		global $DB;

		$Slot = $this->getLayoutById();
		if (!empty($Slot)) {
			if ($Slot['hasChildSlots'] > 0) {
				$Layout = $Slot['Layout'];

				$this->getLayoutSlotsTree($this->id, $Slot, 0);
				$Slots = $this->slotTree;
			} else {
				$Slots = array($Slot);
			}

			$Stm = $DB->prepare("DELETE FROM T_LayoutSlots WHERE T_LayoutSlots.`ID` = :ID");

			foreach ($Slots as $slot) {
				$Stm->execute(array(
					':ID' => $slot['ID'];
				));
			}

			return $Stm->rowCount();
		}
	}

	public function deleteLayoutSlotsByLayout () {
		global $DB;

		$Stm = $DB->prepare("DELETE FROM T_LayoutSlots WHERE T_LayoutSlots.`Layout` = :Layout");
		$Stm->execute(array(
			':Layout' => $this->layout
		));
		return $Stm->rowCount();
	}

	public function deleteLayout () {
		global $DB;

		$this->deleteLayoutSlotsByLayout();
		
		$Stm = $DB->prepare("DELETE FROM T_Layout WHERE T_Layout.`ID` = :ID LIMIT 1");
		$Stm->execute(array(
			':ID' => $this->id;
		));
	}

}

?>