<?php
include __DIR__ . '/../adminauthjson.php';
include __DIR__ . '/classes/portfoliocompiler.php';
include __DIR__ . '/classes/portfoliolistcompiler.php';

class ProudctPost extends AdminAuthJson {
	/** @property string */
	//public $ = '';
	
	
	public function __construct() {
		parent::__construct();
		$this->table = 'portfolio';
		
		$this->treq('title');
		$this->treq('body', 'content_block');
		$this->treq('shortdesc');
		$this->treq('url');
		$this->treq('heading');
		$this->treq('defaultLogo', 'logo');
		$this->ireq('category', 'category_id');
		$this->ireq('hours');
		$this->treq('custom_download_content');
		
		$this->treq('description');
		$this->treq('keywords');
		$this->treq('og_description');
		$this->treq('og_title');
		$this->treq('og_image');
		$this->tsreq('right_menu_heading');
		$this->tsreq('right_menu_secondary_text');
		$this->treq('sha256');
		$this->treq('product_file_textlink');
		$this->treq('outerLink', 'outer_link');
		$this->treq('outerLinkText', 'outer_link_text');
		$this->treq('productFile', 'product_file');
		$this->treq('relatedArticles');
		$this->breq('hasSelfSection', 'has_self_section');
		$this->breq('dontCreatePage', 'dont_create_page');
		$this->breq('hideFromProductlist', 'hide_from_productlist');
		
		
		$errors = [];
		
		
		
		if ($this->_validate($errors)) {
			$id = ireq('id');
			$this->_setLogo();
			$this->_setUrl();
			$sql = '';
			if ($id) {
				$sql = $this->updateQuery(('id = ' . $id), ['updated_at' => now()]);
			} else {
				$sql = $this->insertQuery([]);
			}
			
			$newId = query($sql);
			if (!$id) {
				$id = $newId;
				$sDate = now();
			} else {
				$sDate = dbvalue("SELECT created_at FROM {$this->table} WHERE id = {$id}");
				if (!$sDate) {
					$sDate = now();
				}
			}
			$this->_saveRelatedArticles($id);
			
			$comiErr = '';
			
			//product page
			if (!$this->has_self_section && !$this->dont_create_page) {
				
				$oCompiler = new PortfolioCompiler();
				$oCompiler->title = $this->title;
				$oCompiler->nProductId = $id;
				$oCompiler->url = $this->url;
				$oCompiler->content = $this->content_block;
				$url = $this->url . 'index.html';
				$oCompiler->outputFile = DOC_ROOT . $url;
				$oCompiler->canonicalUrl = $this->url;
				$oCompiler->heading = $this->heading;
				$oCompiler->description = $this->description;
				$oCompiler->keywords = $this->keywords;
				$oCompiler->og_title = $this->og_title;
				$oCompiler->og_description = $this->og_description;
				$oCompiler->og_image = $this->og_image;
				$oCompiler->nCategory = $this->category_id;//For bc
				$oCompiler->compile();
				//$comiErr = $oCompiler->emsg;
			}
			
			
			//main portfolio list
			$oCompiler = new PortfoliolistCompiler();
			$oCompiler->compileMainList();
			
			//portfolio section list - это глючит, не все списки обновляются.
			$oCompiler->compileLevelsLists($this->category_id);
			
			json_ok('id', $id, 'comiErr', $comiErr);
		}
		json_error_arr(['errors' => $errors]);
	}
	/**
	 * @description Сохранить данные в таблице portfolio_pages set $this->aRelatedArticles //TODO define it
	 * TODO тут же создавать фрагмент для вставки в страницу всех работ портфолио
	*/
	private function _saveRelatedArticles(int $productId)
	{
		$this->aRelatedArticles = json_decode(utils_utf8($this->relatedArticles));
		
		if (is_array($this->aRelatedArticles)) {
			$table = 'portfolio_pages';
			$aExistsPages = query("SELECT id, page_id FROM {$table} WHERE `work_id` = {$productId}");
			$aMap = array_column($aExistsPages, 'id', 'page_id');
			foreach ($this->aRelatedArticles as $oArticleData) {
				$artId = intval($oArticleData->id);
				if (isset($aMap[$artId])) {
					unset($aMap[$artId]);
				}
				query("INSERT INTO portfolio_pages (`work_id`, `page_id`) VALUES({$productId}, {$artId}) ON DUPLICATE KEY UPDATE `page_id`=`page_id`");
			}
			if (count($aMap)) {
				$sIdList = join(',', $aMap);
				query('DELETE FROM ' . $table . ' WHERE id IN(' . $sIdList . ')');
			}
		}
	}
	/**
	 * @description Если установлен "Не создавать отдельную страницу" (dontCreatePage) очищает поле url
	*/
	private function _setUrl() {
		if ($this->dont_create_page) {
			$this->url = $this->request['url'] = '';
		}
	}
	/**
	 * @description Записывает в таблицу logos данные файла, если его там нет. Заменяет $this->logo с пути на logos.id 
	*/
	private function _setLogo()
	{
		$s = $this->logo;
		if (!file_exists(DOC_ROOT . '/' . $s) && strpos($s, 'http') !== 0) {
			return;
		}
		$exId = dbvalue("SELECT id FROM logos WHERE path='{$s}'");
		if ($exId) {
			$this->request['logo'] = $this->logo = $exId;
			return;
		}
		$srcname = treq('srcFileName');
		$this->request['logo'] = $this->logo = query("INSERT INTO logos (`path`, `name`, `is_deleted`) VALUES ('{$s}', '{$srcname}', '0')");
		
		if ($this->logo) {
			query("UPDATE logos SET delta = id WHERE id = {$this->logo}");
		}
	}
	/**
	 * @description Проверка, заполнены ли поля
	*/
	private function _validate(array &$errors) : bool
	{
		//title
		$this->_setRequiredError('title', 'Title', $errors);
		
		//shortdesc
		$this->_setRequiredError('shortdesc', 'Shortdesc', $errors);
		
		//heading
		$this->_setRequiredError('heading', 'Heading Article', $errors);
		
		//content_block
		$this->_setRequiredError('shortdesc', 'Shortdesc', $errors);
		
		//category
		if (!$this->category_id ) {
			$errors['category_id'] = l('field-required', 0, l('Category', 1));
		}
		if (count($errors)) {
			return false;
		}
		return true;
	}
	/**
	 * @description Установить ошибку Поле обязательно
	 * @param string $varname
	 * @param string $localizeKey
	 * @param array &$errors
	*/

	private function _setRequiredError(string $varname, string $localizeKey, array &$errors)
	{
		if (!strlen($this->$varname) ) {
			$errors[$varname] = l('field-required', 0, l($localizeKey, 1));
		}
	}
	public function breq($key, $field = '', $varname = 'REQUEST')
	{
		$field = $field ? $field : $key;
		$s = $this->tsreq($key, $field, $varname);
		$this->$field = ($s == 'true' ? true : false);
		if ($this->$field) {
			$this->request[$field] = $this->$field = 1;
		} else {
			$this->request[$field] = $this->$field = 0;
		}
		return $this->$field;
	}
}
