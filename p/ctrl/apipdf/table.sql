--
-- Структура таблицы `pdftotext_tasks`
--

CREATE TABLE `pdftotext_tasks` (
  `id` int(11) NOT NULL,
  `insurance_policy_request_id` int(11) DEFAULT NULL,
  `links` text DEFAULT NULL COMMENT 'Список ссылок на pdf, каждая на своей строке',
  `state` tinyint(4) DEFAULT NULL COMMENT '1 - ждет ообработки, 2 - обработано, 3 - отправлено'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `pdftotext_tasks`
--
ALTER TABLE `pdftotext_tasks`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `pdftotext_tasks`
--
ALTER TABLE `pdftotext_tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
