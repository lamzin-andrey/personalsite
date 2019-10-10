<template>
	<div>
   		<img v-if="noclicked" :src="img" @click="onClickGetVideo" :video="video" :autoplay="autoplay" style="cursor:pointer" :width="width" :height="height">
		<youframe v-if="!noclicked" :src="compSrc" :autoplay="compAutoplay" 
			:allowfullscreen="allowfullscreen" 
			:width="width" 
			:height="height"
			:css="css"
			:frameborder="frameborder"></youframe>
	</div>
</template>
<script>
	require('../../../net/httpquerystring');

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
			//Run after click if 'true'
			autoplay: {
				type:String,
				default: 'true'
			},

			//Youtube iframe attributes
			allowfullscreen: {
				type:String,
				default:'allowfullscreen'
			},
			width: {
				type:Number,
				default:640
			},
			height: {
				type:Number,
				default:360
			},
			css: {
				type:String,
				default:'width: 100%;'
			},
			frameborder: {
				type:String,
				default:'0'
			}
		},

        //Called earlier than mounted
        data: function(){return {
            //Manage elements visible
            noclicked:true,
		}; },
		
		computed:{
			compSrc() {
				let vid = HttpQueryString._GET('v', null, this.video),
					sAutoplay = (this.compAutoplay ? '&autoplay=1' : '');
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
             * @description Hide image, add iframe
            */
            onClickGetVideo(evt) {
				this.noclicked = false;
            },
        }, //end methods
        //call after data(), fields from data access as this.fieldName
        mounted() {
        }
    }
</script>