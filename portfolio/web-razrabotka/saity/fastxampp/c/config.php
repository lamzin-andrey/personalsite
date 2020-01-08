<?php

require_once __DIR__ . '/custom.php';

define('SUMMER_TIME', 0);
define('APP_CACHE_FOLDER', __dir__ . '/cache');
define('DB_DELTA_NOT_USE_TRIGGER', true);
define('DEV_MODE', false);



@date_default_timezone_set('Europe/Moscow');
