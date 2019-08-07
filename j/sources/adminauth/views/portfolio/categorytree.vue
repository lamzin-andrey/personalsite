<template>
	<div>
		<accordionselecttree
			id="portfolioCategoriesTree"
			label="Categories"
			:value="value"
			@input="$emit('input', value)"
			:treedata="portfolioCategoriesTree"
			:showIcons="true" 
			:showIcon="true" 
			defaultIconClass="fas fa-book"
			urlCreateNewItem="/p/portfoliocats/pcsave.jn/"
			urlUpdateItem="/p/portfoliocats/pcsave.jn/"
			urlRemoveItem="/p/portfoliocats/pcdelte.jn/"
		></accordionselecttree>
	</div>
</template>
<script>
	//TODO перерегистрировать локально
	Vue.component('accordionselecttree', require('../../../landlib/vue/2/bootstrap/4/accordionselecttree/accordionselecttree'));
    export default {
		model: {
			prop: 'value',
			event: 'input'
		},
		props: {
			'id': {
				type: String
			},
			'value': {
				type: Number
			},
		},
		name: 'categorytree',
		
        //вызывается раньше чем mounted
        data: function(){return {
			val: 2222,
			/** @property {Array} данные дерева категорий */
            portfolioCategoriesTree: [{"id": 2222, "name": "Zevs", "children": [{"id": 3333, "parent_id" : 2222, "name": "Neptune"}, {"id": 4444, "parent_id" : 2222, "name": "Stratus"} ] } ],
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
			
        }, //end methods
        //вызывается после data, поля из data видны "напрямую" как this.fieldName
        mounted() {
			
        }
    }
</script>