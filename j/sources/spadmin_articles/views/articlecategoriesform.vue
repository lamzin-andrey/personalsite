<template>
    <form class="user" method="POST" action="/p/acategories/savearticle.jn/" @submit="onSubmit" novalidate id="ariclecategoriesform">
		<inputb4 v-model="category_name" @input="setDataChanges" type="text" :placeholder="$t('app.Category')" :label="$t('app.Category')" id="category" validators="'required'"></inputb4>
        
		<div class="float-right ">
			<div id="categorySaver" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="3000">
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
        
    </form>

</template>
<script>
    export default {
        name: 'articlecategoryform',
        //вызывается раньше чем mounted
        data: function(){
			let _data  = {
				//Значение category_name
				category_name:'',
				//Идентификатор редактируемой категории
				id : 0,
			};
			return _data;
		},
        //
        methods:{
			/**
			 * @description Установить данные категории статьи для редактирования
			 * @param {Object} data @see mysql table fields pages_categories
			*/
			setCategoryData(data) {
				this.category_name = 'a';
				
				//Fix bug when edit the article more then one time...
				setTimeout(() => {
					this.category_name = data.category_name;
					console.log('I set');
				}, 1);
				
			},
			/**
			 * @description уведомляем приложение, что данные изменились
			 */
			setDataChanges() {
				this.$root.$refs.articlecategories.setDataChanges(true);
			},
            /** 
             * @description Пробуем отправить форму
            */
            onSubmit(evt) {
                evt.preventDefault();
                if (this.allRequiredFilled()) {
					let formInputValidator = this.$root.formInputValidator;
					this.id = this.$root.$refs.articlecategories.getCategoryId();
                    this.$root._post(
                        this.$data,
                        (data) => { this.onSuccessAddCategory(data, formInputValidator);},
                        '/p/acategories/categorysave.jn/',
                        (a, b, c) => { this.onFailAddCategory(a, b, c);}
                    );
                }
			},
			/**
			 * @description Успешное добавление категории статьи
			*/
			onSuccessAddCategory(data, formInputValidator){
				if (!this.onFailAddCategory(data)) {
					return;
				}
				let id = parseInt(data.id);
				if (data.status == 'ok' && id) {
					this.$root.$refs.articlecategories.setCategoryId(id);
					$('#categorySaver').toast('show');
					this.$root.$refs.articlecategories.setDataChanges(false);
				}
			},
			/**
			 * @description Неуспешное добавление категории статьи
			 * @return Boolean false если существует data.status == 'error'
			*/
			onFailAddCategory(data, b, c){
				return this.$root.defaultFailSendFormListener(data,b, c);
				
			},
			/**
             * @description Проверяет, заполнены ли все необходимые поля
            */
			allRequiredFilled(){
				return (
					String(this.category_name).length > 0
				);
			},
        }, //end methods
        //вызывается после data, поля из data видны "напрямую" как this.fieldName
        mounted() {

            var self = this;
            /*this.$root.$on('showMenuEvent', function(evt) {
                self.menuBlockVisible   = 'block';
                self.isMainMenuVisible  = true;
                self.isScrollWndVisible = false;
                self.isColorWndVisible  = false;
                self.isHelpWndVisible   = false;
                self.nStep = self.$root.nStep;
            })/**/
            //console.log('I mounted!');
        }
    }
</script>