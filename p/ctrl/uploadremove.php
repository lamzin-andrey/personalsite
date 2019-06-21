<?php
class UploadRemove {
	public function __construct() {
		$id = ireq('id');
		$src = req('src');
		$uid = Auth::getUid();
		if ($uid) {
			$row = false;
			if ($id) {
				$row = dbrow("SELECT id, path, user_id FROM photos WHERE id = {$id}");
			} else {
				$row = dbrow("SELECT id, path, user_id FROM photos WHERE path = '{$src}'");
			}
			if(is_array($row) && $row['user_id'] == $uid) {
				query("DELETE FROM photos WHERE id = '{$row['id']}'", $n, $a);
				if ($a && $row['path']) {
					if (file_exists(DOC_ROOT . $row['path'])) {
						@unlink(DOC_ROOT . $row['path']);
						json_ok('s', 1);
						return;
					}
					json_ok('s', 2);
					return;
				}
				
			}
		}
		json_error();
		return;
	}
}
