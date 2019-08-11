<!-- Самокорректура  - до Конечно, fullScreen здесь достаточно условен-->
<header>
	<h3>Программирование 2D графики</h3>
</header>
<div class="ainfo">
Все рассмотренные до сих пор примеры почти не зависят от среды, в которой выполняется JavaScript. Если вам вдруг пришла бы в голову идея запустить эти программки например как сценарий
WScript для windows, от вас бы потребовалось только определить четыре функции: alert, prompt, writeln и  readln так, чтобы они принимали от пользователя значение или выводили его куда-нибудь.
Решения задач по программированию 2D графики будут привязаны к браузерному JavaScript гораздо сильнее. Я буду использовать элемент DOM canvas (холст), но не собираюсь вдаваться в подробности, что такое DOM.
Об этом в сети великое множество информации, а я здесь осваиваю азы программирования и информация о DOM, как мне кажется, к азам программирования отношение имеет мало.
DOM элемент canvas хорош в контексте освоения азов программирования тем, что имеет многие функции, которые очень похожи на 
соответствующие функции для отрисовки графических примитивов в других языках программирования, например C++ или Pascal.
</div>
<h4 id="first_canvas">Как добавить холст, на котором будем рисовать</h4>
<p>Перед тем, как пытаться программировать графику в браузерном JavaScript, надо получить контекст существующего на веб-странице элемента 
canvas (canvas буквально  - холст). В нашем случае на странице такого элемента нет вообще, но мы можем создать новый, чтобы получить его контекст. Контекст будет получен в виде объекта. А у этого объекта уже будут доступны множество
методов и свойств, описание которых вы можете видеть на сайте <?=QuickStartHandler::a('http://www.w3schools.com/jsref/dom_obj_canvas.asp', 'w3schools.com')?> </p>
<p>Вы можете выполнить на нашем сайте этот код:</p>
<pre>
<b>function</b> createCanvasExample() {
	<span class="strcolor">"use strict"</span>
	<b>var</b> canvas = document.<i>createElement</i>(<span class="strcolor">'canvas'</span>),       <span class="strcolor">//Создали "холст"
</span>		context, 
		appConsole = document.<i>getElementById</i>(<span class="strcolor">'console'</span>); <span class="strcolor">//наше "окошко" вывода приложения
</span>	canvas.width  = 300;               <span class="strcolor">//ширина холста
</span>	canvas.height = 150;               <span class="strcolor">//высота холста
</span>	appConsole.innerHTML = <span class="strcolor">''</span>;         <span class="strcolor">//удаляем из окна вывода приложения все, что там может быть
</span>	appConsole.<i>appendChild</i>(canvas);    <span class="strcolor">//добавляем в окно вывода приложения наш холст, можно начинать рисовать
</span>	
	context = canvas.<i>getContext</i>(<span class="strcolor">"2d"</span>);   <span class="strcolor">//Получить контекст рисования
</span>	context.fillStyle = <span class="strcolor">"#FF0000"</span>;       <span class="strcolor">//Стиль заливки - красный. Проще говоря, будем рисовать красную фигуру.
</span>	<span class="strcolor">//Рисуем прямоугольник с координатами левого верхнего угла x = 10 и y = 10 
</span>	<span class="strcolor">// пикселей и шириной 100 и 110 пикселей
</span>	context.<i>fillRect</i>(10, 10, 100, 110);  
}
</pre>
<p>В этом коде много нового. Объект document доступен в JavaScript программе, которая выполняется в браузере, у него есть свои свойства и методы.
Меня сейчас интересуют два из них. Первый, createElement служит для того, чтобы создать новый DOM элемент. 
Так как мне нужен холст, я передаю этому методу как аргумент строку 'canvas'. Полученный в результате вызова объект холста позволяет менять его свойства. Я изменил свойства ширина (width) и высота (height), чтобы иметь возможность видеть то, что я буду рисовать на этом холсте.</p>
<p>Окошко, в котором мы видим вывод наших примеров имеет идентификатор "console". Используя метод объекта document <i>getElementById</i> мы можем получить переменную, с помощью которой можем взаимодействовать с ним. 
Первым делом я устанавливаю свойство innerHTML равным пустой строке. Это уничтожит все содержимое, которое может быть в этом окошке. </p>
<p>Затем вызываю метод <i>appendChild</i>, который есть у всех DOM элементов-контейнеров, в том числе и у того, на который ссылается наша переменная appConsole. На этом необходимые манипуляции с DOM заканчиваются, элемент холст создан, можно начинать рисование.
В этом примере я просто вывожу прямоугольник, чтобы убедится, что все работает.</p>
<p>Для начала мне надо получить контекст рисования. Я делаю это с помощью функции <i>getContext</i>, передавая ей как аргумент строку "2d", чтобы указать, что я буду работать с 2D графикой.</p>
<p>Далее я устанавливаю свойство объекта контекста fillStyle в цвет, который выбрал для цвета прямоугольника и отрисовываю его вызвав метод <i>fillRect</i>.</p>
<p>Пример работает, однако от него мало толку. Во-первых, холст хочется растянуть как минимум на все окошко вывода приложения, а еще лучше на весь экран. Сделать это довольно просто. 
Сначала задам холсту размер окошка приложения.</p>
<pre>
<b>function</b> createCanvasExample() {
	<span class="strcolor">"use strict"</span>
	<b>var</b> canvas = document.<i>createElement</i>(<span class="strcolor">'canvas'</span>),       <span class="strcolor">//Создали "холст"
</span>		context, 
		appConsole = document.<i>getElementById</i>(<span class="strcolor">'console'</span>); <span class="strcolor">//наше "окошко" вывода приложения
</span>	canvas.width  = appConsole.offsetWidth;               <span class="strcolor">//ширина холста
</span>	canvas.height = appConsole.offsetHeight;               <span class="strcolor">//высота холста
</span>	appConsole.innerHTML = <span class="strcolor">''</span>;         <span class="strcolor">//удаляем из окна вывода приложения все, что там может быть
</span>	appConsole.<i>appendChild</i>(canvas);    <span class="strcolor">//добавляем в окно вывода приложения наш холст, можно начинать рисовать
</span>	
	context = canvas.<i>getContext</i>(<span class="strcolor">"2d"</span>);   <span class="strcolor">//Получить контекст рисования
</span>	context.fillStyle = <span class="strcolor">"#FF0000"</span>;       <span class="strcolor">//Стиль заливки - красный. Проще говоря, будем рисовать красную фигуру.
</span>	<span class="strcolor">//Рисуем прямоугольник с координатами левого верхнего угла x = 10 и y = 10 
</span>	<span class="strcolor">// пикселей и шириной 100 и 110 пикселей
</span>	context.<i>fillRect</i>(0, 0, canvas.width, canvas.height); 
}
</pre>
<p>Здесь я просто заменил числа значениями ширины и высоты окошка консоли вывода приложения, получив их значения, хранящиеся в свойствах offsetWidth и offsetHeight DOM элемента.</p>
<p>Если надо развернуть ваше графическое приложение на весь экран, можно использовать код:</p>
<pre>
<b>function</b> createCanvasExample() {
	<span class="strcolor">"use strict"</span>
	<b>var</b> canvas = document.<i>createElement</i>(<span class="strcolor">'canvas'</span>),       <span class="strcolor">//Создали "холст"
</span>		context,
		i, firstTextY, text, sz;
	canvas.width  = screen.width;               <span class="strcolor">//ширина холста
</span>	canvas.height = screen.height;              <span class="strcolor">//высота холста
</span>	
	document.body.<i>appendChild</i>(canvas); <span class="strcolor">//добавляем на страницу наш холст, можно начинать рисовать
</span>	<span class="strcolor">//делаем холст "ближе к нам", чтобы он перекрыл все остальное на странице
</span>	canvas.style.zIndex = 5;        
	canvas.style.position = <span class="strcolor">'absolute'</span>;
	canvas.style.top = <span class="strcolor">'0px'</span>;
	canvas.style.left = <span class="strcolor">'0px'</span>;
	
	canvas.onclick = <b>function</b> () { <span class="strcolor">//при клике удаляем его
</span>		document.body.<i>removeChild</i>(canvas);
	}
	context = canvas.<i>getContext</i>(<span class="strcolor">"2d"</span>);   <span class="strcolor">//Получить контекст рисования
</span>	context.fillStyle = <span class="strcolor">"#00AA00"</span>;       <span class="strcolor">//Стиль заливки - темно-зеленый
</span>	<span class="strcolor">//Рисуем прямоугольник на весь холст
</span>	<span class="strcolor">
</span>	context.<i>fillRect</i>(0, 0, canvas.width, canvas.height);
	context.strokeStyle = <span class="strcolor">'#ffffff'</span>;
	context.font = <span class="strcolor">'25px Geneva'</span>;
	text = <span class="strcolor">'Нажмите F11 для перехода в полноэкранный режим'</span>,
		firstTextY = <b>Math</b>.<i>round</i>(screen.height / 2);
	context.<i>strokeText</i>(text, <b>Math</b>.<i>round</i>(screen.width / 2 - context.<i>measureText</i>(text).width / 2),firstTextY);
	context.font = <span class="strcolor">'14px Geneva'</span>;
	context.fillStyle = <span class="strcolor">'#FFFF00'</span>;
	text = <span class="strcolor">'Кликните для закрытия этого зеленого фона!'</span>;
	context.<i>fillText</i>(text, <b>Math</b>.<i>round</i>(screen.width / 2 - context.<i>measureText</i>(text).width / 2),firstTextY + 30);
}
</pre>
<p>В отличии от предыдущего примера я использовал объект screen для того, чтобы получить ширину и высоту экрана и присвоил эти значения в свойства width и height элемента canvas. 
Еще одно отличие в том, что созданный элемент canvas добавляется как потомок не DOM элементу который показывает вывод приложения, а
элементу DOM документа body, в котором обычно находится все содержимое веб-страницы.</p>
<p>Строки 11 - 14 служат для того, чтобы поместить наш элемент canvas поверх всего остального содержимого страницы, интересующимся подробностями советую погуглить статьи и учебники по css.</p>
<p>Далее, назначаем для обработки клика по холсту анонимную функцию. В ней с помощью метода <i>removeChild</i> удаляем наш холст из документа.</p>
<p>Помимо прямоугольника, на этот раз зеленого, выводим на холсте две надписи разными стилями и цветами. Используем свойства контекста рисования font, strokeStyle и fillStyle для определения размера, семейства и цвета шрифта. После того, как эти параметры заданы, мы можем узнать ширину будущей надписи, хотя она еще не выведена. Для этого используется метод <i>measureText</i>, возвращающая объект, у которого есть свойство width, содержащее ширину текста. Сам текст выводится с помощью методов strokeText и fillText, второй и третий аргументы методов определяют x и y координаты левого верхнего угла надписей.</p>
<p>Я решил не ограничивать себя и использовать весь экран при решении примеров, связанных с графикой. Поэтому стоит оформить этот код в виде функции, так как он сравнительно объемен. При решении примера в коде будет только вызов функции <u>createFullScreenContext</u>.</p>
<div class="ainfo">Конечно, fullScreen здесь достаточно условен, так как пользователь будет вынужден переводить браузер в полноэкранный режим "вручную" нажимая F11, но перевести окно браузера в полноэкранный режим с помощью одного только JavaScript если я не ошибаюсь, нельзя.</div>
<pre>
<b>function</b> <u>createCanvasExample</u>() {
	<span class="strcolor">"use strict"</span>
	<b>function</b> <u>createFullScreenContext</u>(color, parentElement, zIndex) {
		<b>if</b> (!zIndex) {
			zIndex = 5;  <span class="strcolor">//значение по умолчанию
</span>		}
		<b>if</b> (!color) {     <span class="strcolor">//Стиль заливки по умолчанию - темно-зеленый
</span>			color = <span class="strcolor">'#00AA00'</span>;
		}
		<b>if</b> (!parentElement) {
			parentElement = document.body;  <span class="strcolor">//значение по умолчанию
</span>		}
	    <b>var</b> canvas = document.<i>createElement</i>(<span class="strcolor">'canvas'</span>),       <span class="strcolor">//Создали "холст"
</span>		    context,
		    i, firstTextY, text, sz;
	        canvas.width  = screen.width;               <span class="strcolor">//ширина холста
</span>	        canvas.height = screen.height;              <span class="strcolor">//высота холста
</span>	
		parentElement.<i>appendChild</i>(canvas); <span class="strcolor">//добавляем на страницу наш холст, можно начинать рисовать
</span>		<span class="strcolor">//делаем холст "ближе к нам", чтобы он перекрыл все остальное на странице
</span>		canvas.style.zIndex = zIndex;
		canvas.style.position = <span class="strcolor">'absolute'</span>;
		canvas.style.top = <span class="strcolor">'0px'</span>;
		canvas.style.left = <span class="strcolor">'0px'</span>;
	
		context = canvas.<i>getContext</i>(<span class="strcolor">"2d"</span>);   <span class="strcolor">//Получить контекст рисования
</span>		context.fillStyle = color;  
		<span class="strcolor">//Рисуем прямоугольник на весь холст
</span>		context.<i>fillRect</i>(0, 0, canvas.width, canvas.height);
		<b>return</b> {context:context, canvas:canvas};
	}
	<b>var</b> _2d = <u>createFullScreenContext</u>();
	_2d.canvas.onclick = <b>function</b>() {
		document.body.<i>removeChild</i>(_2d.canvas);
	}
}
</pre>
<p>Функция <u>createFullScreenContext</u> может принимать три необязательных параметра: цвет заливки, DOM элемент к которому будет добавлен холст и zIndex на случай, если вдруг понадобится поместить очередной холст "над предыдущим". Впрочем, я постараюсь обходиться одним холстом.
Функция <u>createFullScreenContext</u> возвращает объект из двух свойств - в первом контекст рисования, во втором холст, чтобы его можно было удалить. Код, удаляющий холст при клике мышью я по понятным причинам вынес из функции: вряд ли понадобится удалять холст именно таким образом как сейчас, при клике в произвольной точке.
</p>
<p>На этом подготовку к решению примера по 2D графике можно было бы считать законченной, но это не совсем так. Почему, станет понятно после прочтения текста задания.</p>
<h4 id="first_task">Первая задача</h4>
<p>Создать окно в рамке  на фоне, заполненном псевдографическим символом #178 зеленого цвета, с текстом из файла.
По клавишам управления курсором выполнять скроллинг текста в окне на одну строку вверх или вниз.</p>
<h4 id="pseudofile">Замена текстовым файлам</h4>
<p>Здесь у нас небольшая загвоздка: браузерный JavaScript не может работать с текстом из файла, расположенного на вашем компьютере.
Однако, некоторая альтернатива у нас есть. Мы не можем с помощью браузерного JavaScript сохранить текст в любом файле на нашем жестком диске (или прочитать из него текст), но мы можем сохранить его в локальном хранилище браузера. 
Современные браузеры позволяют хранить до десяти мегабайт для каждого сайта в хранилище, Internet Explorer до пяти мегабайт.
Доступ к хранилищу возможен через объект localStorage. Используя метод объекта <i>setItem</i> можно сохранить текст под определенным именем, это вобщем-то напоминает работу с текстовыми файлами.
Существенная разница заключается в том, что доступ к содержимому такого "файла" возможен только из того браузера, в котором вы его создаете: если вы сохранили текст сказки "Хоббит" используя localStorage.<i>setItem</i> в InternetExplorer, то вызвав localStorage.<i>getItem</i> в Firefox вы не сможете его прочитать. Вы не можете копировать этот "файл", в общем замена настоящим файлам слабоватая, но вполне подходящая для нашей задачи.</p>
<p>Пример создания "псевдофайла" опишу в прокомментированом фрагменте кода далее, добавив при необходимости более развернутые комментарии после описания.</p>
<pre>
<b>function</b> pseudoFileExample() {
	<b>var</b> appConsole = document.<i>getElementById</i>(<span class="strcolor">'console'</span>), <span class="strcolor">//наше "окошко" вывода приложения
</span>		textInput  = document.<i>createElement</i>(<span class="strcolor">'textarea'</span>), <span class="strcolor">//Создали элемент для ввода многострочного текста
</span>		nameInput  = document.<i>createElement</i>(<span class="strcolor">'input'</span>),    <span class="strcolor">//Создали элемент для ввода однострочного текста
</span>		saveButton = document.<i>createElement</i>(<span class="strcolor">'button'</span>);   <span class="strcolor">//Создали кнопку "Сохранить"
</span>		saveButtonWrap = document.<i>createElement</i>(<span class="strcolor">'p'</span>);    <span class="strcolor">//Создали обертку для кнопки "Сохранить"
</span>	textInput.style = <span class="strcolor">"width:99%; resize:none; height:440px;"</span>; <span class="strcolor">//Размеры поля ввода
</span>	nameInput.style = <span class="strcolor">"width:99%"</span>;
	saveButtonWrap.style = <span class="strcolor">"text-align:right;"</span>;          <span class="strcolor">//Кнопка будет смещена к правому краю
</span>	saveButton.innerHTML = <span class="strcolor">"Сохранить"</span>;      <span class="strcolor">//Написали на ней Сохранить
</span>	appConsole.innerHTML = <span class="strcolor">''</span>;               <span class="strcolor">// Очистили DOM элемент, в который будем добавлять наши элементы ввода
</span>	<span class="strcolor">//Добавили наши элементы
</span>	appConsole.<i>appendChild</i>(textInput);       
	appConsole.<i>appendChild</i>(nameInput);
	appConsole.<i>appendChild</i>(saveButtonWrap); 
	saveButtonWrap.<i>appendChild</i>(saveButton); <span class="strcolor">//Кнопку добавили в "обертку", чтобы выровнять ее по правому краю
</span>	
	<span class="strcolor">//пользовательский интерфейс готов, добавим логики
</span>	saveButton.onclick = <b>function</b>() {
		<b>if</b> (nameInput.value &amp;&amp; textInput.value) {
			localStorage.<i>setItem</i>(nameInput.value, textInput.value);
		}
	}
}
</pre>
<p>Прежде, чем сохранить какой-то текст в такой "файл", надо позаботиться о том, чтобы можно было ввести этот текст. К счастью, браузерный JavaScript позволяет создавать на веб-странице всевозможные элементы ввода значений, среди них многострочные и однострочные поля ввода текста и кнопки. С помощью метода <i>createElement</i> я создаю эти элементы, а также создаю элемент- контейнер (с помощью кода <i>createElement(<span class="strcolor">'p'</span>)</i>). 
Он мне нужен для того, чтобы сместить кнопку к правому краю, это привычно большинству пользователей компьютеров. </p>
<p>Далее я использую свойство (атрибут) style созданных элементов для того, чтобы задать им размер. 
Для многострочного поля ввода текста я задал ширину 99% и высоту 420 пикселей, а заодно запретил изменять размеры поля пользователю. Я использовал правила CSS, если вас интересуют все возможности, которые можно получить используя атрибут style, можете воспользоваться поиском и найти себе подходящий справочник по CSS3 или книгу.</p>
<p>Обычно кнопки в подобных формах ввода информации расположены по правому краю. Я использую правило CSS "text-align:right" чтобы содержимое контейнера p, на который ссылается переменная saveButtonWrapper было выровнено по правому краю.</p>
<p>Вас может удивить, что свойство innerHTML используется у одного элемента для создания на нем надписи, а у второго для удаления всего содержимого. Дело в том, что в результате работы метода <i>appendChild</i>, применённого к тому или иному DOM элементу внутри него создается текстовое содержимое - HTML код. Например, вместо:
<pre class="no_lines">
	<b>var</b> saveButtonWrap = document.<i>createElement</i>(<span class="strcolor">'p'</span>);
	saveButtonWrap.style = <span class="strcolor">"text-align:right;"</span>;
	<span class="strcolor">// ...</span>
	appConsole.<i>appendChild</i>(saveButtonWrap);
</pre><p>можно было бы написать:</p>
<pre class="no_lines">
	appConsole.innerHTML += <span class="strcolor">'&lt;p style="text-align:right"&gt;&lt;/p&gt;'</span>;</pre>
<p>я не делаю этого, так как не хочу касаться темы HTML, чтобы не "распыляться" больше чем необходимо. Однако, обратите внимание, что если вы изучите HTML, вы можете писать более коротко!</p>
<p>После того, как содержимое консоли вывода приложения очищено, туда добавляются созданные элементы ввода с помощью метода <i>appendChild</i>.</p>
<p>В заключении мы можем видеть код обработки события клика по кнопке "Сохранить". Если задано имя "файла" и есть, что в него сохранять, происходит сохранение. "Имена" лучше давать без пробелов и латиницей, проверку на соблюдение этих условий можете добавить сами. Также, вам может не хватать меток полей ввода, где имя файла, где его содержимое, их можно добавить по аналогии с "оберткой" для кнопки "Сохранить".</p>
<p>Сохраните какой-нибудь текст с именем my_content. Будем использовать его при решении задачи.</p>
<h4 id="first_task_decision">Решение первой задачи</h4>
<p>Напомню текст задачи: создать окно в рамке на фоне, заполненном псевдографическим символом #178 зеленого цвета, с текстом из файла. По клавишам управления курсором выполнять скроллинг текста в окне на одну строку вверх или вниз.</p>
<div class="ainfo">Если вы уже успели заинтересоватсья html и css, вам возможно уже известно, что эту задачу можно легко и просто решить их средствами. Однако, здесь меня интересует программирование графики, поэтому я решу эту задачу так, словно никакого html и css не сущетствует.</div>

<pre>
<b>function</b> <u title="Пример реализации окна на холсте">window2DExample</u>() {
	<b>var</b> _2d = <u>createFullScreenContext</u>(<span class="strcolor">"#FFFFFF"</span>), 
		WIDTH = 640,       <span class="strcolor">//ширина окна с текстом</span>
		HEIGHT = 480,      <span class="strcolor">//высота окна с текстом</span>
		TOP_BORDER_H = 30, <span class="strcolor">//высота верхней рамки окна с текстом</span>
		BORDER = 5,        <span class="strcolor">//толщина рамки окна с текстом</span>
		BORDER_COLOR = <span class="strcolor">"#AA0000"</span>, <span class="strcolor">//цвет рамки окна</span>
		WND_BG_COLOR = <span class="strcolor">"#00F0F0"</span>, <span class="strcolor">//цвет фона окна</span>
		ctx = _2d.context,   <span class="strcolor">//контекст рисования</span>
		BG_COLOR =  <span class="strcolor">"#00AA00"</span>,    <span class="strcolor">//фон холста</span>
		ch = <b>String</b>.fromCharCode(178),<span class="strcolor">//символ, который используется в качестве фона</span>
		SC_WIDTH = screen.width,             <span class="strcolor">//ширина экрана</span>
		SC_HEIGHT = screen.height,			 <span class="strcolor">//высота экрана</span>
		s, i, j, k, y, verticalLimit,		 
		textFontSize = 12,					 <span class="strcolor">//размер шрифта в пикселях</span>
		verticalStart = textFontSize;
		
	<span class="strcolor">//Залить фон
</span>	<b>function</b> <u>drawBg</u>() {
		ctx.fillStyle = BG_COLOR;	
		ctx.font = <span class="strcolor">"12px Geneva"</span>;
		<span class="strcolor">//определить, сколько символов поместится в строке в ширину
</span>		s = <b>new</b> <b>Array</b>(101).<i>join</i>(ch);
		<b>while</b> (ctx.<i>measureText</i>(s).width < SC_WIDTH) {
			s += ch;
		}
		<span class="strcolor">//сколько надо строк, чтобы залить фон символом
</span>		verticalLimit = <b>Math</b>.<i>ceil</i>(SC_HEIGHT / textFontSize), y = textFontSize;
		<span class="strcolor">//залить фон символом
</span>		<b>while</b> (y < SC_WIDTH) {
			ctx.fillText(s, 0, y);
			y += textFontSize;
		}
	}
	drawBg();
	<span class="strcolor">//вывести текст
</span>	<b>function</b> <u>_drawText</u>(ctx, _x, _y, _w, _h) {
		<b>var</b> text = localStorage.getItem(<span class="strcolor">'my_content'</span>), caretX = _x, caretY = verticalStart + _y, needNextStr = <b>false</b>;
		if (!text) {
			text = <span class="strcolor">'Надо сохранить в хранилище текст с именем "my_content" воспользовавшись примером "Замена текстовым файлам"'</span>;
		}
		ctx.fillStyle = <span class="strcolor">"#FF0000"</span>;	
		ctx.font = <span class="strcolor">"12px Geneva"</span>;
		s = <span class="strcolor">''</span>;
		<span class="strcolor">//выводим текст
</span>		<b>for</b> (i = 0; i < text.length; i++) {
			s += text.<i>charAt</i>(i);
			<b>if</b> (ctx.<i>measureText</i>(s).width > _w || text.<i>charAt</i>(i) == <span class="strcolor">"\n"</span>) {
				s = s.<i>substring</i>(0, s.length - 1);
				<span class="strcolor">//alert(s);
</span>				<b>if</b> (caretY > _y) {
					ctx.fillText(s, caretX, caretY);
				}
				caretY += textFontSize;
				s = text.<i>charAt</i>(i);
			}
			<b>if</b> (caretY > _y + _h) {
				<span class="strcolor">//alert(i);
</span>				<b>return</b>;
			}
		}
		<b>if</b> (s.length && caretY > _y) {
			ctx.fillText(s, caretX, caretY);
		}
	}
	<span class="strcolor">//отрисовать "окно"
</span>	<b>function</b> <u>drawWnd</u>() {
		<b>var</b> w = <b>Math</b>.<i>round</i>( (SC_WIDTH - WIDTH) / 2), h = <b>Math</b>.<i>round</i>( (SC_HEIGHT - HEIGHT) / 2);
		ctx.fillStyle = BORDER_COLOR;
		ctx.<i>fillRect</i>(w, h, WIDTH, HEIGHT );
		ctx.fillStyle = WND_BG_COLOR;
		ctx.<i>fillRect</i>(w + BORDER, h +  TOP_BORDER_H, WIDTH - 2 * BORDER, HEIGHT - TOP_BORDER_H - BORDER);
		_drawText(ctx, w + BORDER, h +  TOP_BORDER_H, WIDTH - 2 * BORDER, HEIGHT - TOP_BORDER_H - BORDER);
	}
	drawWnd();
	
	<b>function</b> <u>moveText</u>(event) {
		<b>if</b> (event.keyCode != 38 && event.keyCode != 40) {
			<b>return</b> <b>true</b>;
		}
		<b>if</b> (event.keyCode == 38) {
			verticalStart += textFontSize;
		}
		<b>if</b> (event.keyCode == 40) {
			verticalStart -= textFontSize;
		}
		ctx.fillStyle  = <span class="strcolor">"#ffffff"</span>;
		ctx.<i>fillRect</i>(0, 0, SC_WIDTH, SC_HEIGHT);
		<u>drawBg</u>();
		<u>drawWnd</u>();
		<b>return</b> <b>false</b>;
	}
	
	document.body.onkeydown = moveText;
	
	_2d.canvas.onclick = <b>function</b>() {
		document.body.<i>removeChild</i>(_2d.canvas);
		document.body.onkeydown = <b>null</b>;
	}
	<span class="strcolor">
	//==================================================================
	</span>
	<span class="strcolor">//Вспомогательная функция для создания холста на весь экран</span>
	<b>function</b> <u>createFullScreenContext</u>(color, parentElement, zIndex) {
		<b>if</b> (!zIndex) {
			zIndex = 5;  <span class="strcolor">//значение по умолчанию
</span>		}
		<b>if</b> (!color) {     <span class="strcolor">//Стиль заливки по умолчанию - темно-зеленый
</span>			color = <span class="strcolor">'#00AA00'</span>;
		}
		<b>if</b> (!parentElement) {
			parentElement = document.body;  <span class="strcolor">//значение по умолчанию
</span>		}
	    <b>var</b> canvas = document.<i>createElement</i>(<span class="strcolor">'canvas'</span>),       <span class="strcolor">//Создали "холст"
</span>		    context,
		    i, firstTextY, text, sz;
	        canvas.width  = screen.width;               <span class="strcolor">//ширина холста
</span>	        canvas.height = screen.height;              <span class="strcolor">//высота холста
</span>	
		parentElement.<i>appendChild</i>(canvas); <span class="strcolor">//добавляем на страницу наш холст, можно начинать рисовать
</span>		<span class="strcolor">//делаем холст "ближе к нам", чтобы он перекрыл все остальное на странице
</span>		canvas.style.zIndex = zIndex;
		canvas.style.position = <span class="strcolor">'fixed'</span>;
		canvas.style.top = <span class="strcolor">'0px'</span>;
		canvas.style.left = <span class="strcolor">'0px'</span>;
	
		context = canvas.<i>getContext</i>(<span class="strcolor">"2d"</span>);   <span class="strcolor">//Получить контекст рисования
</span>		context.fillStyle = color;  
		<span class="strcolor">//Рисуем прямоугольник на весь холст
</span>		context.<i>fillRect</i>(0, 0, canvas.width, canvas.height);
		<b>return</b> {context:context, canvas:canvas};
	}
	<span class="strcolor">
	//==================================================================
	</span>
}
</pre>
<p>В общем-то при решении задачи не использовано ничего нового. Для отрисовки окна я использовал те же функции что и в примерах этой статьи выше - <i>fillRect</i> и <i>fillText</i>. Для получения текста, выводимого в окне я использую метод localStorage.</i>getItem</i>. Чтобы обеспечить прокрутку текста на одну строку я назначил обработку событий нажатия клавиш клавиатуры "вверх" и "вниз" функции <u>moveText</u>, которая перерисовывает все после кажлого нажатия кнопки, смещая текст. Я использую переменную verticalStart чтобы запоминать смещение от начала текста, и я вывожу его только когда очередная строка займет место ниже чем область окна. 
Функция moveText возвращает false если нажата кнопка "вверх" или "вниз". Это сделано для того, чтобы браузер не прокручивал полосу прокрутки справа, которую вы возможно видите, если работаете в firefox. Но так как необходимо, чтобы браузер нормально реагировал на нажатие этих клавиш после того, как приложение закрыто, в обработке клика на холсте я присваиваю <b>null</b> заместо функции <u>moveText</u>. Можете попробовать не делать этого - браузер перестанет реагировать на нажатия клавишь "вверх" и "вниз" пока вы не обновите страницу.</p>
<?=QuickStartHandler::prevnext('function_tasks', 'snake');?>
