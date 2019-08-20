<?php
/**
 * Боремся с матершиной и абсцентной лексикой
*/
class SwearSword {
	
	/**
	 * @description Заменяет матерные слова на *** в фразе $s
	 * @param string $s предпочтительно в utf-8
	*/
	public function clearstring($s)
	{
		return $this->sanitize($s);
	}
	
	public function sanitize($s)
	{
		$this->amt($s);
		return $s;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	private function amt(&$s)
	{
		$words = [
		'хуе',
		'хуё',
		'хуи',
		'хуй',
		'хули',
		'хую',
		'хуя',
		'xyu',

		'пиз',
		'писец',
		'пипец',
		'ппц',
		'песдец',
		'песдес',

		'бля',

		'еб', // !!
		'ёб', // !!
		'ъеб',
		'ьеб',
		'ъёб',
		'ьеб',
		'ибу',

		'сука',
		'говно',
		'гавно',
		'гавен',
		'говен',
		'гавён',
		'говён',
		'быдло',
		'вафле',
		'вафлё',

		'пидар',
		'пидор',
		'педар',
		'педор',
		'педр',
		'пидр',
		'педик',
		'пелотка',
		'лесб',
		'гомося',

		'дроч',
		'падла',
		'падло',
		'жоп',
		'писюн',
		'срак',
		'сра',
		'сcа',
		'govnosait',
		'govnosite'/*,
		''
		/**/
		];
		$pre = [
		'а',
		'в',
		'вы',
		'въ',
		'до',
		'за',
		'на',
		'не',
		'ни',
		'об',
		'от',
		'о',
		'под',
		'по',
		'подъ',
		'раз',
		'рас',
		'с',
		'сь',
		'съ',
		'зло',
		'у'
		];
		$toUtf = 0;
		
		$bIsUtf8 = false;
		if (mb_detect_encoding($s, ['UTF-8', 'CP-1251', 'Windows-1251'] ) != 'Windows-1251') {
			$bIsUtf8 = false;//TODO что-то тут не то
		}
			
		if ($bIsUtf8) {
			$toUtf = 1;
			$s = utils_cp1251($s);
		}
		$w = '';
		$alphabet = 'абвгдеёжзийклмнопрстуфхцчшщъыьэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯabcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$sl = strtolower($s);
		for ($i = 0; $i < strlen($sl); $i++) {
			if ( strpos($alphabet, $sl[$i]) === false ) {
				$this->checkAmt($s, $i, $w, $words, $pre, $alphabet);
				$w = '';
				continue;
			}
			$w .= $s[$i];
		}
		$this->checkAmt($s, strlen($s) - 1, $w, $words, $pre, $alphabet);
		if ($toUtf) {
			$s = utils_utf8($s);
		}
	}
  
	private function checkAmt(&$s, $i, $w, $words, $pre, $alphabet) {
		if ( trim($w) == '') return;
		if ($w == 'себя') return;
		if ($w == 'себе') return;
		if ($w == 'отсебятина') return;
		if ($w == 'отсебятину') return;
		if ($w == 'Педро') return;
		if ($w == 'веб') return;
		if ($w == 'вебе') return;
		if ($w == 'вебом') return;
		if ($w == 'вебмани') return;
		if ($w == 'вебманями') return;
		if ($w == 'веб-мани') return;
		if ($w == 'веб-манями') return;
		if ($w == 'вебманей') return;
		if ($w == 'веб-маней') return;
		if ($w == 'сравнению') return;
		if ($w == 'сразил') return;
		if ($w == 'сразу') return;

		$d = 0;
		//if ( $w ==  'заебало' ) $d = 1;

		$ws = mb_strtolower($w, 'Windows-1251');

		$found = 0;
		$badword = '';
		foreach ($words as $word) {
			if ( strpos($ws, $word) === 0) {
				$found = 1;
				$badword = $ws;
				break;
			}
			$nn = 0;
			foreach ($pre as $p) {
				$cs = $p . $word;
				if ( strpos($ws, $cs) === 0) {
					$found = 1;
					$badword = $ws;
					break;
				}
				$nn++;
			}
			if ($found) break;
		}
		if ($found) {
			$j = $i;
			for ($j = $i; $j < strlen($s); $j++) {
				if ( strpos($alphabet, $s[$j]) === false) break;// !! j--
			}
			$start = $j - strlen($badword);
			$pref = substr($s, 0, $start);
			$post = substr($s, $j);
			$q = '';
			for ($k = 0; $k < strlen($badword); $k++) $q .= '*';
			$s = $pref . $q . $post;
		}
	}
} 
