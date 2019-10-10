<template>
   <div style="max-width: 100%;">
	   <iframe :src="compSrc"
	   		:allowfullscreen="allowfullscreen" 
	   		:style="css" 
	   		:width="width" 
	   		:height="height"
			:frameborder="frameborder"></iframe>
	</div>
</template>
<script>
	require('../../../net/httpquerystring');
    export default {
		name: 'Youtube',

		computed: {
			compSrc() {
				if (this.autoplay) {
					let s = HttpQueryString.setVariable(this.src, 'autoplay', '1');
					console.log('will add ap=1', s);
					return s;
				}
				//let s = HttpQueryString.setVariable(this.src, 'autoplay', 'CMD_UNSET');
				//return s;
				console.log('this.src', this.src);
				return this.src;
			}
		},

		//Атрибуты компонента
		props: {
			src: {
				type:String
			},
			//Play immediately
			autoplay: {
				type:Boolean,
				default: false
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
        //вызывается раньше чем mounted
        data: function(){return {
            //Значение email
            email:null,
            
        }; },
        //
        methods:{
            /** 
             * @description TODO Придумай имя
            */
            onSubmitLoginForm(evt) {
                
            },
           
        }, //end methods
        //вызывается после data, поля из data видны "напрямую" как this.fieldName
        mounted() {
            
        }
    }
</script>