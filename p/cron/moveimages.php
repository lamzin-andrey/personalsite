<?php

require_once __DIR__ . '/../../q/q/laws.php';

class MoveImages extends LandAws {
	
	
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function moveImages():void
	{
		$root = __DIR__ . '/../../i';
		
		$this->_moveImages($root);
	}
	
	private function _moveImages(string $rt):void
	{
		$ls = scandir($rt);
		foreach ($ls as $f) {
			if ($f == '.' || $f == '..') {
				continue;
			}
			$f = $rt . '/' . $f;
			if (is_dir($f)) {
				echo "Process {$f}\n";
				$this->_moveImages($f);
			} else {
				$a = explode('/../i/', $f);
				$rf = 'i/' . $a[1];
				echo "Will cp to {$rf}\n";
				//$this->send($f, $rf);
			}
		}
	}
}


$mv = new MoveImages();
$mv->moveImages();
