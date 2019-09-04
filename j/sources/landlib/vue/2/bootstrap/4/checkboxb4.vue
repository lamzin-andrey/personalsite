<template>
	<div class="form-group mt-2">
		<div class="custom-control custom-checkbox small">
			<input 
			:value="value"
			:checked="value"
			@input="$emit('input', $event.target.checked)"
			v-b421validators="validators"
			type="checkbox" 
			:class="'custom-control-input' + (className ? (' ' + className) : '')"
			:id="id" :name="id"
			>
			<label class="custom-control-label ml-1" :for="id">
				{{ label }}
			</label>
			<div class="invalid-feedback"></div>
		</div>
	</div>
</template>
<script>
    export default {
		props: [
			'label',
			'validators',
			'id',
			'type',
			'value',
			'className'
		],
		name: 'checkboxb4',
		
        //вызывается раньше чем mounted
        data: function(){return {
            
        }; },
        //
        methods:{
            
           
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