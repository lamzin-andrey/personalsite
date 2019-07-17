<p>
	����� ���������� ������� �����
	https://www.npmjs.com/package/vue2-datatable-component
	
	���� �������� ������������� �� zip ������
</p>
<p>
	
	https://datatables.net/examples/data_sources/server_side.html
	  - �����.
	  
	
	<ul>
		<li>��������� �����������</li>
		<li>�������� � ��������� ������� � ������������ ���</li>
		<li>�������� �����</li>
		<li>����������� ������ (��������� �� sbadmin ���� ��� ��� ����)</li>
		<li>����� �������������� ��������� ���� � ����, ���������� ������������� (�����)</li>
	</ul>
</p>
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
	<div class="tab-pane fade" id="edit" role="tabpanel" aria-labelledby="edit-tab">
		<div class="card">
			<div class="card-body">
				<h5 class="card-title">�����/������������� ������</h5>
				<p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
				<a href="#" class="btn btn-primary">������� ����-������</a>
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
































