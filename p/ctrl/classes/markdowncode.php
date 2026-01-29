<?php

class MarkdownCode
{
    const LANG_MAP = [
        'conf'    => 'ini',
        'sh'      => 'bash',
        'htm'     => 'html',
    ];

    public function processText(string $text): string
    {
        // Сначала обрабатываем inline-код, потом блоки, 
        // чтобы не трогать обрамляющие ` внутри блоков кода
        $text = $this->processInline($text);
        $text = $this->processBlock($text);
        return $text;
    }

    public function processBlock(string $text): string
    {
        $lines = explode("\n", $text);
        $result = [];
        $inCodeBlock = false;
        $currentLang = '';
        $currentBlock = [];
        
        foreach ($lines as $line) {
            if (!$inCodeBlock) {
                // Проверяем, начинается ли блок кода
                $langName = '';
                if ($this->detectBlockStart($line, $langName)) {
                    $inCodeBlock = true;
                    $currentLang = $langName;
                    // Применяем карту языков
                    $currentLang = self::LANG_MAP[$currentLang] ?? $currentLang;
                    // Добавляем открывающий тег HTML блока
                    $result[] = "[html]";
                    if (!$this->isSpecialPseudoLang($currentLang)) {
						$result[] = "<pre><code class=\"language-{$currentLang}\">";
					}
                } else {
                    $result[] = $line;
                }
            } else {
                // Мы внутри блока кода
                if (trim($line) === '```') {
                    // Конец блока кода
                    $inCodeBlock = false;
                    
                    // Обрабатываем и добавляем содержимое блока
                    $blockContent = implode("\n", $currentBlock);
                    
                    // Специальная обработка для HTML
                    if ($currentLang === 'html') {
                        $blockContent = htmlspecialchars($blockContent, ENT_QUOTES, 'UTF-8');
                    }
                    
                    if ($currentLang === 'info' || $currentLang === 'infohead') {
                        $blockContent = $this->createInfoBlock($blockContent, ($currentLang === 'infohead'));
                    }
                    if ($currentLang === 'warning' || $currentLang === 'warninghead') {
                        $blockContent = $this->createWarningBlock($blockContent, ($currentLang === 'warninghead'));
                    }
                    
                    $result[] = $blockContent;
                    if (!$this->isSpecialPseudoLang($currentLang)) {
						$result[] = "</code></pre>";
					}
                    $result[] = "[/html]";
                    
                    $currentBlock = [];
                    $currentLang = '';
                } else {
                    $currentBlock[] = $line;
                }
            }
        }
        
        // Если что-то осталось в блоке (незакрытый блок)
        if (!empty($currentBlock)) {
            $result = array_merge($result, $currentBlock);
        }
        
        return implode("\n", $result);
    }
    
    private function createInfoBlock(string $blockContent, bool $withHead = false): string
    {
		return $this->createPseudoLangBlock($blockContent, $withHead);
	}
	
	private function isSpecialPseudoLang(string $currentLang):bool
	{
		$list = [
			'info',
			'infohead',
			'warning',
			'warninghead',
		];
		return in_array($currentLang, $list);
	}
	
	private function getInfoTplWithHead(): string
	{
		return '<div role="alert" class="alert alert-info">
<div class=" border u-warning-icon-border rounded-circle p-1 d-inline-block float-left mr-2" style="min-width: 49px; padding-left: 15px !important; height: 53px !important; border-color: rgb(132, 132, 181) !important;">
<i class="fa fa-info" style="font-size: 2rem;"></i></div> 
<h4 class="alert-heading">{head}</h4>
{text}
<hr> <p class="mb-0">&nbsp;</p></div>';
	}
	
	private function getInfoTpl(): string
	{
		return '<div role="alert" class="alert alert-info">
<div class=" border u-warning-icon-border rounded-circle p-1 d-inline-block float-left mr-2" style="min-width: 49px; padding-left: 15px !important; height: 53px !important; border-color: rgb(132, 132, 181) !important;">
<i class="fa fa-info" style="font-size: 2rem;"></i></div> 
{text}
<hr> <p class="mb-0">&nbsp;</p></div>';
	}
	
	private function createWarningBlock(string $blockContent, bool $withHead = false): string
    {
		return $this->createPseudoLangBlock($blockContent, $withHead, 'getWarnTplWithHead', 'getWarnTpl');
	}
	
	private function getWarnTplWithHead(): string
	{
		return '<div class="alert alert-warning"><div class=" border u-warning-icon-border rounded-circle p-1 d-inline-block float-left mr-2"><i class="fa fa-radiation" style="font-size: 2rem;"></i></div>
<h4 class="alert-heading">{head}</h4>
{text}
</div>';
	}
	
	private function getWarnTpl(): string
	{
		return '<div class="alert alert-warning"><div class=" border u-warning-icon-border rounded-circle p-1 d-inline-block float-left mr-2"><i class="fa fa-radiation" style="font-size: 2rem;"></i></div>
{text}
</div>';
	}
	
	private function createPseudoLangBlock(string $blockContent, bool $withHead = false, string $withHeadTpl = 'getInfoTplWithHead', string $noHeadTpl = 'getInfoTpl'): string
    {
		$a = explode("\n", $blockContent);
		$head = '';
		if ($withHead) {
			$head = $a[0];
			unset($a[0]);
			$tpl = $this->$withHeadTpl();
		} else {
			$tpl = $this->$noHeadTpl();
		}
		$text = '<p>' . implode("</p>\n<p>", $a) . '</p>' . "\n";
		$s = str_replace('{text}', $text, $tpl);
		$s = str_replace('{head}', $head, $s);
		return $s;
	}

    private function detectBlockStart(string $line, string &$detectedLangName): bool
    {
        $trimmedLine = trim($line);
        
        // Проверяем, начинается ли строка с ```
        if (strpos($trimmedLine, '```') === 0) {
            // Получаем язык программирования
            $langPart = substr($trimmedLine, 3);
            $detectedLangName = trim($langPart);
            
            // Если нет указания языка, используем пустую строку
            if ($detectedLangName === '') {
                $detectedLangName = '';
            }
            
            return true;
        }
        
        return false;
    }
    
    public function processInline(string $text): string
    {
		$lines = explode("\n", $text);
		$inBlock = false;
		foreach ($lines as $i => $line) {
			$tline = trim($line);
			if (strpos($tline, '```') === 0) {
				$inBlock = !$inBlock;
				if (!$inBlock) {
					continue;
				}
			}
			if (!$inBlock) {
				$lines[$i] = $this->processInlineOneLine($line);
			}
		}
		
		return implode("\n", $lines);
	}

    public function processInlineOneLine(string $text): string
    {
        $result = '';
        $inInlineCode = false;
        $backtickCount = 0;
        
        for ($i = 0; $i < strlen($text); $i++) {
            $char = $text[$i];
            
            if ($char === '`') {
                $backtickCount++;
                
                // Проверяем, нужно ли начать или закончить inline-код
                if (!$inInlineCode) {
                    // Начинаем inline-код (четное количество кавычек)
                    $result .= '<code>';
                    $inInlineCode = true;
                } else {
                    // Заканчиваем inline-код (нечетное количество кавычек)
                    $result .= '</code>';
                    $inInlineCode = false;
                }
            } else {
                $result .= $char;
            }
        }
        
        // Если остались незакрытые теги
        if ($inInlineCode) {
            $result .= '</code>';
        }
        
        return $result;
    }
}
