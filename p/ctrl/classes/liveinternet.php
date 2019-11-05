<?php
class Liveinternet {
	/**
	 * @description Set Logotype
	 * @return string
	*/
	static public function getHtml()
	{
		$sFile = DOC_ROOT . '/p/view/site/masters/counters/liveinternet.html';
		if (!file_exists($sFile)) {
			return '';
		}
		return file_get_contents($sFile);
	}
}
