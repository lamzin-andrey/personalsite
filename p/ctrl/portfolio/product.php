<?php
require __DIR__ . '/../adminauthjson.php';
/**
 * @class Product  - получение данных работы для редактирования
*/
class Product extends AdminAuthJson {
	public $uid = 0;
	
	public function __construct() {
		$this->table = 'portfolio';
		parent::__construct();
		$this->ireq('id');
		$product = $this->rec();
		if ($product['logo'] > 0) {
			$product['logo'] = dbvalue("SELECT path FROM logos WHERE id = {$product['logo']}");
		} else {
			$product['logo'] = DEFAULT_ARTICLE_LOGO;
		}
		if (!$product['logo']) {
			$product['logo'] = DEFAULT_ARTICLE_LOGO;
		}
		if ($product['og_image'] == '') {
			$product['og_image'] = DEFAULT_ARTICLE_LOGO;
		}
		json_ok_arr($product);
	}
}
