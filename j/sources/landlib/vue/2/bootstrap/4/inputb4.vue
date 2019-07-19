<template>
	<div class="mt-2">
		<label :for="id">{{ label }}</label>
		<div class="input-group">
			<input
				:value="value"
				@input="$emit('input', $event.target.value)"
				:placeholder="placeholder"
				v-b421validators="validators"
				:class="'form-control' + (className ? (' ' + className) : '')"
				:aria-describedby="id + 'Help'"
				:type="type"     :id="id" :name="id">
			<div class="invalid-feedback"></div>
			<small :id="id + 'Help'" class="form-text text-muted"></small>
		</div>
	</div>
</template>
<script>
    export default {
		props: [
			'label',
			'validators',
			'id',
			'placeholder',
			'type',
			'value',
			'className'
		],
		name: 'inputb4',
		
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