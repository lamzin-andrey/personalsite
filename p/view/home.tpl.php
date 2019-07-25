<p>
	����� ��������� ������� �����
	https://www.npmjs.com/package/vue2-datatable-component
	
	���� ��������� package.json: npm install --save datatables.net-bs4
</p>
<p>
	
	https://datatables.net/examples/data_sources/server_side.html
	  - �����.
	<p>��� ������ ���� �� ����� �������������� (�� SEO):</p>
	<ul>
		<li>������������ ��������� �� ��������.</li>
		<li>������, �� �������� �� ��� ������������ � Vue?</li>
		<li>����� ���������� id ������������� ������ ��� "��������" �������� �� "��������������" � ��������� �������� ���� ����.</li>
		<li>� ���� �������� � SEO ��� ������� ������� ������ "��������� � ��������� ��������������" � "��������� ��������������" (����� ������� ��� ������!). ����� ������ �������� ������� ������ ����� ����, ��� ������ ���� �� ���</li>
		<li>insertimage button</li>
		<li>https://habr.com/ru/company/ruvds/blog/346220/ - ��� ������, �� ��� ��������, �� ���.</li>
	</ul>
</p>
<ul class="nav nav-tabs" role="tablist">
	<li class="nav-item">
		<a class="nav-link" 
			id="alist-tab"
			data-toggle="tab"
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
			aria-selected="false"><?php echo l('Append');?></a>
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
	
	<div class="tab-pane fade " id="alist" role="tabpanel" aria-labelledby="list-tab">
		<div class="card">
			<div class="card-body">
				<h5 class="card-title">������ ������</h5>
				
				
	<table id="articles" class="display table table-bordered" style="width:100%">
        <thead>
            <tr>
                <th><?php echo l('Heading'); ?></th>
                <th><?php echo l('Operations'); ?></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
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
				<h5 class="card-title"><?php echo l('NewEdit') ?></h5>
				<articleform></articleform>
				<textarea hidden style="display:none" id="jdata"><?php echo $route->app->jsonData ?></textarea>
			</div>
		</div>
	</div>
	
	<div class="tab-pane fade" id="seo" role="tabpanel" aria-labelledby="seo-tab">
		<div class="card">
			<div class="card-body">
				<h5 class="card-title">SEO</h5>
				<p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
				<a href="#" class="btn btn-primary">������� ����-������</a>
			</div>
		</div>
	</div>
	
</div>
































