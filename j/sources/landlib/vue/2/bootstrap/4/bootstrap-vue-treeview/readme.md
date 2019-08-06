This is the fork https://github.com/kamil-lip/bootstrap-vue-treeview

I need nodeRenamed event.
And deleteNode event add argument idList containts id all child nodes.
TreeView 'deleteNodeEx', node, nodeData) nodeData array of objects {id,parent_id,label}
TreeNode 'deleteNodeEx', node, nodeData) nodeData array of Number (id)

TreeView got new argument nodeParentKeyProp
TreeNode got new argument parentKeyProp

New depends treealg.js

