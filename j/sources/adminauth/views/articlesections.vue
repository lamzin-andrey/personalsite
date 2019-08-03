<template>
<div>
	
<ul class="nav nav-tabs" role="tablist">
		<li class="nav-item">
			<a class="nav-link" 
				id="acatlist-tab"
				data-toggle="tab"
				href="#acatlist"
				role="tab"
				aria-controls="home"
				aria-selected="true">{{ $t('app.List') }}</a>
		</li>
		<li class="nav-item">
			<a class="nav-link active"
				id="editacat-tab"
				data-toggle="tab"
				href="#editacat"
				role="tab"
				aria-controls="profile"
				aria-selected="false"> formTabTitle </a>
		</li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane fade " id="acatlist" role="tabpanel" aria-labelledby="list-tab">
			<div class="card">
				<div class="card-body">
					<h5 class="card-title">Список категорий статей</h5>
					
					<table id="articlecats" class="display table table-bordered" style="width:100%">
						<thead>
							<tr>
								<th>{{ $t('app.Heading') }}</th> 
								<th>{{ $t('app.Operations') }}</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th>{{ $t('app.Heading') }}</th>
								<th>{{ $t('app.Operations') }}</th>
							</tr>
						</tfoot>
					</table>
					
				</div>
			</div>
		</div>
		
		<div class="tab-pane fade show active" id="editacat" role="tabpanel" aria-labelledby="edit-tab">
			<div class="card">
				<div class="card-body">
					<h5 class="card-title"> newEdit </h5>
					HERE EDIT FORM
				</div>
			</div>
		</div>
	</div>
</div>

</template>

<script>
    //Компонент для отображения инпута ввода текста bootstrap 4
    /*Vue.component('inputb4', require('../../landlib/vue/2/bootstrap/4/inputb4.vue'));
    Vue.component('selectb4', require('../../landlib/vue/2/bootstrap/4/selectb4.vue'));
    Vue.component('checkboxb4', require('../../landlib/vue/2/bootstrap/4/checkboxb4.vue'));
    Vue.component('textareab4', require('../../landlib/vue/2/bootstrap/4/textareab4.vue'));
    Vue.component('inputfileb4', require('../../landlib/vue/2/bootstrap/4/inputfileb4/inputfileb4.vue'));*/

    export default {
        name: 'articlesections',
        //вызывается раньше чем mounted
        data: function(){
			let _data  = {
				
			};
			return _data;
		},
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