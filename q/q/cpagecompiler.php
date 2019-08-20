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
	/** @property string canonicalUrl  <link rel="canonical" href="scheme://host/$this->canonicalUrl"/>   */
	public $canonicalUrl = '';//CANONICAL_URL
	
	public function __construct() {
		$this->displayDate = date('d.m.Y');
	}
	/**
	 * @description
	 * @return string
	*/

	public function compile()
	{
		$s = file_get_contents($this->tpl);
		$s = str_replace('{TITLE}', $this->title, $s);
		$s = str_replace('{BC}', $this->bc, $s);
		$s = str_replace('{HEADING}', $this->heading, $s);
		
		$s = str_replace('{CONTENT}', $this->content, $s);
		$s = str_replace('{DATEENG}', date('Y-m-d H:i"s'), $s);
		$s = str_replace('{DATERUS}', $this->displayDate, $s);
		$s = str_replace('{CANONICAL_URL}', $this->canonicalUrl, $s);
		
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
		return $s;
	}
}
