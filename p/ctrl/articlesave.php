<?php
include __DIR__ . '/adminauth.php';
include DOC_ROOT . '/q/q/cstaticpagescompiler.php';

class ArticlePost extends AdminAuth {
	
	
	/** @property string */
	//public $ = '';
	
	
	public function __construct() {
		parent::__construct();
		$this->table = 'pages';
		$this->treq('title');
		$this->treq('body', 'content_block');
		$this->treq('url');
		$this->treq('heading');
		$this->treq('defaultLogo', 'logo');
		$this->ireq('category');
		
		$errors = [];
		
		if ($this->_validate($errors)) {
			$id = ireq('id');
			$this->_setLogo();
			$sql = '';
			if ($id) {
				$sql = $this->updateQuery(('id = ' . $id), []);
			} else {
				$sql = $this->insertQuery([]);
			}
			
			$newId = query($sql);
			if (!$id) {
				$id = $newId;
			}
			//TODO call compile
			$oCompiler = new CStaticPagesCompiler(1, $this->url, $this->title, $this->heading, $this->content_block);
			$comiErr = $oCompiler->emsg;
			json_ok('id', $id, 'comiErr', $comiErr);
		}
		json_error_arr($errors);
	}
	/**
	 * @description Записывает в таблицу logos данные файла, если его там нет. Заменяет $this->logo с пути на logos.id 
	*/
	private function _setLogo()
	{
		$s = $this->logo;
		if (!file_exists(DOC_ROOT . '/' . $s)) {
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
	 * @description Проверка, заполнены ли поля TODO test it
	*/
	private function _validate(array &$errors) : bool
	{
		//title
		$this->_setRequiredError('title', 'Title', $errors);
		
		//heading
		$this->_setRequiredError('heading', 'Heading Article', $errors);
		
		//content_block
		$this->_setRequiredError('content_block', 'Article', $errors);
		
		//category
		if (!$this->category ) {
			$errors['category'] = l('field-required', l('Category'));
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
			$errors[$varname] = l('field-required', l($localizeKey));
		}
	}
}
