<?php
require_once __DIR__ . '/openapp.php';
/**
 * @class TrollKillerSearch - Поиск пользователей-троллейбусов по a_mail_id ли по нику
*/
class TrollKillerSearch extends OpenApp {
	public $uid = 0;
	public function __construct() {
		parent::__construct();
		$rows = [];
		$this->table = 'trollkiller';
		
		$s = $this->treq('t');
		$n = intval($s);
		if (!$n && !$s) {
			json_ok('list', $rows);
		}
		$sWhere = '';
		$uid = intval(Auth::getUid());
		$sql = 'SELECT tui.uid AS subject_id, tui.name, tui.imgpath, u.name AS dname, u.surname, COUNT(t.id) AS cnt,
							tbr.id AS is_subsrcribed

FROM trollkiller_userinfo AS tui

LEFT JOIN ausers AS u ON u.id = tui.uid
LEFT JOIN trollkiller AS t ON t.uid = tui.uid
LEFT JOIN trollkiller_banlists_rel AS tbr ON tbr.client_id = ' . $uid . ' AND tbr.subject_id = tui.uid

{WHERE}

GROUP BY t.uid

ORDER BY cnt DESC';
		
		
		if ($n > 0) {
			$sWhere = 'WHERE tui.a_mail_id = ' . $n;
		} else {
			$a = explode(' ', $s);
			$aB = [];
			foreach ($a as $ss) {
				$sq = trim($ss);
				if ($sq) {
					$aB[] = $sq;
				}
			}
			$s = join(' ', $aB);
			$sWhere = 'WHERE tui.name LIKE(\'%' . $s . '%\') OR u.name LIKE(\'%' . $s . '%\') OR u.surname LIKE(\'%' . $s . '%\')';
			foreach ($aB as $s) {
				$sWhere .=  'OR u.name LIKE(\'%' . $s . '%\')';
				$sWhere .=  'OR u.surname LIKE(\'% ' . $s . '%\')';
				$sWhere .=  'OR tui.name LIKE(\'%' . $s . '%\')';
			}
		}
		$sql = str_replace('{WHERE}', $sWhere, $sql);
		
		
		
		global $dberror;
		$rows = query($sql);
		json_ok('list', $rows, 'dberror', $dberror);
	}
}
