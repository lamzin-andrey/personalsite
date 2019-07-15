<ul class="nav nav-tabs" role="tablist">
	<li class="nav-item">
		<a class="nav-link active" 
			id="alist-tab"
			data-toggle="tab"
			href="#alist"
			role="tab"
			aria-controls="home"
			aria-selected="true"><?php echo l('List') ?></a>
	</li>
	<li class="nav-item">
		<a class="nav-link"
			id="edit-tab"
			data-toggle="tab"
			href="#edit"
			role="tab"
			aria-controls="profile"
			aria-selected="false"><?php echo l('NewEdit') ?></a>
	</li>
	<li v-if="isSeotabVisible" class="nav-item">
		<a class="nav-link"
			id="seo-tab"
			data-toggle="tab"
			href="#seo"
			role="tab"
			aria-controls="contact"
			aria-selected="false"><?php echo l('SEO') ?></a>
	</li>
</ul>
<div class="tab-content">
	<div class="tab-pane fade show active" id="alist" role="tabpanel" aria-labelledby="list-tab">
		<div class="card">
			<div class="card-body">
				<h5 class="card-title">Список статей</h5>
				<p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
				<a href="#" class="btn btn-primary">Переход куда-нибудь</a>
			</div>
		</div>
	</div>
	<div class="tab-pane fade" id="edit" role="tabpanel" aria-labelledby="edit-tab">
		<div class="card">
			<div class="card-body">
				<h5 class="card-title">Новая/редактируемая статья</h5>
				<p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
				<a href="#" class="btn btn-primary">Переход куда-нибудь</a>
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
































