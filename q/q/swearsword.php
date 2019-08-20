<?php
/**
 * ������� � ���������� � ���������� ��������
*/
class SwearSword {
	
	/**
	 * @description �������� �������� ����� �� *** � ����� $s
	 * @param string $s ��������������� � utf-8
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
		'���',
		'��',
		'���',
		'���',
		'����',
		'���',
		'���',
		'xyu',

		'���',
		'�����',
		'�����',
		'���',
		'������',
		'������',

		'���',

		'��', // !!
		'��', // !!
		'���',
		'���',
		'���',
		'���',
		'���',

		'����',
		'�����',
		'�����',
		'�����',
		'�����',
		'����',
		'����',
		'�����',
		'�����',
		'����',

		'�����',
		'�����',
		'�����',
		'�����',
		'����',
		'����',
		'�����',
		'�������',
		'����',
		'������',

		'����',
		'�����',
		'�����',
		'���',
		'�����',
		'����',
		'���',
		'�c�',
		'govnosait',
		'govnosite'/*,
		''
		/**/
		];
		$pre = [
		'�',
		'�',
		'��',
		'��',
		'��',
		'��',
		'��',
		'��',
		'��',
		'��',
		'��',
		'�',
		'���',
		'��',
		'����',
		'���',
		'���',
		'�',
		'��',
		'��',
		'���',
		'�'
		];
		$toUtf = 0;
		
		$bIsUtf8 = false;
		if (mb_detect_encoding($s, ['UTF-8', 'CP-1251', 'Windows-1251'] ) != 'Windows-1251') {
			$bIsUtf8 = false;//TODO ���-�� ��� �� ��
		}
			
		if ($bIsUtf8) {
			$toUtf = 1;
			$s = utils_cp1251($s);
		}
		$w = '';
		$alphabet = '�������������������������������������Ũ��������������������������abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
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
		if ($w == '����') return;
		if ($w == '����') return;
		if ($w == '����������') return;
		if ($w == '����������') return;
		if ($w == '�����') return;
		if ($w == '���') return;
		if ($w == '����') return;
		if ($w == '�����') return;
		if ($w == '�������') return;
		if ($w == '���������') return;
		if ($w == '���-����') return;
		if ($w == '���-������') return;
		if ($w == '��������') return;
		if ($w == '���-�����') return;
		if ($w == '���������') return;
		if ($w == '������') return;
		if ($w == '�����') return;

		$d = 0;
		//if ( $w ==  '�������' ) $d = 1;

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
