<h2>�������</h2>
<ul>
	<li>������ - ��� ������ ����� ����, ��� ��� �� ���� � ������</li>
	<li>�������� - ������ ���-�� ��� ��� ������ ������ (��������, ����� ����� ��������� vue ��� ���������)</li>
	<li>��������� - ��� ������� ����� ��� "�������" �����, �� ����� ���� ��� ������ ����</li>
</ul>

<h2 class="text-danger">��������</h2>
<h3>���������</h3>
<ul>
	<li>���������� � ��������� �����, ���� �� ��� �������������� ������ �� ����� ����������. ���� ����, �� ������?</li>
	<li></li>
</ul>
<h3>������ � ��������</h3>
<ul>
	<li></li>
</ul>
<h3>��������</h3>
<ul>
	<li></li>
</ul>
<h3>"��������"</h3>
<ul>
	<li>�������� ��������� ����� ��� ���, ��������� ���� ��������� ������.</li>
	<li></li>
</ul>

<h2 class="text-success">������</h2>
<h3>���������</h3>
<ul>
	<li>���������� ������� ����� ������.</li>
	<li>404 �������� ����� htaccess ������� php-����. �������� �������� �� ��� ��, ��� �� ������� (� ������ ���� ���� �������, ������ �� ���������� ��� html ����!)</li>
	<li>�� ����� ������ � ���� ���� ����� ���������� (���� ������)</li>
	<li></li>
</ul>
<h3 >������ � ��������</h3>
<h4>������ ������</h4>
<ul>
	<li>�������</li>
	<li class="text-success">������ ������������ �� �������. ������� 05 11 2019 ����, �� ����� Symfony.com �������������� ������������ ������ 3.4, 4.3, 4.4 � 5.0 master. 
	����� ������� ����������� ������������ �� ����� symfony.ru.</li>
	
	<li><a href="https://symfony.ru/doc/current/forms.html" target="_blank">Forms in Symfony</a></li>
	<li><a href="https://symfony.ru/doc/current/components/form.html" target="_blank">Forms</a></li>
	<li><a href="http://symfony.ru/doc/current/form/form_themes.html" target="_blank">Forms themes</a></li>
</ul>


<h4>������ ��������</h4>
<ul>
	<li class="text-error">������� 3. (����� ������������, ���-�� ��������� ������ �� ������ �� ������������ symfony 3.4, 
				�� ��� �� ����� ��� ������� ������� ������ Symfony 3.4)
		<ul>
			<li class="text-warning">app.city_sero_id ������ ���������� ������ main.city = 0</li>
			<li class="text-warning">https://symfony.ru/doc/current/service_container/3.3-di-changes.html#controllers-are-registered-as-services - ��� ������ �� �������������, �����</li>
			<li class="text-warning">������ �������� ������������ - ������ ����� ���� ����������� �������, ������� �� ������ ����� ������ ����� ������</li>
			<li class="text-warning">������������ �������, ������ ��� ���������� �������������  ��������� ��� composer ������ ��� ���� ��� ���������� �� ������, �� ������ ������ � vendor �������������� �������� ��� �������� git. <a href="https://andryuxa.ru/blog/faq_po_ustanovke_symfony_3_i_symfony_4_na_localhost_xubuntu_1804_v_oktyabre_2019_ogo_goda/#autoload" target="_blank">���</a></li>
			<li class="text-info">��� ������� � ������������ ���� ����������� � Symfony?
				<ul>
					<li>�������� ������� � ������ � ������� ��� 2.6, ��������� �� ������� ��������.</li>
					<li>��� ���� �������� � ���� �� Sym 3. �� ���� �������������, ��� �������� � ���. �����������.</li>
					
				</ul>
			</li>
			<li class="text-success">���������� ����������� ������������� https://vfac.fr/blog/how-install-fosuserbundle-with-symfony-4
		FOSUserBundle � ������ ����� composer ��������.
				<ul>
					<li>��������� �������� ������, ������� �� ������������
						<ul>
							<li> ����������� ������ ��� ���������� ����� profile/edit), ��� ��� ���� ������� ������ �� ����,
									����� ����������������� ������.
									���� �� �� �����, ��� ������ �������� �������� ���� ����� ������, �����
									$request->set()
									���� �� ��������, ������ �����.
							 </li>
							<li>��� ���������� sw ����������� �������� �� ���. ��� ������.
								����������� sw ��������, ���� �������� ���������
							</li> 
							<li>����� �������� �� ��������� ����� /a/m/*</li>
							<li>��������� �� ���� /login</li>
							<li></li>
						</ul>
					</li>
					<li>���������� ajax �������, ��������� �������� �����������</li>
					<li></li>
				</ul>
			</li>
			<li class="text-light">���� ���� ������ ������ � �������� � ��������� ��������� �������� � �������
			<ul >
				<li>������� ����������� ���������� Traning controller</li>
				<li>� ��� �� ��������� /train/{method}</li>
				<li>������ ��� ������� ������ ��������� ����� (� ��� ������� � ��� ��������</li>
				<li >SELECT COUNT(id) AS cnt FROM main GROUP BY region *</li>
				<li >SELECT m.title, c.city_name, r.region_name, r.is_city FROM main AS m
LEFT JOIN cities AS c ON c.id = m.city 
LEFT JOIN regions AS r ON r.id = m.region 
WHERE m.is_deleted = 1 LIMIT 10, 10;								*
				</li>
				<li >SELECT m.title, c.city_name, r.region_name, r.is_city FROM main AS m
INNER JOIN cities AS c ON c.id = m.city 
INNER JOIN regions AS r ON r.id = m.region 
WHERE m.is_deleted = 1 LIMIT 10, 10; *</li>
				<li >SELECT CONCAT(phone, ', ', email) FROM users LIMIT 10; *</li>
				<li>SELECT m.id, m.phone, GROUP_CONCAT(m.title) AS titles, GROUP_CONCAT(m.id) AS idlist FROM main AS m 
					GROUP BY (m.phone)
					-- https://ourcodeworld.com/articles/read/245/how-to-execute-plain-sql-using-doctrine-in-symfony-3
					-- ����� ���������!
				 </li>
				<li>SELECT * FROM `users` ORDER BY RAND() LIMIT 1000</li>
				<li>��� ������� � mysql md5 ��� �� ��� ������ � ��� �����
						"INSERT INTO `mdd` (hash, str) VALUES (UNHEX(md5('x')), md5('x'))"
						//hash ����� ��� ������ binary(16)
					 - � ��� ��� ����� � �������?
				</li>
				<li></li>
			</ul>
			</li>
			
			<li>��������� uid � before �������</li>
			<li>���������� ������������� ������</li>
			<li>https://github.com/hwi/HWIOAuthBundle - ��� ����� ���-����, ��� ������ ��������.</li>
			<li>�� �������� �� ��� Symfony func-�����</li>
			<li>�����������, �� ��������� �� � S4 ���-�� ������ ������� ��� ���������� ������������ (Symfony Flex).</li>
			<li>�������� ������ qb->leftJoin ��� ������� ���������� (��������� ���������).</li>
			<li>��� �������� �� ���������������� ������ ��� ������ ���������� ������������ ����������� ���: <a href="https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/reference/caching.html#result-cache" tagret="_blank" >�����</a></li>
			<li></li>
			<li></li>
		</ul>
		
	 </li>
	 <li></li>
	<li>������, ���� ������� ����� ������ �� ������� 3, ������ �� laravel.</li>
	<li>������� - ��� �� ������� ����� ������� ������. ����� �����������, ���� ����������� � 2 3 4 � 5. https://github.com/FriendsOfSymfony/FOSUserBundle/blob/master/Resources/doc/index.rst - �������, ���-�� ������� ��� ����������� �������������. </li>
	<li>����� �� ������������� ������ ����� ����� �� ��������?  - ��!
	� ���� ������ html �� ������, ������� �� ��������.
	�� ��� �� ��������, ��������.
	���� �������� �������� html �� ������ �������, ������� ��������� �� �������.
	</li>
</ul>

<h2>���� �������</h2>
<ul>
	<li>������ userscript ��� ��������������� ������������ �� gz ������ ��� �������� ������ �� pma</li>
	<li>��������� �������� ������ � ������� �� ������ ������</li>
	<li>������� ��� ����� ������� ������ ����� ������ ������ �� ���������� �� ����-�����.</li>
	<li>���������� ��� ������������� - 4 ���� �������� ������������ ������ ��������,
		���� �� ��� ����� �� ��������, ������������ �� ���� php2js � ��� ����������. </li>
	<li>php2js - ��������� ��������� namespace � use ���������� � ������������ �� php/</li>
	<li>�� ���������� ������ ����� �������� sw, ���� � ������� ������ �� ���.
		<ul>
			<li>� ��������� �������� �������������� ���.</li>
			<li>������ ������ ������.</li>
			<li>�������� �������� �������� ���� ��� �� ����������.</li>
			<li>�������� �������� ��� �2 �������� ��� ������.</li>
			<li></li>
		</ul>
	</li>
	<li>���� ���������� �� ������������ ������� � ����� �������� 
		��� ������ ������� �� ���� �� ������ �������. ������ �� �� ���� �� ���������������?
		<ul>
			<li class="text-danger">��� ������ �� ������ ����, ���������� ����.</li>
			<li>������ ����� ������������ ������ ������ � �������</li>
			<li></li>
		</ul>
	</li>
	
	<li>����������, �������� �� ��� ��������������� �� php2js �������.<ul>
			<li>������������ � bootstrap 4 / vue2</li>
			<li></li>
		</ul></li>
	</li>
	<li><b class="text-danger">20 11 2019</b> ���� �� � ��� �����, ��������� ���� � parent. Paypal && donate ���������
	<li></li>
	<li><ul>
		<li >����������� � ���������� freesoft
			
			<li ><b class="text-danger">24 12 2019</b> ��������, �� �������� �� ��� ��� ��������� freesoft.
			</li>
			<li>���������, �� ������� �� ������ � �����. (����� ������ ������ �� ����� ���������) </li>
			<li></li>
		</li>
		<li></li>
	</ul></li>
</ul>

<h2>������ ���������������</h2>
<ul>
	<li>����� ������.</li>
	<li>������� �����, ��� �� ������ (07 10 2019) ������� ����� ������ ������.</li>
</ul>

<h2>����������</h2>
<ul>
	<li>��������� 100 </li>
	<li>������ ���������</li>
	<li>��� - ������</li>
	<li>� ����� ������� ��������� ���� �� �������������, ����� �� ������� �������� �������� �������.</li>
	<li>� ������-����� �������� �������� ������ �� ��� �����.</li>
	<li>����� ������ fx ��� �������� php ������. �� ������, ��� ���������� ������ ������ ������������ �� �����</li>
	<li></li>
</ul>

<h2>���������� ����� ������</h2>
<ul>
	<li>��� � ����������</li>
	<li>������ � web ������ ������� �� ��� ����.</li>
</ul>

<h2>���������� �� ����� ������</h2>
<ul>
	<li>���� ������</li>
	<li>���� opensource �������������</li>
	<li>���� �����</li>
	<li>���� �������</li>
</ul>

<h2>���-�� ������</h2>
<ul>
	<li>� ������� ����������� ������ ��� ����������� ������, �������. � �� ������������� �� media?</li>
	<li>https://habr.com/ru/company/ruvds/blog/346220/ - ��� ������, �� ��� ��������, �� ���.</li>
	<li>������, �� �������� �� ��� ������������ � Vue?</li>
</ul>


<ul>
	<li>� ������� ����������� ������ ��� ����������� ������, �������. � �� ������������� �� media?</li>
	<li>https://habr.com/ru/company/ruvds/blog/346220/ - ��� ������, �� ��� ��������, �� ���.</li>
	<li>������, �� �������� �� ��� ������������ � Vue?</li>
</ul>

<p>
	����� ��������� ������� �����
	https://www.npmjs.com/package/vue2-datatable-component
	
	���� ��������� package.json: npm install --save datatables.net-bs4
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
				<h5 class="card-title">������ ������</h5>
				
				
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
				<a href="#" class="btn btn-primary">������� ����-������</a>
			</div>
		</div>
	</div>
	
</div>
