<template>
	<div>
		<input	v-model="selectedNodeId"  type="hidden" :id="id" :name="id">
		<div class="accordion" :id="id + 'Accord'">
			<div class="card">
				<div class="card-header" :id="id + 'AccordHeading'">
				<h5 class="mb-0">
					<label>{{label}}: </label>
					<button :class="btnCss"  type="button" data-toggle="collapse" data-target="#collapsePortCatTreeAccord" aria-expanded="true" aria-controls="collapseSeo">
						<span v-if="selectedNode">{{ selectedNode[nodeLabelProp] }}</span> 
					</button>
				</h5>
			</div>
			<div id="collapsePortCatTreeAccord" class="collapse" :aria-labelledby="id + 'AccordHeading'" :data-parent="'#' + id + 'Accord'">
				<div class="card-body">
					<b-tree-view :ref="'v' + id" 
						:data="data" 
						:contextMenuItems="contextMenuItems" 
						:showIcons="showIcons" 
						showIcon="true"
						:defaultIconClass="defaultIconClass"
						:allowMultiple="allowMultiple"
						:nodeKeyProp="nodeKeyProp"
						:nodeChildrenProp="nodeChildrenProp"
						:nodeLabelProp="nodeLabelProp"
						:nodesDraggable="nodesDraggable"
						:contextMenu="contextMenu"
						:renameNodeOnDblClick="renameNodeOnDblClick"
						:prependIconClass="prependIconClass"			
						:iconClassProp="iconClassProp"
						></b-tree-view>
				</div>
				</div>
			</div>
		</div>

	</div>
</template>
<script>
	//Компонент для дерева категорий
	//так импортировалось из bootstrap-vue-treeview
    //import BootstrapVueTreeview from 'bootstrap-vue-treeview';
	//Vue.use(BootstrapVueTreeview);

	//Пытаюсь импортировать из своего форка
	import BootstrapVueTreeview from '../bootstrap-vue-treeview/index';
	Vue.use(BootstrapVueTreeview);

    export default {
		model: {
			prop: 'value',
			event: 'input'
		},
		props: {
			id: {
				type: String
			},
			value: {
				type: Number
			},
			label: {
				type: String
			},
			/** @property field name for parent id */
			nodeParentKeyProp: {
                type: String,
                default: 'parent_id'
			},
			/** @property {String} url post request created new menu item*/
			urlCreateNewItem: {
				type:String,
				default: ''
			},
			/** @property {String} url post request updated (rename) menu item */
			urlUpdateItem: {
				type:String,
				default: ''
			},
			/** @property {String} url post request delete menu item */
			urlRemoveItem: {
				type:String,
				default: ''
			},
			//This props will be passed in TreeView
			data: {
                type: Array,
                required: true
            },
            allowMultiple: {
                type: Boolean,
                default: false
            },
            nodeKeyProp: {
                type: String,
                default: 'id'
            },
            nodeChildrenProp: {
                type: String,
                default: 'children'
            },
            nodeLabelProp: {
                type: String,
                default: 'name'
            },
            nodesDraggable: {
                type: Boolean,
                default: false
            },
            contextMenu: {
                type: Boolean,
                default: true
			},
			/* Exclude from props
            contextMenuItems: {
                type: Array,
                default: () => {
					return [
						{code: 'ADD_NODE', label: 'Add node'},
						{code: 'RENAME_NODE', label: 'Rename node'},
						{code: 'DELETE_NODE', label: 'Delete node'}
					];
				}
			},*/
            renameNodeOnDblClick: {
                type: Boolean,
                default: true
            },
            // class added to every icon no matter what
            prependIconClass: {
                type: String,
                default: null
            },
            // default icon if node icon is not specified
            defaultIconClass: {
                type: String,
                default: null
            },
            // where to search for node icon
            iconClassProp: {
                type: String,
                default: "icon"
            },
            // show icons
            showIcons: {
                type: Boolean,
                default: false
            }
		},
		name: 'categorytree',
		
        //вызывается раньше чем mounted
        data: function(){return {
			
			/** @property {Object} defaultSelectedNode Значение активной ноды по умолчанию (ничего не выбрано) */
			defaultSelectedNode : {name: this.$t('app.Nothing_select'), id : 0},
			
			/** @property {Object} selectedNode Активная нода */
			selectedNode : this.defaultSelectedNode,

			/** @property {Number} selectedNodeId id активной ноды */
			selectedNodeId : 0,

			/** @property {String} Вид кнопки с именем выбранной категории */
			btnCss : 'btn btn-danger',

			/** @property {Array} contextMenuItems */
			contextMenuItems :[
				{code: 'ADD_NODE', label: this.$root.$t('app.Add_node')},
				{code: 'RENAME_NODE', label: this.$root.$t('app.Rename_node')},
				{code: 'DELETE_NODE', label: this.$root.$t('app.Delete_node')}
			],
			
		}; },
		computed: {
			/*dinlabel : function() {
				if (this.placeholderlabel) {
					return this.placeholderlabel;
				}
				return this.label;
			}*/
		},
        //
        methods:{
			/**
			 * @description Обработка выбора пункта контекстного меню дерева категорий
			*/
			onSelectTreeViewContextMenuItem(item, node){
				console.log(item);
				console.log(node.data);
				if (item.code == 'ADD_NODE') {
					//TODO add spinner
					/* <div role="status" class="spinner-grow small">
									  <span class="sr-only">Loading...</span>
					</div>*/
					//data, onSuccess, url, onFail

					if (this.nRequestAddNodeId) {
						this.showError(this.$t('app.Add_request_already_sended_wait'));//TODO loc and showError
						return;
					}
					let id = node.data[this.nodeKeyProp];
					this.nRequestAddNodeId = id;
					Rest._post({parent_id : id}, (data) => {this.onSuccessAddNewItem(data); }, this.urlCreateNewItem, (a, b, c) => {this.onFailItemAction(a, b, c);});
				}
				if (item.code == 'DELETE_NODE') {
					//TODO add spinner
					/* <div role="status" class="spinner-grow small">
									  <span class="sr-only">Loading...</span>
					</div>*/
					//data, onSuccess, url, onFail

					if (!this.stackremovedItems) {
						//TODO сюда помещаем всех потомков ветки и ветку по id
						//скорее всего понадобиться в TreeView.menuItemSelected перед удалением собрать все id
						this.stackremovedItems = {};
					}

					let id = node.data[this.nodeKeyProp];
					this.nRequestDeleteNodeId = id;
					this.sRequestedNodeLabel = node.data[this.nodeLabelProp];
					this.nRequestedNodeParentId = node.data[this.nodeParentKeyProp];
					Rest._post({id : id}, (data) => {this.onSuccessDeleteItem(data); }, this.urlRemoveItem, (a, b, c) => {this.onFailDeleteItem(a, b, c);});
				}
				//On delete expand all child id and send to server
				//on add send info to server, get id. 
			},
			/**
			 * @description Processed success add new item
			 * @param {Object} data
			*/
			onSuccessAddNewItem(data) {
				if (!this.onFailItemAction(data)) {
					//TODO drop spinner
					return;
				}
				//if (!this.nodeMapCreated) {
					delete this.$refs['v' + this.id].nodeMap;
					this.$refs['v' + this.id].createNodeMap();
				//	this.nodeMapCreated = true;
				//}
				console.log('try search by key ' + this.nodeParentKeyProp + ' = ' + data[this.nodeParentKeyProp]);
				let x = this.$refs['v' + this.id].getNodeByKey(data[this.nodeParentKeyProp]);
				let newNodeData = {};
				newNodeData[this.nodeKeyProp] = data[this.nodeKeyProp];
				newNodeData[this.nodeLabelProp] = data[this.nodeLabelProp];
				newNodeData[this.nodeParentKeyProp] = data[this.nodeParentKeyProp];
				newNodeData.icon = this.defaultIconClass;
				x.appendChild(newNodeData);
			},
			/**
			 * @description default process failure item operations
			 * @param {Object} data
			*/
			onFailItemAction(data, b, c) {
				this.nRequestAddNodeId = 0;
				if (data.status && data.status == 'ok') {
					return true;
				}
				if (data.status && data.status == 'error') {
					if (data.msg) {
						this.showError(data.msg);
						return false;
					}
				} else {
					this.showError(this.$t('app.DefaultError') );
					return false;
				}
				
			},
			/**
			 * @description Repair item if it no remove
			 * @param {Object} data
			*/
			onFailDeleteItem(data, b, c) {
				this.nRequestAddNodeId = 0;
				if (data.status && data.status == 'ok') {
					return true;
				}
				if (data.status && data.status == 'error') {
					if (data.msg) {
						this.showError(data.msg);
						return false;
					}
				} else {
					this.showError(this.$t('app.DefaultError') );
					return false;
				}
				
			},
			/**
			 * @description 
			*/
			onRenameTreeViewItem(node) {
				console.log(node.data);
				let sendData = {};
				sendData[this.nodeKeyProp] = node.data[this.nodeKeyProp];
				sendData[this.nodeParentKeyProp] = node.data[this.nodeParentKeyProp];
				sendData[this.nodeLabelProp] = node.data[this.nodeLabelProp];
				Rest._post(sendData, (data) => {this.onSuccessRenameItem(data); }, this.urlUpdateItem, (a, b, c) => {this.onFailItemAction(a, b, c);});
			},
			/**
			 * @description On end rename item on server if no rename will show error
			*/
			onSuccessRenameItem(data) {
				if (!this.onFailItemAction(data)) {
					//TODO drop spinner
					return;
				}
			},
			/**
			 * TODO Пусть в конейнере со списком снизу розовый алерт выдвигается
			 */
			showError(s) {
				alert(s);
			},
			/**
			 * @description Обработка выбора элемента дерева.
			*/
			onSelectTreeViewItem(node, isSelected){
				if (isSelected) {
					this.selectedNode = node.data;
					this.btnCss = 'btn btn-success';
				} else if (node.data[this.nodeKeyProp] === this.selectedNode[this.nodeKeyProp]) {
					this.selectedNode = this.defaultSelectedNode;
					this.btnCss = 'btn btn-danger';
				}
				this.selectedNodeId = this.selectedNode[this.nodeKeyProp];
				this.$emit('input', this.selectedNodeId);
			},
			/**
			 * @param {TreeNode} oNode
			*/
			expandBranch(oNode) {
				oNode.expand();
				let x = this.$refs['v' + this.id].getNodeByKey(oNode.data[this.nodeParentKeyProp]);
				while (x) {
					x.expand();
					x = this.$refs['v' + this.id].getNodeByKey(x.data[this.nodeParentKeyProp]);
				}
			},
			/**
			 * @description Initalize defaultSelectedNode
			*/
			initDefaultSelectedNode() {
				//defaultSelectedNode : {name: this.$t('app.Nothing_select'), id : 0}
				this.defaultSelectedNode = {};
				this.defaultSelectedNode[this.nodeKeyProp] = 0;
				this.defaultSelectedNode[this.nodeLabelProp] = this.$t('app.Nothing_select');
			},
			/**
			 * @description Localize default menu only if it default menu
			*/
			localizeDefaultMenu(){
					this.contextMenuItems = [
						{code: 'ADD_NODE', label: this.$root.$t('app.Add_node')},
						{code: 'RENAME_NODE', label: this.$root.$t('app.Rename_node')},
						{code: 'DELETE_NODE', label: this.$root.$t('app.Delete_node')}
					];
			}
        }, //end methods
        
        mounted() {
			this.localizeDefaultMenu();
			this.initDefaultSelectedNode();
			this.selectedNode = this.defaultSelectedNode;

			console.log('in vcategorytree.Mounted ' + this.value);
			this.$refs['v' + this.id].createNodeMap();
			let x = this.$refs['v' + this.id].getNodeByKey(this.value);
			if (x) {
				x.select();
				this.expandBranch(x);
			}
			this.$refs['v' + this.id].$on('contextMenuItemSelect', (item, node) => {
                this.onSelectTreeViewContextMenuItem(item, node);
			});
			this.$refs['v' + this.id].$on('nodeSelect', (node, isSelected) => {
                this.onSelectTreeViewItem(node, isSelected);
			});
			this.$refs['v' + this.id].$on('nodeRenamed', (node) => {
                this.onRenameTreeViewItem(node);
			});
        }
    }
</script>