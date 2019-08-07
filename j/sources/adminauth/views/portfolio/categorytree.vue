<template>
	<div>
		<accordionselecttree
			id="portfolioCategoriesTree"
			label="Categories"
			v-model="selectedCategory"
			
			@input="$emit('input', value)"
			:treedata="portfolioCategoriesTree"
			:showIcons="true" 
			:showIcon="true" 
			defaultIconClass="fas fa-book"
			urlCreateNewItem="/p/portfoliocats/pcsave.jn/"
			urlUpdateItem="/p/portfoliocats/pcsave.jn/"
			urlRemoveItem="/p/portfoliocats/pcdelte.jn/"
			ref="acctree"
		></accordionselecttree>
	</div>
</template>
<script>
	//TODO перерегистрировать локально
	Vue.component('accordionselecttree', require('../../../landlib/vue/2/bootstrap/4/accordionselecttree/accordionselecttree'));
	require('../../../landlib/nodom/treealg');
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
				
			},
		},

		watch:{
			value:function(n, old) {
				//this.selectedCategory = n; - это скорее всего лажа
				console.log('CTree: new = ' + n + ', old = '  + old);
			}
		},

		name: 'categorytree',
		
        //вызывается раньше чем mounted
        data: function(){return {
			selectedCategory : 0,
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
			//parse data
			let data = $('#pcategorydata').val();
			//this.selectedCategory = this.value;
			console.log('this.selectedCategory', this.selectedCategory);
			console.log('this.value', this.value);
			try {
				data = JSON.parse(data);
				data = TreeAlgorithms.buildTreeFromFlatList(data.portfolioCategories, true);
				this.portfolioCategoriesTree[0] = data[0];
				setTimeout(() =>{
					this.selectedCategory = 2;//TODO получить категорию работы с сервера в данных. Использовать тик. Попробовать разобраться с реактивностью
					this.$refs.acctree.selectNodeById(this.selectedCategory);
				}, 500);
			} catch(e){}

			this.$refs.acctree.$on('input', (value) => {
				//this.onDeleteTreeViewItem(node, nodesData, idList);
				this.$emit('input', value);
				
			});	
        }
    }
</script>