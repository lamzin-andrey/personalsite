<?php

class MarkdownCode
{
    const LANG_MAP = [
        'python' => 'perl',
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
                    $result[] = "<pre><code class=\"language-{$currentLang}\">";
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
                    
                    $result[] = $blockContent;
                    $result[] = "</code></pre>";
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
