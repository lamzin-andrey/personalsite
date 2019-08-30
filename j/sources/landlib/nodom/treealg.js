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
	 * @param {Boolean} bSetChildsAsArray = false if true, all 'children' (this.childsFieldName) property will convert to array
	 * @return Array with root nodes in items
	 */
	buildTreeFromFlatList(aScopesArg, bSetChildsAsArray = false) {
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
			
			oItem[this.idFieldName] = parseInt(oItem[this.idFieldName]);
			oItem[this.parentIdFieldName] = parseInt(oItem[this.parentIdFieldName]);
			
			//перемещаем вложенные во внутрь
			if (oItem[this.parentIdFieldName] > 0) {
				oParent = aScopes[oItem[this.parentIdFieldName]];
				if (oParent) {
					if (!oParent[sChilds]) {
						oParent[sChilds] = {};
					}
					//a = &oParent->sChilds;
					a = oParent[sChilds];
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
			if (bSetChildsAsArray) {
				this.walkAndExecuteAction(oItem, {context:this, f:this._convertChildsToArray});
			}
			r.push(oItem);
		}

		return r;
	},
	/**
	 * @description Convert childs to array
	 * @param {Object} node 
	 */
	_convertChildsToArray(node) {
		let newChilds = [], k;
		for (k in node[this.childsFieldName]) {			
			newChilds.push(node[this.childsFieldName][k]);
		}
		node[this.childsFieldName] = newChilds;
	},
	/**
	 * @description Find nodt By Id
	 * @param {Object} node (or tree)
	 * @param {String} id
	 * @return Object node or null
	*/
	findById(node, id) {
		let r, i;
		if (node[this.idFieldName] == id) {
			return node;
		}
		if (node[this.childsFieldName]) {
			if ( node[this.childsFieldName] instanceof Array ) {
				for (i = 0; i < node[this.childsFieldName].length; i++) {
					r = this.findById( node[this.childsFieldName][i], id );
					if (r) {
						return r;
					}
				}
			} else {
				for (i in node[this.childsFieldName]) {
					r = this.findById( node[this.childsFieldName][i], id );
					if (r) {
						return r;
					}
				}
			}
			
		}
		return null;
	},
	/**
	 * @description Remove node from tree by node id
	 * @param {Object} tree (or tree)
	 * @param {String} id
	 * @return Boolean
	*/
	remove(tree, id) {
		let i, node = this.findById(tree, id), parentNode;
		if (!node) {
			return false;
		}
		if (node[this.parentIdFieldName]) {
			parentNode = this.findById(tree, node[this.parentIdFieldName]);
		}
		if (!parentNode || !parentNode[this.childsFieldName]) {
			return false;
		}
		
		if ( parentNode[this.childsFieldName] instanceof Array ) {
			for (i = 0; i < parentNode[this.childsFieldName].length; i++) {
				if (parentNode[this.childsFieldName][i][this.idFieldName] == node[this.idFieldName]) {
					parentNode[this.childsFieldName].splice(i, 1);
					//delete node;
					return true;
				}
			}
		} else {
			for (i in parentNode[this.childsFieldName]) {
				if (parentNode[this.childsFieldName][i][this.idFieldName] == node[this.idFieldName]) {
					delete parentNode[this.childsFieldName][i];
					//delete node;
					return true;
				}
			}
		}
		return false;
	},
	/**
	 * @description Return array of nodes from tree root to node with id = nId
	 * @param {Object} oNode 
	 * @param {Number} nId 
	 * @return Array
	 */
	getNodesByNodeId(oNode, nId) {
		let result = [],
			node = this.findById(oNode, nId);
		if (node) {
			result.push(node);
			while (node[this.parentIdFieldName]) {
				node = this.findById(oNode, node[this.parentIdFieldName]);
				if (node) {
					result.push(node);
				} else {
					break;
				}
			}
			return result.reverse(); 
		}
		return result;
	}
};
