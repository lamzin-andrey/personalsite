window.Vue = require('vue');

Vue.directive('b421validators', {
    inserted:function(el, binding, vnode) {
        let $el = $(el), $form = $el.parents('form').first(),
            args = String(binding.expression).split(','), i, func,
            validator = vnode.context.$root.formInputValidator;
        for (i = 0; i < args.length; i++) {
            func = args[i].trim().replace(/"/g, '').replace(/'/, "");
            if (validator && validator[func] instanceof Function) {
                console.log('Add listener for func "' + func + '"');
                $el.on('input', (event) => { validator[func](event, $el, 'input', vnode.context.$root.$t); });
                $form.on('submit', (event) => { return validator[func](event, $el, 'submit', vnode.context.$root.$t); });
            }
        }
    }
});
//Просто чтобы было, что импортировать в app.js. На самом деле здесь важно было создать глобальную директиву
var B421ValidatorsDirective = 'B421ValidatorsDirective';
export default B421ValidatorsDirective;