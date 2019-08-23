<?php

require_once __DIR__ . '/openapp.php';
/**
 * @class TrollKillerLogout  - Лог-out из TrollKiller
*/
class TrollKillerLogout extends OpenApp {
	public function __construct() {
		Auth::logout();
		json_ok();
	}
}
