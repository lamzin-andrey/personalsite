<template>
	<div>
   		<img v-if="noclicked" :src="img" @click="onClickGetVideo" :video="video" :autoplay="autoplay" style="cursor:pointer">
		<youframe ref="iframe" v-if="!noclicked" :src="compSrc" :autoplay="compAutoplay"></youframe>
	</div>
</template>
<script>
	require('../../../net/httpquerystring');

	//TODO добавить все опции iframe из примера
    export default {
		name: 'YoutubeIframeVideo',

		components:{
			youframe: require('./youframe.vue')
		},
		
		//Component attributes
		props:{
			//Link to youtube page with video
			video: {
				type:String
			},
			//Link to patch image (usually photo youtube first frame)
			img: {
				type:String,
				require:true
			},
			//Run after click
			autoplay: {
				type:String,
				default: false
			}
		},

        //вызывается раньше чем mounted
        data: function(){return {
            //Отвечает за видимость элементов
            noclicked:true,
		}; },
		
		computed:{
			compSrc() {
				let vid = HttpQueryString._GET('v', null, this.video),
					sAutoplay = (this.autoplay ? '&autoplay=1' : '');
				return `https://www.youtube.com/embed/${vid}?ecver=2${sAutoplay}`;
			},
			compAutoplay() {
				if (this.autoplay == 'false') {
					return false;
				}
				if (this.autoplay) {
					return true;
				}
				return false;
			}
		},

        //
        methods:{
            /** 
             * @description Пробуем отправить форму
            */
            onClickGetVideo(evt) {
				this.noclicked = false;
            },
        }, //end methods
        //вызывается после data, поля из data видны "напрямую" как this.fieldName
        mounted() {
        }
    }
</script>