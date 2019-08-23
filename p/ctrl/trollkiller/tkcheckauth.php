<?php
require_once __DIR__ . '/openapp.php';
/**
 * @class TrollKillerLogin  - Проверка, залогинен ли в TrollKiller
*/
class TrollKillerCheckAuth extends OpenApp {
	public $uid = 0;
	
	public function __construct() {
		parent::__construct();
		$uid = Auth::getUid();
		
		if ($uid) {
			if (ireq('p') == 1) {
				$aRelList = $this->_loadRelList($uid);
				json_ok('msg', l('Login success'), 'uid', $uid, 'l', $aRelList);
			}
			json_ok('msg', l('Login success'), 'uid', $uid);
		}
		json_error('msg', l('User not found'));
	}
	/**
	 * @description Получить список троллейбусов, с которыми связан авторизованный пользователь
	 * @param int $uid
	 * @return array
	*/
	private function _loadRelList(int $uid) : array
	{
		$sql = 'SELECT tbr.subject_id, t.uid, COUNT(t.id) AS cnt, tui.name, tui.imgpath, u.name AS dname, u.surname
			FROM trollkiller_banlists_rel AS tbr
			
			LEFT JOIN trollkiller AS t  ON tbr.subject_id = t.uid
			
			LEFT JOIN trollkiller_userinfo AS tui ON tui.uid = t.uid
			
			LEFT JOIN ausers AS u ON u.id = t.uid
			
			WHERE tbr.client_id = ' . $uid . '
			
			GROUP BY uid ORDER BY cnt DESC ';
			
		return query($sql);
	}
}
