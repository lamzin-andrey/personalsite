<template>
	<div>
 		<img v-if="noclicked" :src="img" @click="onClickGetVideo" :video="video" :autoplay="autoplay" style="cursor:pointer" :width="width" :height="height" :class="imageclass">
		<ruframe v-if="!noclicked" :src="compSrc" 
			:allowfullscreen="allowfullscreen" 
			:width="width" 
			:height="height"
			:css="css"
			:frameborder="frameborder"></ruframe>
	</div>
</template>
<script>
    export default {
		name: 'RutubeIframeVideo',

		components:{
			ruframe: require('./ruframe.vue').default
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
      // img.class calue
      imageclass: {
        type:String,
				default: 'mw-100'
      },
			//Rutube iframe attributes
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
				let vid = this.parseUrl(); // TODO
				let s =  `https://rutube.ru/play/embed/${vid}`;
				return s;
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
            parseUrl() {
              let s = this.video, a;
              s = s.split('?')[0];
              a = s.split('/video/')
              if (a[1]) {
                s = a[1].split('/')[0]
                return s;
              }

              return '#notfound';
            }
            
        }, //end methods
        //call after data(), fields from data access as this.fieldName
        mounted() {
        }
    }
</script>