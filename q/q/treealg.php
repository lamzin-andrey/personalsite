<?php
class TreeAlgorithms {
	/** @property strings idFieldName */
	static public $idFieldName = 'id';

	/** @property string parentIdFieldName */
	static public $parentIdFieldName = 'parent_id';

	/** @property string childsFieldName */
	static public $childsFieldName = 'children';
	
	/**
	 * @description Get all "$this->idFieldName" values from node and all node childs (all levels)
	 * @param StdClass node 
	 * @return array of "$this->idFieldName" nodes  (all levels)
	*/
	static public function getBranchIdList($node)
	{
		$r = [];
		$sField = static::$idFieldName;
		$r[] = $node->$sField;
		
		$sField = static::$childsFieldName;
		if ($node->$sFieldName) {
			$part = [];
			foreach ($node->$sFieldName as $oItem) {
				$part = static::getBranchIdList($oItem);
				for ($j = 0; $j < count($part); $j++) {
					$r[] = $part[$j];
				}
			}
		}
		return r;
	}
	/**
	 * @description walking oTree and execute oCallback(currentNode)
	 * @param StdClass &$oTree
	 * @param StdClass $oCallback  {context, f:function, isStatic:bool}
	*/
	static public function walkAndExecuteAction(&$oTree, $oCallback)
	{
		//oCallback.f.call(oCallback.context, oTree);
		$sFunction = $oCallback->f;
		if (isset($oCallback->isStatic) && $oCallback->isStatic == true) {
			$oCallback->context::$sFunction($oTree);
		} else {
			$oCallback->context->$sFunction($oTree);
		}
		$sFieldName = static::$childsFieldName;
		if ($oTree->$sFieldName) {
			foreach ($oTree->$sFieldName as $oItem) {
				static::walkAndExecuteAction($oItem, $oCallback);
			}
		}
	}
	/**
	 * @description build tree from flat list
	 * @param StdClass aScopesArg array of objects {this.idFieldName, this.parentIdFieldName}
	 * @param bool bSetChildsAsArray = false if true, all 'children' (this.childsFieldName) property will convert to array
	 * @return array with root nodes in items
	*/
	static public function buildTreeFromFlatList($aScopesArg, $bSetChildsAsArray = false)
	{
		//let aBuf, nId, oItem, sChilds, oParent, a, r = [], i;
		$r = [];
		$aBuf = [];
		foreach ($aScopes as $oItem) {
			$nId = $oItem[static::$idFieldName];
			$aBuf[$nId] = $oItem;
			$aBuf[$nId][static::$childsFieldName] = [];
		}
		$aScopes = $aBuf;
		
		//тут строим дерево
		$sChilds = static::$childsFieldName;
		
		foreach ($aScopes as $nId => $oItem) {
			$sFieldName = static::$idFieldName;
			$oItem->$sFieldName = intval($oItem->$sFieldName);
			$sFieldName = static::$parentIdFieldName;
			$oItem->$sFieldName = intval($oItem->$sFieldName);
			
			//перемещаем вложенные во внутрь
			//$sFieldName = static::$parentIdFieldName;
			if ($oItem->$sFieldName > 0) {
				$oParent = $aScopes[$oItem->$sFieldName];
				if ($oParent) {
					if (!$oParent->$sChilds) {
						$oParent->$sChilds = [];
					}
					//a = &oParent->sChilds;
					$a = &$oParent->$sChilds;
					$a[$nId] = $oItem;
					//aScopes[nId] = &a[nId];
					$aScopes[$nId] = &$a[$nId];
					$aScopes[$nId]->isMoved = true;
				}
			}
		}
		
		//удаляем из корня ссылки на перемещенные в родителей.
		foreach ($aScopes as $nId => $oItem) {
			if ($oItem->isMoved) {
				unset($aScopes[$nId]);
			}
		}
		foreach ($aScopes as $nId => $oItem) {
			if ($bSetChildsAsArray) {
				$oCallback = new StdClass();
				$oCallback->context = static;
				$oCallback->isStatic= true;
				$oCallback->f = '_convertChildsToArray';
				static::walkAndExecuteAction($oItem, $oCallback);
			}
			$r[] = $oItem;
		}
		return $r;
	}
	/**
	 * @description Convert childs to array
	 * @param StdClass &$node 
	*/
	static private function _convertChildsToArray(&$node)
	{
		$newChilds = [];
		$sChldFieldName = static::$childsFieldName;
		foreach ($node->$sChldFieldName as $k => $oItem) {			
			$newChilds[] = $oItem;
		}
		$node->$sChldFieldName = $newChilds;
	}
	/**
	 * @description Find nodt By Id
	 * @param StdClass node (or tree)
	 * @param string id
	 * @return StdClass node or null
	*/
	static public function findById($node, $id)
	{
		$sIdFieldName = static::$idFieldName;
		if ($node->$sIdFieldName == $id) {
			return $node;
		}
		$sChildsFieldName = static::$childsFieldName;
		if ($node->$sChildsFieldName) {
			foreach ($node->$sChildsFieldName as $currNode) {
				$r = static::findById($currNode, $id);
				if ($r) {
					return $r;
				}
			}
		}
		return null;
	}
	//TODO next remove
}
