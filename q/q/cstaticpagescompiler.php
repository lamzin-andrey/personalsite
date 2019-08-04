<?php
class CStaticPagesCompiler {
	
	public $DEST_DOC_ROOT =   "";
	
	public $emsg = "";
	
    public function __construct(int $masterTemplateId, string $destFilePath, string $title, string $heading, string $content, 
									string $description, string $keywords, string $ogTitle, 
									string $ogDescription, string $ogImage, string $dateCreate)
    {
    	$this->DEST_DOC_ROOT = DOC_ROOT;
    	$rows = query("SELECT * FROM master_templates WHERE id = {$masterTemplateId}");
    	$masterFile = ($rows[0]['html_file_path']);
    	if ( !file_exists($this->DEST_DOC_ROOT . $masterFile) ) {
			$this->emsg = "Template '{$this->DEST_DOC_ROOT}{$masterFile}' not found";
			return;
    	}
    	//$destRoot = preg_replace("#(.*)/[\-\w\d\*]*\.html?$#", "$1", $destFilePath);
    	
    	$destFolder = $this->DEST_DOC_ROOT .  $destFilePath;
    	@mkdir($destFolder, 0777);
    	
        if ( !file_exists($destFolder) ) {
			$this->emsg = "Directory '{$destFolder}' not found";
			return;
    	}
        if ( !is_dir($destFolder) ) {
			$this->emsg = "'{$destFolder}' is not directory ";
			return;
    	}
    	$this->stripHtmlCode($content);
    	//$content = strip_tags($content, "<b><img><p><ul><li><a><br>");Это потом, для НЕадминов
    	$content = str_replace("\r", '', $content);
    	//$content = preg_replace('#onerror|onload\s?=\s?"[^"]*"#', "", $content);
    	//$content = preg_replace("#onerror|onload\s?=\s?'[^']*'#", "", $content);
    	$content = str_replace("\n", "</p><p>", $content);
    	$content = '<p>' . $content . '</p>';
    	//$content = str_replace("  ", "<br/>", $content);
    	$content = str_replace(array(md5('newline'), '[html]', '[/html]', md5('monosp'), md5("\t"), '[code]', '[/code]'), array("\n", '', '', ' ', "\t", '', ''), $content);
    	
    	$this->cyr($title);
    	$this->cyr($heading);
    	$this->cyr($heading2);
    	$this->cyr($content);
    	$this->cyr($description);
    	$this->cyr($keywords);
    	$this->cyr($ogTitle);
    	$this->cyr($ogDescription);
    	
    	$s = file_get_contents($this->DEST_DOC_ROOT . $masterFile);
        $s = str_replace('{TITLE}', $title, $s);
        $s = str_replace('{HEADING}', $heading, $s);
        
        if ($description) {
			$s = str_replace('<!--DESCRIPTION -->', '<meta name="description" content="' . $description . '">', $s);
		} else {
			$s = str_replace('<!--DESCRIPTION -->', '', $s);
		}
        if ($keywords) {
			$s = str_replace('<!--KEYWORDS -->', '<meta name="keywords" content="' . $keywords . '">', $s);
		} else {
			$s = str_replace('<!--KEYWORDS -->', '', $s);
		}
        
        if ($ogTitle) {
			$s = str_replace('<!--OGTITLE -->', '<meta property="og:title" content="' . $ogTitle . '">', $s);
		} else {
			$s = str_replace('<!--OGTITLE -->', '', $s);
		}
        if ($ogDescription) {
			$s = str_replace('<!--OGDESCRIPTION -->', '<meta property="og:description" content="' . $ogDescription . '">', $s);
		} else {
			$s = str_replace('<!--OGDESCRIPTION -->', '', $s);
		}
        if ($ogImage) {
			$s = str_replace('<!--OGIMAGE -->', '<meta property="og:image" content="' . $ogImage . '">', $s);
		} else {
			$s = str_replace('<!--OGIMAGE -->', '', $s);
		}
        
        
        //$s = str_replace('{HEADING2}', $heading2, $s);
        $s = str_replace('{CONTENT}', $content, $s);
        //$s = str_replace('{TSTAMP}', dechex(time()), $s);
        $ts = strtotime($dateCreate);
        $s = str_replace('{DATEENG}', date('Y-m-d', $ts), $s);
        $s = str_replace('{DATERUS}', date('d.m.Y', $ts), $s);
        
        file_put_contents($destFolder . '/index.html', $s);
    }
    
    private function stripHtmlCode(string &$s)
    {
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
    	$s = $q;
    }
    
    private function cyr(&$s)
    {
    	//$s = utils_cp1251($s);
    }
}
?>
