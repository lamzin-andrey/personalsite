<h2>Термины</h2>
<ul>
	<li>Теория - тут больше будет того, что ещё не умею и изучаю</li>
	<li>Практика - делаем что-то что уже делали раньше (например, пишем новый компонент vue или директиву)</li>
	<li>Недоделки - тут имеются ввиду три "учебных" сайта, ну может быть ещё старый блог</li>
</ul>

<h2 class="text-danger">Фронтенд</h2>
<h3>Недоделки</h3>
<ul>
	<li>Svelta</li>
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
	<li></li>
</ul>


<h4>Больше практика</h4>
<ul>
	<li class="text-error">симфони 3. (очень сомнительное, что-то структура файлов не похожа на документацию symfony 3.4, 
				но тем не менее при запуске выводит именно Symfony 3.4)
	 </li>
	 <li>Самый продвинутый бандл - с командой декорирования и зависимостью от liip - надо выпилить эту зависимость.</li>
	 <li>Бандл оплаты, черновик - надо добить с киви будет</li>
	 <li>И наконец надо создать свой бандл с авторизацией, который я смогу использовать в Sym 4 и 5</li>
	 <li>А к нему прикрутить эту старую историю с авторизацией</li>
	 <li></li>
</ul>


<h2>Дела текущие</h2>
<ul>
	<li>fastxampp для новой версии php см. process.txt</li>
	<li>Сделать так чтобы кавычки внутри тегов вообще всегда не заменялись на ёлки-палки.</li>
	
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
		 - Это было давно, что теперь?
		<ul>
			<li></li>
		</ul>
	</li>
	<li>php2js<ul>
			<li>Если в классе две константы, одна теряется и комментарий получается с prop вместо const</li>
			<li>
				Запрос вывода статистики использования 
				SELECT u.id, COUNT(c.id) AS cnt FROM p2j_users AS u 
					LEFT JOIN `p2j_codes` AS c ON c.uid = u.id GROUP BY c.uid
				ORDER BY cnt DESC
				
				7 - это я
			</li>
			<li>Переверстать с bootstrap 4 / vue2</li>
		<li></li>
		</ul></li>
	</li>
	<li></li>
</ul>
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
