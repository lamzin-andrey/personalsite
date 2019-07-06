Назначение
 Сделать поддержку атрибута v-validators для полей ввода
 input[type=text]
 input[type=number]
 input[type=password]
 input[type=email]
 
Работает со стандартной версткой полей ввода bootstrap 4.2.1 вида

<div class="col-md-6 mb-3">
  <label for="iPassword">Password</label>
  <input type="password" class="form-control is-invalid" id="iPassword" placeholder="Enter password" required>
  <div class="invalid-feedback">
	Password must containts numbers and letters different case: upper and lower.
  </div>
</div>


Для указанного примера программист может добавить 
атрибут v-b421validators="'password,required'" полю input[type=password].


Директива перехватывает событие submit формы, в которой содержится поле ввода и если введенное значение не валидно,
 добавляет класс is-invalid и заполняет div.invalid-feedback локализованым сообщением. 
(Функцию локализации можно передать в конструктор например так: oB421Validator = new B421Validator(t);)

Директива перехватывает событие input поля ввода, удаляет is-invalid при начале ввода и очищает div.invalid-feedback.
Если в процессе ввода введено валидное значение, добавляет полю ввода класс is-valid.

Для использования другой верстки (или других версий бутстрап) планируется 
определять директивы с другими именами.

(Вероятно, часть логики куда-то вынести, чтобы 
наследоваться от какого-то базового класса и перегружать методы, ответственные за отображение полей формы.
)

-------------------------------------------------------
Как правильно реализовать на vue js модуль или плагин, реализующий некоторое "стандарное" поведение для полей ввода?

Здравствуйте! Пытаюсь изучать vuejs, для простых вещей всё довольно понятно.
Однако дошел до точки, когда захотелось автоматизировать валидацию полей ввода на формах.
Я написал ТЗ, которое легко могу реализовать не используя vue js, вот оно:

.

Но я совсем не понимаю, как это реализовать используя vue js.

Если бы речь шла о не универсальном решении, верстка выглядела бы так

<div class="col-md-6 mb-3">
  <label for="iPassword">Password</label>
  <input placeholder="$t('Enter password')" v-model="password" v-bind:class="passwordIsInvalid" type="password"  name="iPassword"  required>
  <div class="invalid-feedback">
	{{ passwordError }}
  </div>
</div>


и я использовал бы связанные поля для реализации нужного поведения.

Проблема в том, что при таком подходе для каждого поля ввода мне придётся создавать несколько переменных в компоненте vue 
, отвечающим за форму (в этом примере их три, passwordError, passwordIsInvalid, password)
и повторять это в каждом комипоненте. Это уныло.

Думаю, в vue js есть способ реализовать то, что написано в ТЗ, просто я его пока не вижу (документацию читаю тут https://ru.vuejs.org/v2/guide/index.html).
Можете подсказать, в какую сторону смотреть?
