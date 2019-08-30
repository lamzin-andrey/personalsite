<template>
	<div>
		<accordionselecttree
			id="portfolioCategoriesTree"
			:label="$t('app.Category')"
			v-model="selectedCategory"
			
			:treedata="portfolioCategoriesTree"
			:showIcons="true"
			defaultIconClass="fas fa-box"
			urlCreateNewItem="/p/portfoliocats/pcsave.jn/"
			urlUpdateItem="/p/portfoliocats/pcsave.jn/"
			urlRemoveItem="/p/portfoliocats/pcdelte.jn/"
			ref="acctree"
		></accordionselecttree>
		<!--label>CA level
			<input type="text" v-----model="selectedCategory">
		</label-->
	</div>
</template>
<script>
	//TODO перерегистрировать локально
	Vue.component('accordionselecttree', require('../../../landlib/vue/2/bootstrap/4/accordionselecttree/accordionselecttree.vue'));
	require('../../../landlib/nodom/treealg.js');
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
				if (n != old) {
					this.selectedCategory = this.value;
				}
			}
		},

		name: 'categorytree',
		
        //вызывается раньше чем mounted
        data: function(){return {
			selectedCategory : 0,
			/** @property {Array} данные дерева категорий */
            portfolioCategoriesTree: [{"id": 2222, "name": "Zevs", "children": [{"id": 3333, "parent_id" : 2222, "name": "Neptune"}, {"id": 4444, "parent_id" : 2222, "name": "Stratus"} ] } ],
		}; },
		computed: {},
        //
        methods:{
			/**
			 * @description Возвращает массив имён категорий от корня к категории с id = nId
			 * @param {Number nId}
			*/
			getBranchNames(nId) {
				let arr = TreeAlgorithms.getNodesByNodeId(this.portfolioCategoriesTree[0], nId);//TODO treeData
				arr = arr.map((el) =>{
					return TextFormat.transliteUrl(el.name);
				});
				return arr;
			}
        }, //end methods
        //вызывается после data, поля из data видны "напрямую" как this.fieldName
        mounted() {
			//parse data
			let data = $('#pcategorydata').val();
			//this.selectedCategory = this.value;
			try {
				data = JSON.parse(data);
				data = TreeAlgorithms.buildTreeFromFlatList(data.portfolioCategories, true);
				this.portfolioCategoriesTree[0] = data[0];

				Vue.nextTick(() =>{
					this.selectedCategory = 0;//TODO  Использовать тик
					this.$refs.acctree.selectNodeById(this.selectedCategory);
				});
			} catch(e){}

			this.$refs.acctree.$on('input', (value) => {
				this.selectedCategory = value;
				this.$emit('input', value);
				
			});	
        }
    }
</script>