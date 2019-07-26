<template>
	<div class="mt-2">
		<label :for="id">{{ dinlabel }}</label>
		<div class="input-group">
			<input
				:value="value"
				@input="$emit('input', $event.target.value)"
				:placeholder="dinplaceholder"
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
			//if set, values label and placeholder will ignore and label set === placeholder
			'placeholderlabel',
			//set readonly="readonly" for apply
			'readonly',
			'type',
			'value',
			'className'
		],
		name: 'inputb4',
		
        //вызывается раньше чем mounted
        data: function(){return {
            
		}; },
		computed: {
			dinplaceholder : function() {
				if (this.placeholderlabel) {
					return this.placeholderlabel;
				}
				return this.placeholder;
			},
			dinlabel : function() {
				if (this.placeholderlabel) {
					return this.placeholderlabel;
				}
				return this.label;
			}
		},
        //
        methods:{
            
           
        }, //end methods
        //вызывается после data, поля из data видны "напрямую" как this.fieldName
        mounted() {
			//set readonly
            let s = 'readonly', inp;
			if (this[s] == s) {
				$('#' + this.id)[0].setAttribute(s, s);
			}
        }
    }
</script>