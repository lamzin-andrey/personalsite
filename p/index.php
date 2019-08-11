<?php

require_once __DIR__ . '/lang/ru.php';
require_once __DIR__ . '/../q/q/baseapp.php';
require_once __DIR__ . '/..//q/q/auth.php';
require_once __DIR__ . '/../q/q/bootstrapformview.php';
require_once __DIR__ . '/../q/q/formview.php';
$GLOBALS['sLangDir'] =  __DIR__ . '/lang';
require_once __DIR__ . '/../q/q/lang.php';
//require_once __DIR__ . '/../q/q/.php';

require_once __DIR__ . '/route.php';
//require_once __DIR__ . '/capp.php';
//require_once $handler;
require_once $route->master;
