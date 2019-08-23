<template>
	<div class="border border-success mb-5" v-if="isVisible">
		<div class="bg-primary">
			<div class="float-left text-light ml-3">
				<h6 class="pt-2" id="ktpan">Панель управления TrollKiller</h6>
			</div>
			<div class="float-right">
				<button @click="onClickClosePanel" class="btn btn-danger">
					<span >&times;</span>
				</button>
				
			</div>
			<div class="clearfix"></div>
		</div>
		<div class="p-4" v-if="isVisible">
			<div v-if="!isListNoEmpty" class="alert alert-success">
				<p>Вы ещё не используете опыт ни одного из пользователей сайта.</p>
				<p>Для того, чтобы сделать это, вы можете пройти по ссылке 
					&laquo;Список забаненых троллей&raquo; на странице
					<a href="/portfolio/web/userscripts/trollkiller/list/">Топ-10</a> 
				</p>
				<p>
					Если вам нравится, кого банит тот или иной троллеборец &copy;.
					вы можете  нажать на кнопку &laquo;Использовать этот опыт&raquo;.
				</p>
				<p>
					Тогда при использовании <a href="/portfolio/web/userscripts/trollkiller/">TrollKiller</a>
					вы не будете видеть в своеё ленте вопросов троллей, забаненых пользователем, опыт которого вы используете.
				</p>
				<p>
					Вы сможете в любой момент перестать использовать чужой опыт, нажав на ссылку &laquo;Управление&raquo;
					в <a @click="onClickClosePanel" href="#">главном меню TrollKiller</a> и отписавшись от дальнейшего использования.
				</p>
			</div>
			<div>
				<ul class="list-unstyled">
					<li v-for="user in aNormalizedRels" class="w100 border-bottom pb-2 mb-2"> 
						<div class="media">
							<img :src="user.imgpath" class="f-left d-inline-block mr-2 rounded-circle" style="max-width: 45px; max-height: 45px;">
							<div class="media-body">
								<div class="d-inline-block mr-2 float-left"><div>{{ user.name }}</div>
								<div>
									<span v-if="user.cnt > 5" class="badge badge-light">{{ user.cnt }}</span>
									<img v-for="s in user.imgrating" :src="'/i/troll16' + s + '.jpg'">
									<!--img src="/i/troll16[g].jpg"-->
								</div>
							</div>
							<div class="float-right d-inline-block mr-2">
								<a :href="'/portfolio/web/userscripts/trollkiller/user/' + user.uid + '/'">Список забаненых троллей</a>
							</div>
								<div class="clearfix"></div>
							</div>
						</div>
					</li>
				</ul>
			</div>

			<div class="text-center">
				<button @click="onClickClosePanel" class="btn btn-primary">
						Закрыть панель TrollKIller
				</button>
				<button @click="onClickLogout" class="btn btn-info">
						Выйти
				</button>
			</div>
		</div>
		
	</div>
</template>
<script>
    export default {
        name: 'relsListBlock',
        //Аргументы извне
        props:[
		],
		computed: {
			aNormalizedRels () {
				return this.aRels.map((element) => {
					if (!element.name) {
						element.name = element.dname + ' ' + element.surname;
						element.name = element.name.trim();
					}
					let rating = element.cnt, i;
					element.imgrating = [];
					for (i = 0; i < 5; i++) {
						if (i < rating) {
							element.imgrating.push('');
						} else {
							element.imgrating.push('g');
						}
					}
					return element;
				})
			}
		},
        //вызывается раньше чем mounted
        data: function(){return {
			/** @property {Boolean} isListNoEmpty принимает true когда список на кого пользователь подписан не пуст*/
			isListNoEmpty : false,

			/** @property {Boolean} isVisible отвечает за отображение панели управления*/
			isVisible : false,

			/** @property {Array} aRels список пользователей, на которых подписан авторизованный */
			aRels : [],
        }; },
        //
        methods:{
            /**
             * @description TODO доделать для непустого списка
             */
            setList(list) {
				this.isListNoEmpty = (list.length > 0);
				this.aRels = list;
				console.log(this.aRels);
			},
			onClickClosePanel(){
				this.isVisible = false;
				location.hash = '';
			},
			onClickLogout() {
				Rest._get((data)=>{this.$root.onSuccessAuthStatus(data);}, '/p/trollkiller/logout.jn/', (a, b, c) => {this.$root.defaultFailSendFormListener(a, b, c);});
			},
			/**
             * @description
             */
            toggleIsVisible() {
                this.isVisible = !this.isVisible;
            },
			/**
             * @description TODO доделать для непустого списка
			 * @param {Boolean} bVisible
             */
            setIsVisible(bVisible) {
                this.isVisible = bVisible;
			},
        }, //end methods
        //вызывается после data, поля из data видны "напрямую" как this.fieldName
        mounted() {
        }
    }
</script>