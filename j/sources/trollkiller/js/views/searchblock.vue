<template>
	<div class="" v-if="isVisibleSearchBlockInput">
		<div>
			<div class="input-group mb-3">
				<input ref="iSearch" @keyup="onClickFindNow" type="search" class="form-control" :placeholder="$t('app.Search_Tk')" :aria-label="$t('app.Search_Tk')" aria-describedby="button-addon2">
				<div class="input-group-append">
					<button @click="onClickFindNow" class="btn btn-primary" type="button" id="button-addon2">{{ $t('app.FindNow')}}</button>
				</div>
			</div>
		</div>
		<div id="tkPreloaderPlaceWrap" style="display:none;">
			<div id="tkPreloaderPlace"></div>
		</div>
		<div class="p-4" v-if="isVisible">
			<div v-if="!isListNoEmpty" class="alert alert-success">
				<p>Нет результатов.</p>
				<p>Для поиска троллеборца введите его id в сервисе ответы@mail.ru.
					Видеть id пользователя  в его профиле сервиса ответы@mail.ru можно в адресной строке браузера
						<a data-toggle="collapse" href="#aidExample" role="button" aria-expanded="false" aria-controls="collapseExample">
							здесь
						</a>
						</p>
						<div class="collapse" id="aidExample">
							<img src="/i/aid.jpg" style="max-width:100%;">
						</div>
				<p class="mt-2">
					Вы можете в любой момент перестать использовать чужой опыт, нажав на ссылку &laquo;Управление&raquo;
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
								<div>
									<a :href="'/portfolio/web/userscripts/trollkiller/user/' + user.subject_id + '/'">Список забаненых троллей</a>
								</div>
								<div>
									<button
										:data-id="user.subject_id"
										:data-is_subscribed="($root.intval(user.is_subsrcribed) > 0 ? '1' : '0')"
										class="btn btn-success"
										@click="onClickUnsubscribeInList">{{ ($root.intval(user.is_subsrcribed) > 0 ? $t('app.Unsubscribe') : $t('app.Subscribe') ) }}</button>
								</div>
								
							</div>
								<div class="clearfix"></div>
							</div>
						</div>
					</li>
				</ul>
			</div>
		</div>
		
	</div>
</template>
<script>
	import TKPreloader  from '../preloader';

    export default {
        name: 'searchBlock',
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
			},
			isVisibleSearchBlockInput() {
				if (~location.href.indexOf('/userscripts/trollkiller/list/')) {
					return true;
				}
				return false;
			}
		},
        //вызывается раньше чем mounted
        data: function(){return {
			/** @property {Boolean} isListNoEmpty принимает true когда список на кого пользователь подписан не пуст*/
			isListNoEmpty : false,

			/** @property {Boolean} isVisible отвечает за отображение панели управления*/
			isVisible : false,

			/** @property {Array} aRels список пользователей, на которых подписан авторизованный */
			aRels : []
        }; },
        //
        methods:{
            /**
             * @description TODO доделать для непустого списка
             */
            setList(list) {
				this.isListNoEmpty = (list.length > 0);
				this.isVisible = true;
				this.aRels = list;
			},
			onClickClosePanel(){
				this.isVisible = false;
				location.hash = '';
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
			/**
             * @description В отличии от кнопки в блоке "Управление" можно и подписываться и отписываться
			 * @param {Event} evt
            */
			onClickUnsubscribeInList(evt) {
				let id = parseInt($(evt.target).attr('data-id')),
					isSubscribed = parseInt($(evt.target).attr('data-is_subscribed'));
				if (id) {
					if (isSubscribed) {
						Rest._post({n: id}, (dt) => { this.onSuccessDelRel(dt); }, '/p/trollkiller/delrel.jn/', (a, b, c) =>{this.$root.defaultFailSendFormListener(a, b, c)});
					} else {
						Rest._post({n: id}, (dt) => { this.onSuccessAddRel(dt); }, '/p/trollkiller/addrel.jn/', (a, b, c) =>{this.$root.defaultFailSendFormListener(a, b, c)});
					}
				}
			},
			/**
             * @description Обработка отписки от блэклиста пользователя
			 * @param {Event} evt
            */
			onSuccessDelRel(data) {
				if (!this.$root.onSuccessDelRel(data)) {
					return;
				}
				let n = this.$root.intval(data.n), btn;
				if (n) {
					btn = $('button[data-id=' + n + ']').first();
					btn.attr('data-is_subscribed', '0');
					btn.text(this.$root.$t('app.Subscribe'));
				}
			},
			/**
             * @description Обработка подписки на блэклист пользователя
			 * @param {Event} evt
            */
			onSuccessAddRel(data) {
				if (!this.$root.onSuccessAddRel(data)) {
					return;
				}
				let n = this.$root.intval(data.n), btn;
				if (n) {
					btn = $('button[data-id=' + n + ']').first();
					btn.attr('data-is_subscribed', '1');
					btn.text(this.$root.$t('app.Unsubscribe'));
				}
			},
			/**
             * @description Клик на кнопке "Поиск"
			 * @param {Event} evt 
            */
			onClickFindNow(evt){
				if (evt.key && evt.keyCode != 13) {
					return;
				}
				let t = this.$refs.iSearch.value;
				if (t) {
					this.setList([]);
					this.isListNoEmpty = true;
					$('#tkPreloaderPlaceWrap')[0].style.display = null;
					Rest._post({t: t}, (dt) => { this.onSuccessFindResults(dt); }, '/p/trollkiller/search.jn/', (a, b, c) =>{$('#tkPreloaderPlaceWrap')[0].style.display = 'none'; this.$root.defaultFailSendFormListener(a, b, c)});
				}
			},
			/**
             * @description Обработка успешных результтатов поиска
            */
			onSuccessFindResults(data) {
				$('#tkPreloaderPlaceWrap')[0].style.display = 'none';
				if (!this.$root.defaultFailSendFormListener(data)) {
					return false;
				}
				this.setList(data.list);
			}
        }, //end methods
        //вызывается после data, поля из data видны "напрямую" как this.fieldName
        mounted() {
			this.spinner = new TKPreloader('#tkPreloaderPlace', '#tkPreloaderPlaceWrap', null);
			this.spinner.configure(true, false);
			this.spinner.watch();
        }
    }
</script>