/**
 * Algorithm for work with tree. 
*/
window.TreeAlgorithms = {
	/** @property {String} idFieldName */
	idFieldName : 'id',

	/** @property {String} parentIdFieldName */
	parentIdFieldName : 'parent_id',

	/** @property {String} childsFieldName */
	childsFieldName : 'children',

	/**
	 * @description Get all "this.idFieldName" values from node and all node childs (all levels)
	 * @param {Object} node 
	 * @return Array of "this.idFieldName" nodes  (all levels)
	 */
	getBranchIdList(node) {
		let r = [], part, i, j;
		r.push(node[this.idFieldName]);
		if (node[this.childsFieldName]) {
			part = [];
			if ( node[this.childsFieldName] instanceof Array ) {
				for (i = 0; i < node[this.childsFieldName].length; i++) {
					part = this.getBranchIdList( node[this.childsFieldName][i] );
					for (j = 0; j < part.length; j++) {
						r.push(part[j]);
					}
				}
			} else {
				for (i in node[this.childsFieldName]) {
					part = this.getBranchIdList( node[this.childsFieldName][i] );
					for (j = 0; j < part.length; j++) {
						r.push(part[j]);
					}
				}
			}
			
		}
		return r;
	},
	/**
	 * @description walking oTree and execute oCallback(currentNode)
	 * @param {Object} oTree
	 * @param {Object} oCallback  {context, f:function}
	 */
	walkAndExecuteAction(oTree, oCallback) {
		let i;
		oCallback.f.call(oCallback.context, oTree);
		if (oTree[this.childsFieldName] ) {
			if (oTree[this.childsFieldName] instanceof Array) {
				for (i = 0; i < oTree[this.childsFieldName].length; i++) {
					this.walkAndExecuteAction(oTree[this.childsFieldName][i], oCallback);
				}
			} else {
				for (i in oTree[this.childsFieldName]) {
					this.walkAndExecuteAction(oTree[this.childsFieldName][i], oCallback);
				}
			}
		}
	},
	/**
	 * @description build tree from flat list
	 * @param {Object} aScopesArg array of objects {this.idFieldName, this.parentIdFieldName}
	 * @return Array with root nodes in items
	 */
	buildTreeFromFlatList(aScopesArg) {
		let aBuf, nId, oItem, sChilds, oParent, a, r = [], i;
		
		aScopes = [...aScopesArg];
		aBuf = {};
		
		if (aScopes instanceof Array) {
			for (i = 0; i < aScopes.length; i++) {
				nId = aScopes[i][this.idFieldName];
				aBuf[nId] = aScopes[i];
				aBuf[nId][this.childsFieldName] = {};
			}
		} else {
			for (nId in aScopes) {
				oItem = aScopes[nId];
				aBuf[nId] = oItem;
				aBuf[nId][this.childsFieldName] = {};
			}
		}
		aScopes = aBuf;
		
		//тут строим дерево
		sChilds = this.childsFieldName;
		for (nId in aScopes) {
			oItem = aScopes[nId];
			
			oItem[this.idFieldName] = parseInt(oItem[this.idFieldName]); //it need?
			
			//перемещаем вложенные во внутрь
			if (oItem[this.parentIdFieldName] > 0) {
				oParent = aScopes[oItem[this.parentIdFieldName]];
				if (oParent) {
					if (!oParent[sChilds]) {
						oParent[sChilds] = {};
					}
					//a = &oParent->sChilds;
					a = oParent[sChilds];
					//console.log();
					a[nId] = oItem;
					//aScopes[nId] = &a[nId];
					aScopes[nId] = a[nId];
					aScopes[nId].isMoved = true;
				}
			}
		}
		
		//удаляем из корня ссылки на перемещенные в родителей.
		for (nId in aScopes) {
			oItem = aScopes[nId];
			if (oItem.isMoved) {
				delete aScopes[nId];
			}
		}
		for (nId in aScopes) {
			oItem = aScopes[nId];
			r.push(oItem);
		}
		return r;
	}
};