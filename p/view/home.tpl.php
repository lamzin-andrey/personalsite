<h2>Термины</h2>
<ul>
	<li>Теория - тут больше будет того, что ещё не умею и изучаю</li>
	<li>Практика - делаем что-то что уже делали раньше (например, пишем новый компонент vue или директиву)</li>
	<li>Недоделки - тут имеются ввиду три "учебных" сайта, ну может быть ещё старый блог</li>
</ul>

<h2 class="text-danger">Фронтенд</h2>
<h3>Недоделки</h3>
<ul>
	<li>Посмотреть в последнем хроме, есть ли там горизонтальный скролл на Добро пожаловать. Если есть, то почему?</li>
	<li></li>
</ul>
<h3>Теория и практика</h3>
<ul>
	<li></li>
</ul>
<h3>Практика</h3>
<ul>
	<li></li>
</ul>
<h3>"Подкасты"</h3>
<ul>
	<li>Записать последний ролик про кэш, остальных всех отправить читать.</li>
	<li></li>
</ul>

<h2 class="text-success">Бэкенд</h2>
<h3>Недоделки</h3>
<ul>
	<li>Статистику считать более упорно.</li>
	<li>404 страницу через htaccess сделать php-шной. Старться генерить на ней то, что не найдено (а должно было быть найдено, просто не скомпилися ещё html файл!)</li>
	<li>На новой статье в меню есть Добро ПОжаловать (хотя скрыто)</li>
	<li></li>
</ul>
<h3 >Теория и практика</h3>
<h4>Больше теория</h4>
<ul>
	<li>Симтест</li>
	<li class="text-success">Читаем документацию по Симфони. Сегодня 05 11 2019 года, на сайте Symfony.com поддерживается документация версий 3.4, 4.3, 4.4 и 5.0 master. 
	Читаю перевод официальной документации на сайте symfony.ru.</li>
	
	<li><a href="https://symfony.ru/doc/current/forms.html" target="_blank">Forms in Symfony</a></li>
	<li><a href="https://symfony.ru/doc/current/components/form.html" target="_blank">Forms</a></li>
	<li><a href="http://symfony.ru/doc/current/form/form_themes.html" target="_blank">Forms themes</a></li>
</ul>


<h4>Больше практика</h4>
<ul>
	<li class="text-error">симфони 3. (очень сомнительное, что-то структура файлов не похожа на документацию symfony 3.4, 
				но тем не менее при запуске выводит именно Symfony 3.4)
		<ul>
			<li class="text-warning">app.city_sero_id теперь используем вместо main.city = 0</li>
			<li class="text-warning">https://symfony.ru/doc/current/service_container/3.3-di-changes.html#controllers-are-registered-as-services - это далеко не выбрасывается, важно</li>
			<li class="text-warning">Фильтр перестал запоминаться - скорее всего глюк встроенного сервера, который не всегда может читать файлы сессий</li>
			<li class="text-warning">Библиотечные функции, годные для повторного использования  оформляем как composer пакеты или если для публикации не готовы, то просто кидаем в vendor пригнудительно поместив под контроль git. <a href="https://andryuxa.ru/blog/faq_po_ustanovke_symfony_3_i_symfony_4_na_localhost_xubuntu_1804_v_oktyabre_2019_ogo_goda/#autoload" target="_blank">тут</a></li>
			<li class="text-info">Как создать и использовать свой репозиторий в Symfony?
				<ul>
					<li>Сравнить вопросы с локала и сервера для 2.6, поправить на сервере опечатки.</li>
					<li>Это надо добавить в тест по Sym 3. ОТ кого наследоватсья, что передать в род. конструктор.</li>
					
				</ul>
			</li>
			<li class="text-success">Прикрутить авторизацию пользователей https://vfac.fr/blog/how-install-fosuserbundle-with-symfony-4
		FOSUserBundle в статье через composer ставится.
				<ul>
					<li>Темизация страница логина, которые не темизированы
						<ul>
							<li> Перегрузить теперь ещё контроллер формы profile/edit), так как если текущий пароль не пуст,
									будем переустанавливать пароль.
									Если он не верен, для начала попробуй обнулить поля ввода нового, через
									$request->set()
									если не прокатит, дальше думай.
							 </li>
							<li>При отключеном sw кэшировании работает на ура. Что радует.
								Кэширование sw включить, пути добавить связанные
							</li> 
							<li>Кешер допилить до поддержки маски /a/m/*</li>
							<li>Исключить из кэша /login</li>
							<li></li>
						</ul>
					</li>
					<li>Добиваемся ajax рабьоты, добавляем формочку авторизации</li>
					<li></li>
				</ul>
			</li>
			<li class="text-light">Один день просто сидеть и играться с запросами используя критерии и билдеры
			<ul >
				<li>Создаем специальный контроллер Traning controller</li>
				<li>В нем по маршрутам /train/{method}</li>
				<li>Создаём для каждого случая отдельный метод (и для билдера и для критерии</li>
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
					-- Очень интересно!
				 </li>
				<li>SELECT * FROM `users` ORDER BY RAND() LIMIT 1000</li>
				<li>как хранить в mysql md5 хеш не как строку а как число
						"INSERT INTO `mdd` (hash, str) VALUES (UNHEX(md5('x')), md5('x'))"
						//hash имеет тип данных binary(16)
					 - а как это будет с Симфони?
				</li>
				<li></li>
			</ul>
			</li>
			
			<li>Установку uid в before сделать</li>
			<li>Управление длительностью сессии</li>
			<li>https://github.com/hwi/HWIOAuthBundle - это через соц-сети, для общего развития.</li>
			<li>Не написать ли ещё Symfony func-тесты</li>
			<li>Разобраться, не появилось ли в S4 что-то вместо бандлов для повторного исползования (Symfony Flex).</li>
			<li>Добиться работы qb->leftJoin как обещали симфонисты (используя аннотации).</li>
			<li>При переходе на производительный запрос для списка объявлений использовать кэширование как: <a href="https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/reference/caching.html#result-cache" tagret="_blank" >здесь</a></li>
			<li></li>
			<li></li>
		</ul>
		
	 </li>
	 <li></li>
	<li>Короче, один вариант сайта пилишь на симфони 3, второй на laravel.</li>
	<li>Симфони - там всё делаешь через готовые бандлы. Можно приколоться, идти параллельно в 2 3 4 и 5. https://github.com/FriendsOfSymfony/FOSUserBundle/blob/master/Resources/doc/index.rst - кажется, что-то готовое для организации пользователей. </li>
	<li>Стоит ли перетаскивать бэкенд этого сайта на фреймвёрк?  - ДА!
	У него чистый html на выдаче, поэтому он классный.
	Но это не проблема, наверное.
	Если удасться оставить html по прямым ссылкам, админку переносим на Симфони.
	</li>
</ul>

<h2>Дела текущие</h2>
<ul>
	<li>Создай userscript для автоматического переключения на gz сжатие при экспорте архива из pma</li>
	<li>Поддержку обратных слешей в статьях на хомяке срочно</li>
	<li>Сделать так чтобы кавычки внутри тегов вообще всегда не заменялись на ёлки-палки.</li>
	<li>Инструмент для декорирования - 4 часа пытаемся использовать парсер Никитоса,
		если за это время не успеваем, переписываем на пыху php2js и его используем. </li>
	<li>php2js - добавляем поддержку namespace и use инструкций и переписываем на php/</li>
	<li>НА главторгах первым делом обновить sw, путь к рандому убрать из кэш.
		<ul>
			<li>В модерской добавить редактирование тел.</li>
			<li>Каптчу вообще скрыть.</li>
			<li>Страницу согласия подавшим хоть раз не показывать.</li>
			<li>Страницу согласия для а2 выводить вне фрейма.</li>
			<li></li>
		</ul>
	</li>
	<li>Сайт показывает по неизвестному запросу в гугле страницу 
		Как делать ведроид на пыхе на первой позиции. Почему бы на этом не сосредоточиться?
		<ul>
			<li class="text-danger">Это делаем не каждый день, перемещаем вниз.</li>
			<li>Первым делом дорабатываем методы работы с файлами</li>
			<li></li>
		</ul>
	</li>
	
	<li>Статистику, пытаются ли что транслитировать на php2js сделать.<ul>
			<li>Переверстать с bootstrap 4 / vue2</li>
			<li></li>
		</ul></li>
	</li>
	<li><b class="text-danger">20 11 2019</b> Если да и это уники, Пофиксить глюк с parent. Paypal && donate пробовать
	<li></li>
	<li><ul>
		<li >Разобраться с счётчиками freesoft
			
			<li ><b class="text-danger">24 12 2019</b> Спросить, не появился ли код для счётчиков freesoft.
			</li>
			<li>Проверить, не удалены ли работы с сайта. (Могут прямые ссылки на файлы сломаться) </li>
			<li></li>
		</li>
		<li></li>
	</ul></li>
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
	<li>В навахо-админ добавить растяжку кнопок на всю длину.</li>
	<li>новая версия fx для новейшей php версии. Не забыть, что статистика теперь должна отправляться на хомяк</li>
	<li></li>
</ul>

<h2>Публикации моего старья</h2>
<ul>
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
