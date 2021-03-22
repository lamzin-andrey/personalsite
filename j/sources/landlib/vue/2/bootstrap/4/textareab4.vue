<template>
	<div class="mb-3 mt-2">
		<label :for="id">{{label}}</label>
		<textarea 
			:value="value"
			@input="$emit('input', $event.target.value); onInput($event);"
			@keydown="$emit('keydown', $event);"
			@keyup="$emit('keyup', $event);"
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
		model: {
			prop: 'value',
			event: 'input'
		},
		props: [
			'label',
			'validators',
			'id',
			'placeholder',
			'maxlength',
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
			},
			/**
			 * @description Установка позиции курсора в текстовом поле
			 * @param {Number} pos
			**/
			setCursorPosition(pos)  {
				let input = $('#' + this.id)[0], f = 0;
				if (input.readOnly) return;	
				if (input.value == "") return;	
				if ((!pos)&&(pos !== 0)) return;
				
				try {
					f = input.setSelectionRange;
				} catch(e){
					;
				}
				if(f){
					input.focus();		
					try{
						input.setSelectionRange(pos,pos);
					}catch(e){
						//если находится в контейнере с style="display:none" выдает ошибку
					}
				}else if (input.createTextRange) {
					var range = input.createTextRange();
					range.collapse(true);
					range.moveEnd('character', pos);
					range.moveStart('character', pos);
					range.select();
				}
			},
			focus() {
				$('#' + this.id).focus();
			},
			/**
			 * 
			**/
			/**
			 * @description En: get caret (cursor) position in textarea.
			 *  Ru: Получение позиции курсора в текстовом поле
			 * @return Number
			*/
			getCursorPosition() {
				let input = $('#' + this.id)[0],
					pos = 0;
				if (!input) {
					return -1;
				}
				// IE Support
				if (document.selection) {		
					if (input.value.length == 0) return 0;
					ta.focus();
					var sel = document.selection.createRange();
					var clone  = sel.duplicate();
					sel.collapse(true);
					clone.moveToElementText(ta);
					clone.setEndPoint('EndToEnd', sel);
					return (clone.text.length);
				}
				// Firefox support
				else if (input.selectionStart || input.selectionStart == '0'){
					pos = input.selectionStart;		
				}
				return pos;
			},
			/**
			 * @description En: Alias for get caret position. Ru: Псевдоним для getCursorPosition.
			 * @return Number
			*/
			getCaretPosition(){
				return this.getCursorPosition();
			},
			/**
			 * @description En: Alias for set caret position. Ru: Псевдоним для setCursorPosition.
			 * @return Number
			*/
			setCaretPosition(n){
				return this.setCursorPosition(n);
			}
           
        }, //end methods
        //вызывается после data, поля из data видны "напрямую" как this.fieldName
        mounted() {
			let s, n;
			//set maxlength
			s = 'maxlength';
			n = parseInt(this[s]);
			if (!isNaN(n) ) {
				$('#' + this.id)[0].setAttribute(s, n);
			}
            /*var self = this;
            this.$root.$on('showMenuEvent', function(evt) {
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