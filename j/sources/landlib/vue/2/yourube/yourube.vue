<template>
	<div style="position:relative">
 		<img v-if="noclicked" :src="img" :video="video" :autoplay="autoplay"  :width="width" :height="height" :class="imageclass" ref="bgImg">
 		<img v-if="noclicked" src="/i/rubtn.jpg" @click="onClickGetVideo('ru')" :video="ruvideo" :autoplay="autoplay" :style="rucss" >
 		<img v-if="noclicked" src="/i/youbtn.jpg" @click="onClickGetVideo('you')" :video="youvideo" :autoplay="autoplay" :style="youcss" >
		<yoruframe v-if="!noclicked" :src="compSrc" 
			:allowfullscreen="allowfullscreen" 
			:width="width" 
			:height="height"
			:css="css"
      :rutube="chooseRu"
			:frameborder="frameborder"></yoruframe>
	</div>
</template>
<script>
    require('../../../net/httpquerystring');
    export default {
		name: 'YourubeIframeVideo',

		components:{
			yoruframe: require('./yoruframe.vue').default
		},
		
		//Component attributes
		props:{
			//Link to youtube page with video
			ruvideo: {
				type:String,
        require:true
			},
      youvideo: {
				type:String,
        require:true
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
        noclicked: true,
        chooseRu:  false,
        chooseYou: false,
        rucss: '',
        youcss: '',
    }; },
		
		computed:{
			compSrc() {
        let vid, s, sAutoplay;
        if (this.chooseRu) {
				  vid = this.parseUrl();
				  s =  `https://rutube.ru/play/embed/${vid}`;
        } else {
          vid = HttpQueryString._GET('v', null, this.youvideo),
					sAutoplay = (this.compAutoplay ? '&autoplay=1' : '');
				  s =  `https://www.youtube.com/embed/${vid}?ecver=2${sAutoplay}`;
        }
        

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
            onClickGetVideo(type) {
              if (type == 'you') {
                this.chooseRu = false;
                this.chooseYou = true;
              } else {
                this.chooseRu = true;
                this.chooseYou = false;
              }
				      this.noclicked = false;
            },
            parseUrl() {
              let s = this.ruvideo, a;
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
          let ruX, ruY, youX, youY, spaceY = 30, btnW = 142, btnH = 40, h, shCss, w;
          w = this.$refs.bgImg.offsetWidth;
          if (!w) {
            w = this.width;
          }
          ruX = (w - btnW) / 2;
          youX = ruX;
          h = 2 * btnH + spaceY;
          ruY = (this.height - h) / 2;
          console.log('ruY', ruY);
          youY = ruY + btnH + spaceY;

          shCss = `cursor:pointer;position:absolute;`;
          this.rucss = `${shCss}left:${ruX}px;top:${ruY}px;`;
          this.youcss = `${shCss}left:${youX}px;top:${youY}px;`;
        }
    }
</script>
<style lang="css" scoped>
.rel {
  position:relative;
}
</style>

