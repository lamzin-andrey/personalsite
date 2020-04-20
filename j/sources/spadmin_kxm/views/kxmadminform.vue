<template>
    <form class="user" method="POST" action="/p/portfolio/psave.jn/" @submit="onSubmit" novalidate id="kxmadminform" >
		<div v-if="token.length != 0">
			<textareab4 v-model="body" ref="questbody" @input="setDataChanges" :counter="counter" :label="$t('app.body')"  id="body" rows="3" validators="'required'" ></textareab4>
			<textareab4 v-model="var1" ref="var1" @input="setDataChanges" :counter="counter" :label="$t('app.var1')"  id="var1" rows="3" validators="'required'"></textareab4>
			<textareab4 v-model="var2" ref="var2" @input="setDataChanges" :counter="counter" :label="$t('app.var2')"  id="var2" rows="3" validators="'required'"></textareab4>
			<textareab4 v-model="var3" ref="var3" @input="setDataChanges" :counter="counter" :label="$t('app.var3')"  id="var3" rows="3" validators="'required'"></textareab4>
			<textareab4 v-model="var4" ref="var4" @input="setDataChanges" :counter="counter" :label="$t('app.var4')"  id="var4" rows="3" validators="'required'"></textareab4>
			<inputb4 v-model="varright" ref="varright" @input="setDataChanges" :counter="counter" :label="$t('app.varright')"  type="number" id="varright" rows="3" validators="'required'"></inputb4>
			<inputb4 v-model="price" ref="price" @input="setDataChanges" :counter="counter" :label="$t('app.price')"  type="number" id="price" rows="3" validators="'required'"></inputb4>

	        <p class="text-right my-3">
	            <button  class="btn btn-primary">{{ $t('app.Save') }}</button>
	        </p>
		</div>
        
    </form>

</template>
<script>
	

    
    export default {
		name: 'kxmadminform',
		
		components: {
			'textareab4': require('../../landlib/vue/2/bootstrap/4/textareab4').default,
			'inputb4': require('../../landlib/vue/2/bootstrap/4/inputb4').default
		},

        //вызывается раньше чем mounted
        data: function(){
			let _data  = {
				//Вариант ответа
				var1: '',
				
				//Вариант ответа
				var2: '',
				
				//Вариант ответа
				var3: '',
				
				//Вариант ответа
				var4: '',
				
				//Текст вопроса
				body:'',
				
				//Стоимость вопроса
				price: 500,
				
				//Правильный вариант
				varright:'',
				
				//Идентификатор редактируемого вопроса
				id : 0,
				
				//Чтобы передать в textareab4 true пришлось определить
				counter: true,
				
				/** @property {Boolean} hideFromProductlist Скрывать ли на странице портфолио  */
				hideFromProductlist : false,

				/** @property {Boolean} hideFromProductlist Скрывать ли на странице портфолио  */
				token : ''
			};
			return _data;
		},
		watch: {
			
		},
		computed:{
			
		},
        //
        methods:{
			/**
			 * @description Установитрь данные статьи для редактирования
			 * @param {Object} data @see mysql table fields pages
			*/
			setProductData(data) {
				this.var1 = '';
				this.var2 = '';
				this.var3 = '';
				this.var4 = '';
				this.body = '';
				this.price = 0;
				this.varright = 0;
				//Идентификатор редактируемого вопроса
				this.id = 0;
				this.hideFromProductlist = false;
				
				//Fix bug when edit the article more then one time...
				Vue.nextTick(() => {
					this.var1 = '';
					this.var2 = '';
					this.var3 = '';
					this.var4 = '';
					this.body = '';
					this.price = 0;
					this.varright = 0;
					//Идентификатор редактируемого вопроса
					this.id = 0;
					this.hideFromProductlist = false;
				});
			},
			/**
			 * @description уведомляем приложение, что данные изменились
			 */
			setDataChanges() {
				//this.$root.$refs.portfolio.setDataChanges(true);
			},
            /** 
             * @description Пробуем отправить форму
            */
            onSubmit(evt) {
                evt.preventDefault();
                if (this.allRequiredFilled()) {
					let formInputValidator = this.$root.formInputValidator;
					this.id = this.$root.$refs.portfolio.getProductId();
					this.url = $('#url').val();
					if (!this._validateSga256Inputs(formInputValidator)) {
						return false;
					}
					this.relatedArticles = JSON.stringify(this.tags);
                    this.$root._post(
                        this.$data,
                        (data) => { this.onSuccessAddProduct(data, formInputValidator);},
                        '/p/portfolio/psave.jn/',
                        (a, b, c) => { this.onFailAddProduct(a, b, c);}
                    );
                }
			},
			/**
			 * @description Успешное добавление статьи
			*/
			onSuccessAddProduct(data, formInputValidator){
				if (!this.onFailAddProduct(data)) {
					return;
				}
				let id = parseInt(data.id);
				if (data.status == 'ok' && id) {
					this.$root.$refs.portfolio.setProductId(id);
					$('#portfolioSaver').toast('show');
					this.$root.$refs.portfolio.setDataChanges(false);
					this.$root.$refs.portfolio.dataTable.search('').draw();
				}
			},
			/**
			 * @description Неуспешное добавление статьи
			 * @return Boolean false если существует data.status == 'error'
			*/
			onFailAddProduct(data, b, c){
				return this.$root.defaultFailSendFormListener(data,b, c);
			},
			/**
			 * TODO
             * @description Проверяет, заполнены ли все необходимые поля
            */
			allRequiredFilled(){
				return (
					this.var1.length > 0 &&
					this.var2.length > 0 &&
					this.var3.length > 0 &&
					this.var4.length > 0 &&
					this.body.length > 0 &&
					parseInt(this.price) > 0 &&
					parseInt(this.varright) > 0
				);
			},
			/**
			 * @description Получение данных о существующих статьях
			*/
			onSuccessGetArticlelist(data) {
				let i;
				this.autocompleteItems = [];
				for (i = 0; i < data.data.length; i++) {
					data.data[i].text = data.data[i].heading;
					delete data.data[i].heading;
					this.autocompleteItems.push(data.data[i]);
				}
				this.setRelatedArticles();
			},
			/**
			 * @param {String} sToken
			*/
			setFormToken(sToken) {
				this.token = sToken;
			}
        }, //end methods
        //вызывается после data, поля из data видны "напрямую" как this.fieldName
        mounted() {
			//Запрос данных наименований статей

			//TODO with REST this.$root._get((data)=>{this.onSuccessGetArticlelist(data);}, '/p/articleslist.jn/?draw=1&start=0&length=1000000', (a, b, c )=>{ this.$root.defaultFailSendFormListener(a, b, c) });
            
        }
    }
</script>