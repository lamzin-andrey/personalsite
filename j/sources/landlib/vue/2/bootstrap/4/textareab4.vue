<template>
	<div class="mb-3 mt-2">
		<label :for="id">{{label}}</label>
		<textarea 
			:value="value"
			@input="$emit('input', $event.target.value); onInput($event);"
			:placeholder="placeholder"
			v-b421validators="validators"
			:rows="rows"
			:class="'form-control' + (className ? (' ' + className) : '')"
			:id="id" :name="id"></textarea>
		<div v-if="counter" :class="'bg-gradient-info rounded-bottom text-light text-right pr-3' + (counter && counter.className ? ('' + counter.className) : '')">{{ textLength }}</div>
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
			//if set counter showed symbol counter
			/*** @property counter {className: 'bg-success text-light'} */
			'counter',
			'rows',
			'type',
			'value',
			'className'
		],
		name: 'textareab4',
		
        //вызывается раньше чем mounted
        data: function(){return {
            textLength:0,
        }; },
        //
        methods:{
            onInput(ev) {
				this.textLength = ev.target.value.length;
				return true;
			}
           
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