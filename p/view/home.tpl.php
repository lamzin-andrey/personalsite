<h2>Термины</h2>
<ul>
	<li>Теория - тут больше будет того, что ещё не умею и изучаю</li>
	<li>Практика - делаем что-то что уже делали раньше (например, пишем новый компонент vue или директиву)</li>
</ul>

<h2 class="text-success">Фронтенд</h2>
<h3>Недоделки</h3>
<ul>
	<li>Посмотреть в последнем хроме, есть ли там горизонтальный скролл на Добро пожаловать. Если есть, то почему?</li>
	<li></li>
</ul>
<h3 class="text-success">Теория и практика</h3>
<ul>
	<li class="text-success">Обновиться до node  9 </li>
	<li>Добиться успешной сборки темы, которую (которые) успешно скачал при создании скрытенбурга.
	   Стремимся к 1 в 1 для скомпилированного файла.
	</li>
	<li></li>
</ul>
<h3>Практика</h3>
<ul>
	<li>Написать компонент типа youtubelink где src - ссылка на видео, img - ссылка на файл с изображением.</li>
</ul>
<h3>"Подкасты"</h3>
<ul>
	<li>Записать последний ролик про кэш, остальных всех отправить читать.</li>
	<li></li>
</ul>
 
<h3>Недоделки</h3>
<ul>
	<li>Статистику считать более упорно.</li>
	<li>404 страницу через htaccess сделать php-шной. Старться генерить на ней то, что не найдено (а должно было быть найдено, просто не скомпилися ещё html файл!)</li>
	<li></li>
</ul>
<h3>Теория и практика</h3>
<ul>
	<li>Короче, один вариант сайта пилишь на симфони 3, второй на laravel.</li>
	<li>Симфони - там всё делаешь через готовые бандлы. Можно приколоться, идти параллельно в 2 3 4 и 5.</li>
	<li>Стоит ли перетаскивать бэкенд этого сайта на фреймвёрк?  - Нет, потому что он классный и так. 
	У него чистый html на выдаче.</li>
</ul>

<h2>Дела текущие</h2>
<ul>
	<li>Перенести фастксамп в подраздел хомяка. Не сделать ли хомяком php2js.ru? (в этом году уже поздно, но пусть php2js.ru на всякий случай будет на этом же движке.)</li>
	<li>Старый блог савсэм анонимизировать.</li>
</ul>

<h2>Прочее самообразование</h2>
<ul>
	<li>Пишем тервер.</li>
	<li>Изучаем Алису, там за навыки (07 10 2019) обещали денег саамым резвым.</li>
</ul>

<h2>Творчество</h2>
<ul>
	<li>Конвертер 100 </li>
	<li>Тетрис мобильный</li>
	<li>Кот - прыгун</li>
	<li>В Умную яркость настройку надо ли перезапускать, когда не удалось получить значение яркости.</li>
	<li></li>
</ul>

<h2>Публикации моего старья</h2>
<ul>
	<li>Гиштрис</li>
	<li>Чат с полиглотом</li>
	<li>Курсач и web версию неплохо бы для него.</li>
</ul>

<h2>Публикации не моего старья</h2>
<ul>
	<li>Одну флешку</li>
	<li>Одну opensource программулину</li>
	<li>Одну песню</li>
	<li>Одну киношку</li>
</ul>

<h2>Что-то старое</h2>
<ul>
	<li>В шаблоне изображения сейчас без ограничения ширины, кажется. И не задействовать ли media?</li>
	<li>https://habr.com/ru/company/ruvds/blog/346220/ - как всегда, всё уже украдено, до нас.</li>
	<li>Кстати, не почитать ли про тестирование в Vue?</li>
</ul>


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
