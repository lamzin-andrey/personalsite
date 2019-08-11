<?php


class Logout extends BaseApp {
	
	
	public function __construct() {
		$url = '/';
		if (Auth::getUid()) {
			if (Auth::isAdmin()) {
				$url = '/p/';
			}
		}
		Auth::logout();
		utils_302($url);
	}
}
