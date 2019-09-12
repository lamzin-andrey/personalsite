<ul>
	<li>В шаблоне изображения сейчас без ограничения ширины, кажется. И не задействовать ли media?</li>
	<li>https://habr.com/ru/company/ruvds/blog/346220/ - как всегда, всё уже украдено, до нас.</li>
	<li>Кстати, не почитать ли про тестирование в Vue?</li>
</ul>

<p>
	Может оказаться хорошей темой
	https://www.npmjs.com/package/vue2-datatable-component
	
	Пока использую package.json: npm install --save datatables.net-bs4
</p>
<ul class="nav nav-tabs" role="tablist">
	<li class="nav-item">
		<a class="nav-link" 
			id="alist-tab"
			href="#alist"
			role="tab"
			aria-controls="home"
			aria-selected="true"><?php echo l('List') ?></a>
	</li>
	<li class="nav-item">
		<a class="nav-link active"
			id="edit-tab"
			data-toggle="tab"
			href="#edit"
			role="tab"
			aria-controls="profile"
			aria-selected="false">{{ formTabTitle }}</a>
	</li>
</ul>
<div class="tab-content">
	
	<div class="tab-pane fade " id="alist" role="tabpanel" aria-labelledby="list-tab">
		<div class="card">
			<div class="card-body">
				<h5 class="card-title">Список статей</h5>
				
				
	<table id="articles" class="display table table-bordered" style="width:100%">
        <thead>
            <tr>
                <th class="u-tabledragcolumn-head"><?php echo l(''); ?></th>
                <th><?php echo l('Heading'); ?></th>
                <th><?php echo l('Operations'); ?></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
				<th><?php echo l(''); ?></th>
                <th><?php echo l('Heading'); ?></th>
                <th><?php echo l('Operations'); ?></th>
            </tr>
        </tfoot>
    </table>
				
			</div>
		</div>
	</div>
	
	<div class="tab-pane fade show active" id="edit" role="tabpanel" aria-labelledby="edit-tab">
		<div class="card">
			<div class="card-body">
				<h5 class="card-title">{{ newEdit }}</h5>
				<articleform ref="articleform"></articleform>
				<textarea hidden style="display:none" id="jdata"><?php echo $route->app->jsonData ?></textarea>
			</div>
		</div>
	</div>
	
	<div class="tab-pane fade" id="seo" role="tabpanel" aria-labelledby="seo-tab">
		<div class="card">
			<div class="card-body">
				<h5 class="card-title">SEO</h5>
				<p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
				<a href="#" class="btn btn-primary">Переход куда-нибудь</a>
			</div>
		</div>
	</div>
	
</div>
