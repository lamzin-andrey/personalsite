<template>
	<div class="form-group mt-2">
		<label :for="id">{{ label }}</label>
		<select  
			:class="'custom-select' + (className ? (' ' + className) : '')"
			:value="value"
			@input="$emit('input', $event.target.value)"
			:id="id" :name="id"
		>
			<option v-for="item in data" :value="item.id">{{ item.name }}</option>
		</select>
		<div class="invalid-feedback"></div>
	</div>

</template>
<script>
    export default {
		props: [
			'label',
			'data',
			'id',
			'value',
			'className'
		],
		name: 'selectb4',
		
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