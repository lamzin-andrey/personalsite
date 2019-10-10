<template>
   <div style="max-width: 100%;">
	   <iframe :src="src"
	   		:allowfullscreen="allowfullscreen" 
	   		:style="css" 
	   		:width="width" 
	   		:height="height"
			:frameborder="frameborder"></iframe>
	</div>
</template>
<script>
    export default {
		name: 'YoutubeIframe',

		//Component attrtibutes
		props: {
			src: {
				type:String,
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
        //Called earlier than mounted
        data: function(){return {
        }; },
        //
        methods:{
           
        }, //end methods
        //call after data(), fields from data access as this.fieldName
        mounted() {
            
        }
    }
</script>