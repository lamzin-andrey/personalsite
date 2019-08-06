var data = {
	/** @property {Array} данные дерева категорий */
	tree: [{"id": 2222, "name": "Venus"}, {"id": 3333, "parent_id" : 2222, "name": "Neptune"}, {"id": 4444, "parent_id" : 2222, "name": "Stratus"} ],
	tree2: [{"id": 2222, "name": "Venus", "parent_id" : 33, "children" : {}} ]
};

window.onload = function(){
	var arr = data.tree;
	console.log('arr', arr);
	oTree = TreeAlgorithms.buildTreeFromFlatList(arr);
	console.log('oTree', oTree);
	var k = TreeAlgorithms.remove(oTree[0], 3333);
	console.log('k', k);
}
