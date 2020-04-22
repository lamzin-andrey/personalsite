<template>
    <form class="user" method="POST" action="/p/portfolio/psave.jn/" @submit="onSubmit" novalidate id="kxmadminform" >
		<div v-if="token.length != 0">
			<textareab4 v-model="body" ref="questbody" @input="setDataChanges" :counter="counter" :label="$t('app.body')"  id="body" rows="3" validators="'required'" ></textareab4>
			<textareab4 v-model="var1" ref="var1" @input="setDataChanges" :counter="counter" :label="$t('app.var1')"  id="var1" rows="3" validators="'required'"></textareab4>
			<textareab4 v-model="var2" ref="var2" @input="setDataChanges" :counter="counter" :label="$t('app.var2')"  id="var2" rows="3" validators="'required'"></textareab4>
			<textareab4 v-model="var3" ref="var3" @input="setDataChanges" :counter="counter" :label="$t('app.var3')"  id="var3" rows="3" validators="'required'"></textareab4>
			<textareab4 v-model="var4" ref="var4" @input="setDataChanges" :counter="counter" :label="$t('app.var4')"  id="var4" rows="3" validators="'required'"></textareab4>
			<inputb4 v-model="var_right" ref="varright" @input="setDataChanges" :counter="counter" :label="$t('app.varright')"  type="number" id="varright" rows="3" validators="'required'"></inputb4>
			<inputb4 v-model="price" ref="price" @input="setDataChanges" :counter="counter" :label="$t('app.price')"  type="number" id="price" rows="3" validators="'required'"></inputb4>

			<div class="float-right ">
			<div id="Saver" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="3000">
				<div class="toast-header">
					<strong class="mr-auto">Info</strong>
					<small class="text-muted"></small>
					<button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="toast-body">
					{{ $t('app.SaveCompleted') }}
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
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
				var_right:1,
				
				//Идентификатор редактируемого вопроса
				id : 0,
				
				//Чтобы передать в textareab4 true пришлось определить
				counter: true,
				
				/** @property {Boolean} hideFromProductlist Скрывать ли на странице портфолио  */
				hideFromProductlist : false,

				/** @property {String} csrf token  */
				token: ''
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
			setQuestData(data) {
				this.var1 = '';
				this.var2 = '';
				this.var3 = '';
				this.var4 = '';
				this.body = '';
				this.price = 0;
				this.var_right = 0;
				//Идентификатор редактируемого вопроса
				this.id = 0;
				this.hideFromProductlist = false;
				
				//Fix bug when edit the article more then one time...
				Vue.nextTick(() => {
					this.var1 = data.var1;
					this.var2 = data.var2;
					this.var3 = data.var3;
					this.var4 = data.var4;
					this.body = data.body;
					this.price = data.price;
					this.var_right = data.var_right;
					//Идентификатор редактируемого вопроса
					this.id = data.id;
					//this.hideFromProductlist = data.is_hidden;
				});
			},
			/** 
			 * @param {Number} nId
			*/
			setId(nId) {
				this.id = nId;
			},
			/**
			 * @description уведомляем приложение, что данные изменились
			 */
			setDataChanges() {
				//this.$root.$refs.kxmadmin.setDataChanges(true);
			},
            /** 
             * @description Пробуем отправить форму
            */
            onSubmit(evt) {
                evt.preventDefault();
                if (this.allRequiredFilled()) {
					let formInputValidator = this.$root.formInputValidator;
					Rest._post(
                        this.getWrappedData(),	//wrap fields
                        (data) => { this.onSuccessSaveQuest(data, formInputValidator);},
                        this.$webRoot + '/kxmquestsave.json',
						(a, b, c) => { this.onFailSaveQuest(a, b, c);},
						true
                    );
				}
			},
			/**
			 * @description Оборачивает поля формы в префикс формы, чтобы Symfony могло стандартно обработать эту форму
			 * @return {Object} _data;
			*/
			getWrappedData() {
				let i, _data = {},
				
				exclude = {
					counter: 1,
					token: 1,
					hideFromProductlist: 1
				};

				for (i in this.$data) {
					if (! (i in exclude) ) {
						_data[this.tokenPrefix + '[' + i + ']'] = this.$data[i];
					}
				}
				/** @property {String} tokenPrefix имя массива формы, в котором должны быть переданы все поля */
				_data[this.tokenPrefix + '[_token]'] = this.token;
				return _data;
			},
			/**
			 * @description Успешное сохранение вопроса
			*/
			onSuccessSaveQuest(data, formInputValidator){
				if (!this.onFailSaveQuest(data)) {
					return;
				}
				let id = parseInt(data.id);
				if (data.status == 'ok' && id) {
					this.id = id;
					this.$root.$refs.kxmadmin.setQuestId(id);
					$('#Saver').toast('show');
					this.$root.$refs.kxmadmin.setDataChanges(false);
					this.$root.$refs.kxmadmin.dataTable.search('').draw();
				}
			},
			/**
			 * @description Неуспешное добавление вопроса
			 * @return Boolean false если существует data.status == 'error'
			*/
			onFailSaveQuest(data, b, c){
				return this.$root.defaultFailSendFormListener(data,b, c);
			},
			/**
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
					parseInt(this.var_right) > 0
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
			 * @param {String} sToken Значение токена
			 * @param {String} sTokenPrefix Значение префикса токена (все поля формы будут помещены в массив с этим именем)
			 * 
			*/
			setFormToken(sToken, sTokenPrefix) {
				this.token = sToken;
				Rest._token = this.token;
				this.tokenPrefix = sTokenPrefix;
			}
        }, //end methods
        //вызывается после data, поля из data видны "напрямую" как this.fieldName
        mounted() {
			//Запрос данных наименований статей

			//TODO with REST this.$root._get((data)=>{this.onSuccessGetArticlelist(data);}, '/p/articleslist.jn/?draw=1&start=0&length=1000000', (a, b, c )=>{ this.$root.defaultFailSendFormListener(a, b, c) });
            
        }
    }
</script>