<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/q/q/utils.php';
define('CP1251', 'Windows-1251');
define('UTF8', 'UTF-8');

// TODO write in list!
class BookUpload {
	
	private $folderName = "";
	private $displayName = "";
	private $content = "";
	private $manifest = "";
	private $iconExt = "png";
	private $mime = "image/png";
	private $contentLength = 0;
	
	public function __construct()
	{
		$this->prepareContent();
		$this->writeContent();
		$this->prepareIcon();
		$this->prepareManifest();
		$this->writeManifest();
		$link = '/a/reader18/books/' . $this->folderName . '/';
		$this->addLink($link);
		utils_302($link);
	}
	
	private function addLink(string $link)
	{
		$displayName = mb_convert_encoding($this->displayName, CP1251, UTF8);
		$meas = ' Кб';
		$sz = round($this->contentLength / 1024, 2);
		$ctrl = round($this->contentLength / 1024, 0);
		if (strlen(strval($ctrl)) > 3) {
			$meas = ' Мб';
			$sz = round($sz / 1024, 2);
		}
		$size = $sz . $meas;
		$li = '<li>
			<div>
				<a href="/a/reader18/books/' . $this->folderName . '/" target="_blank">' . $displayName . '</a>
			</div>
			<div class="fl">
				Размер ' . $size . '
			</div>
			<div class="fr">
				Загружен ' . date('d.m.Y') . '
			</div>
			<div class="cl"></div>
		</li>';
		
		$targetFile = __DIR__ . '/../list/index.html';
		file_put_contents($targetFile, $li, FILE_APPEND);
	}
	
	private function writeManifest()
	{
		$targetFolderPath = __DIR__ . '/../books/' . $this->folderName;
		
		if (!file_exists($targetFolderPath)) {
			die("Unable find catalog `{$targetFolderPath}`<br>\n");
		}
		$s = $this->manifest;
		$targetFile = $targetFolderPath . '/manifest.json';
		file_put_contents($targetFile, $s);
		$this->manifest = '';
		if (!file_exists($targetFile)) {
			die("Unable write `{$targetFile}`<br>\n");
		}
		/*$success = chmod($targetFile, 0755);
		if (!file_exists($success)) {
			die("Unable set permissions 755 for  `{$targetFile}`<br>\n");
		}/**/
	}
	
	private function prepareManifest()
	{
		$tpl = file_get_contents(__DIR__ . '/../manifest_tpl.json');
		$s = str_replace('{Display_name}', $this->displayName, $tpl);
		$s = str_replace('{uniq}', $this->folderName, $s);
		$s = str_replace('{ext}', $this->iconExt, $s);
		$s = str_replace('{mime}', $this->mime, $s);
		$s = str_replace('{folder_name}', $this->folderName, $s);
		
		$this->manifest = $s;
	}
	
	private function prepareIcon()
	{
		$targetFolderPath = __DIR__ . '/../books/' . $this->folderName;
		$targetImagePath = $targetFolderPath . '/i';
		
		
		$file = $_FILES['icon'] ?? [];
		if (array_key_exists('tmp_name', $file)) {
			$path = $file['tmp_name'];
			if (file_exists($path)) {
				$ext = $this->getExt($path);
				if (!$ext) {
					die("Unable detext extension by image mime type<br>\n");
				}
				$destFile = $targetImagePath . '/128.' . $ext;
				$targetFile = $targetImagePath . '/src.' . $ext;
				$this->iconExt = $ext;
				move_uploaded_file($path, $targetFile);
				utils_resizeAndAddBg($targetFile, $destFile, 128, 128, [0, 0, 0], false);
				unlink($targetFile);
				if (!file_exists($destFile)) {
					$this->copyDefaultImage();
				}
			} else {
				$destFile = $targetImagePath . '/128.png';
				$this->copyDefaultImage();
			}
			
		} else {
			$destFile = $targetImagePath . '/128.png';
			$this->copyDefaultImage();
		}
	}
	
	private function copyDefaultImage() : void
	{
		$src = __DIR__ . '/../i/128.png';
		$dest = __DIR__ . '/../books/' . $this->folderName . '/i/128.png';
		copy($src, $dest);
		
		if (!file_exists($dest)) {
			die("Unable copy to `{$dest}`<br>\n");
		}
		
		$success = chmod($dest, 0755);
		if (!$success) {
			die("Unable change permissions to 0755 for  `{$dest}`<br>\n");
		}
		$this->iconExt = 'png';
		$this->mime = 'image/png';
	}
	
	private function getExt(string $path) : string
	{
		$this->mime = $mime = utils_getImageMime($path);
		
		if ('image/jpeg' == $mime) {
			return 'jpg';
		}
		if ('image/png' == $mime) {
			return 'png';
		}
		if ('image/gif' == $mime) {
			return 'gif';
		}
		
		return '';
	}
	
	private function writeContent()
	{
		$targetFolderPath = __DIR__ . '/../books/' . $this->folderName;
		if (!file_exists($targetFolderPath)) {
			mkdir($targetFolderPath);
		}
		
		if (!file_exists($targetFolderPath)) {
			die("Unable create catalog `{$targetFolderPath}`<br>\n");
		}
		
		$targetImagePath = $targetFolderPath . '/i';
		if (!file_exists($targetImagePath)) {
			mkdir($targetImagePath);
		}
		if (!file_exists($targetImagePath)) {
			die("Unable create catalog `{$targetImagePath}`<br>\n");
		}
		
		
		$tplPath = __DIR__ . '/../template.html';
		$tpl = file_get_contents($tplPath);
		$s = str_replace('{Title}', mb_convert_encoding($this->displayName, CP1251, UTF8), $tpl);
		$s = str_replace('{manifest.json}', $this->folderName . '/manifest.json', $s);
		$s = str_replace('{content}', $this->content, $s);
		$targetFile = $targetFolderPath . '/index.html';
		file_put_contents($targetFile, $s);
		$this->content = '';
		if (!file_exists($targetFile)) {
			die("Unable write `{$targetFile}`<br>\n");
		}
		/*$success = chmod($targetFile, '0644');
		if (!file_exists($success)) {
			die("Unable set permissions 644 for  `{$targetFile}`<br>\n");
		}*/
	}
	
	private function prepareContent()
	{
		$this->readText();
		$this->convertEncoding();
		$this->replaceBr();
	}
	
	private function replaceBr()
	{
		$this->content = '<p>' . str_replace("\n", "</p>\n<p>", $this->content) . '</p>';
	}
	
	private function convertEncoding()
	{
		$a = $this->content;
		$sz = count($a);
		$n = 0;
		// Анализируем первые 10 непустых строк
		$isCp1251 = false;
		$isUtf8 = false;
		$encs = [CP1251, UTF8];
		$title = '';
		$subtitle = '';
		for ($i = 0; $i < $sz; $i++) {
			$s = trim(strip_tags($a[$i]));
			if ($s) {
				if ($title == '' && $this->isShortLine($s)) { // TODO
					$title = $s;
				} elseif ($title != '' && $subtitle == '' && $this->isShortLine($s)) {
					$subtitle = $s;
				}
				
				$enc = mb_detect_encoding($s, $encs);
				
				if ($enc == CP1251) {
					$isCp1251 = true;
				}elseif($enc == UTF8) {
					$isUtf8 = true;
				}
				
				$n++;
			}
			if ($n > 10) {
				break;
			}
		}
		
		if (!$isCp1251 && !$isUtf8) {
			die("Unable detect encoding of the textfile\n<br>");
		}
		
		$this->content = $s = implode("\n", $this->content);
		if (!$isCp1251 && $isUtf8) {
			$this->content = mb_convert_encoding($s, CP1251, UTF8);
			$title = mb_convert_encoding($title, CP1251, UTF8);
			$subtitle = mb_convert_encoding($subtitle, CP1251, UTF8);
		}
		
		if (trim($subtitle)) {
			$title .= ' ' . trim($subtitle);
			$this->displayName = mb_convert_encoding($subtitle, UTF8, CP1251);
			$this->folderName = utils_translite_url($this->displayName);
		}
		
		if (!trim($this->folderName)) {
			die("Unable detect subcatalog name\n<br>");
		}
		$this->contentLength = strlen($this->content);
	}
	
	private function readText()
	{
		$file = $_FILES['text'] ?? [];
		if (array_key_exists('tmp_name', $file)) {
			$path = $file['tmp_name'];
			if (file_exists($path)) {
				$this->content = explode("\n", file_get_contents($path));
			} else {
				die("Unable save tmp file");
			}
			
		} else {
			die("Not found text file\n<br>");
		}
	}
	
	/**
	 * @return bool true if words in line less when 7
	 * */
	private function isShortLine(string $s) : bool
	{
		$a = preg_split("#[\s,.!]#", $s);
		$n = 0;
		foreach ($a as $w) {
			if (trim($w)) {
				$n++;
			}
		}
		
		return ($n < 7);
	}
}

new BookUpload();
