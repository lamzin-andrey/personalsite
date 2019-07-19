<template>
	<div class="mb-3 mt-2">
		<label :for="id">{{label}}</label>
		<textarea 
			:value="value"
			@input="$emit('input', $event.target.value)"
			:placeholder="placeholder"
			:rows="rows"
			:class="'form-control' + (className ? (' ' + className) : '')"
			:id="id" :name="id"><slot></slot></textarea>
		<div class="invalid-feedback"></div>
		<small :id="id + 'Help'" class="form-text text-muted"></small>
	</div>
</template>
<script>
    export default {
		props: [
			'label',
			'validators',
			'id',
			'placeholder',
			'rows',
			'type',
			'value',
			'className'
		],
		name: 'textareab4',
		
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