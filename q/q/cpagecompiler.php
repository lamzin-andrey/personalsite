<?php

class CPageCompiler {
	
	public $tpl = '';
	public $title = '';
	public $bc = '';
	public $heading = '';
	public $content = '';
	public $description = '';
	public $keywords = '';
	public $ogTitle = '';
	public $ogDescription = '';
	public $ogImage = '';
	public $displayDate = '';
	public $outputFile = '';
	public $rightMenu = '';
	/** @property string canonicalUrl  <link rel="canonical" href="scheme://host/$this->canonicalUrl"/>   */
	public $canonicalUrl = '';//CANONICAL_URL
	
	/** @property bool $bPreprocesContent = false if true $this->content надо обработать _preprocess перед сохранением */
	public $bPreprocesContent = false;
	
	public function __construct() {
		$this->displayDate = date('d.m.Y');
	}
	/**
	 * @description
	 * @return string
	*/

	public function compile($bSaveNow = true)
	{
		$s = file_get_contents($this->tpl);
		$s = str_replace('{TITLE}', $this->title, $s);
		$s = str_replace('{BC}', $this->bc, $s);
		$s = str_replace('{HEADING}', $this->heading, $s);
		
		$s = str_replace('{CONTENT}', $this->_preprocessContent(), $s);
		$s = str_replace('{DATEENG}', date('Y-m-d H:i:s'), $s);
		$s = str_replace('{DATERUS}', $this->displayDate, $s);
		$s = str_replace('{CANONICAL_URL}', $this->canonicalUrl, $s);
		$s = str_replace('{RIGHT_MENU}', $this->rightMenu, $s);
		$s = str_replace('{LIVEINTERNET}', $this->_getLiveinternetCounterHtml(), $s);
		
		if ($this->description) {
			$s = str_replace('<!--DESCRIPTION -->', '<meta name="description" content="' . $this->description . '">', $s);
		} else {
			$s = str_replace('<!--DESCRIPTION -->', '', $s);
		}
        if ($this->keywords) {
			$s = str_replace('<!--KEYWORDS -->', '<meta name="keywords" content="' . $this->keywords . '">', $s);
		} else {
			$s = str_replace('<!--KEYWORDS -->', '', $s);
		}
        
        if ($this->ogTitle) {
			$s = str_replace('<!--OGTITLE -->', '<meta property="og:title" content="' . $this->ogTitle . '">', $s);
		} else {
			$s = str_replace('<!--OGTITLE -->', '', $s);
		}
        if ($this->ogDescription) {
			$s = str_replace('<!--OGDESCRIPTION -->', '<meta property="og:description" content="' . $this->ogDescription . '">', $s);
		} else {
			$s = str_replace('<!--OGDESCRIPTION -->', '', $s);
		}
        if ($this->ogImage) {
			$s = str_replace('<!--OGIMAGE -->', '<meta property="og:image" content="' . $this->ogImage . '">', $s);
		} else {
			$s = str_replace('<!--OGIMAGE -->', '', $s);
		}
		
		if ($bSaveNow && $this->outputFile) {
			$this->_save($s);
		}
		
		return $s;
	}
	/**
	 * @description
	 * @param string $s
	*/
	protected function _save($s)
	{
		$sp = $this->outputFile . '.tmp';
		$a = explode('/', $sp);
		unset($a[count($a) - 1]);
		$sDir = join('/', $a);
		
		
		if (!file_exists($sDir)) {
			utils_createDir($sDir);
		}
		file_put_contents($sp, $s);
		rename($sp, $this->outputFile);
	}
	/**
	 * @description Учёт тегов [html] и [code]
	 * @return string
	*/
	protected function _preprocessContent()
	{
		if ($this->bPreprocesContent) {
			$this->_processHtmlcode();
			//$this->content = strip_tags($this->content, "<b><img><p><ul><li><a><br>");Это потом, для НЕадминов
			$this->content = str_replace("\r", '', $this->content);
			//$this->content = preg_replace('#onerror|onload\s?=\s?"[^"]*"#', "", $this->content);
			//$this->content = preg_replace("#onerror|onload\s?=\s?'[^']*'#", "", $this->content);
			$this->content = str_replace("\n", '</p><p>', $this->content);
			$this->content = '<p>' . $this->content . '</p>';
			//$this->content = str_replace("  ", "<br/>", $this->content);
			$this->content = str_replace([md5('newline'), '[html]', '[/html]', md5('monosp'), md5("\t"), '[code]', '[/code]'], ["\n", '', '', ' ', "\t", '', ''], $this->content);
		}
		return $this->content;
	}
	/**
	 * @description 
	*/
	protected  function _processHtmlcode()
    {
		$s = $this->content;
    	$inTag = false;
    	$inCode = false;
    	$q = '';
    	$nQuoteCounter = 0;
    	for ($i = 0; $i < strlen($s); $i++) {
    		if ($s[$i] == '[' && strpos($s, '[html]', $i) === $i) {
    			$inTag = 1;
    		}
    		if ($s[$i] == '[' && strpos($s, '[/html]', $i) === $i) {
    			$inTag = 0;
    		}
    		
    		if ($s[$i] == '[' && strpos($s, '[code]', $i) === $i) {
    			$inCode = 1;
    		}
    		if ($s[$i] == '[' && strpos($s, '[/code]', $i) === $i) {
    			$inCode = 0;
    		}
    		
    		if ($inTag && $s[$i] == "\n") {
    			$q .= md5('newline');
    		} elseif($inTag && $s[$i] == ' ') {
    			$q .= md5('monosp');
    		} elseif($inTag && $s[$i] == "\t") {
    			$q .= md5("\t");
    		} else {
				
				if (!$inTag && $s[$i] == '"') {
					if ($nQuoteCounter == 0) {
						$nQuoteCounter++;
						$q .= '&laquo;';
					} else {
						$nQuoteCounter--;
						$q .= '&raquo;';
					}
				} else {
					if($inCode && $s[$i] == '<') {
						$q .= '&lt;';
					} else if($inCode && $s[$i] == '>') {
						$q .= '&gt;';
					} else {
						$q .= $s[$i];
					}
				}
    			
    		}
    	}
    	$this->content = $q;
    }
    /**
     * @description Вернуть код счетчика liveInternet
     * @return string
    */
    protected function _getLiveinternetCounterHtml()
    {
		return '';
	}
	
}
