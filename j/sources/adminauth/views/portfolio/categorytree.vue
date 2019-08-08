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
				setTimeout(() =>{
					this.selectedCategory = 2;//TODO получить категорию работы с сервера в данных. Использовать тик. Попробовать разобраться с реактивностью
					this.$refs.acctree.selectNodeById(this.selectedCategory);
				}, 500);
			} catch(e){}

			this.$refs.acctree.$on('input', (value) => {
				this.selectedCategory = value;
				this.$emit('input', value);
				
			});	
        }
    }
</script>