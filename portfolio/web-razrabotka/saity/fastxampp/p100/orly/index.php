<?php

require_once __DIR__ . '/classes/request/Request.php';

class OrlyProxyChecker {
	public function __construct()
	{
		
		$logHead = "==================" . date('Y-m-d H:i:s') . "==================\n";
		$logTail = "\n==================\n";
		$e = "\n";
		$logFile = __DIR__ . '/proxycheckurl.log';
		
		$a = @$_GET['f'];
		$aResults = [];
		//file_put_contents($logFile, print_r($aResults, true), FILE_APPEND);
		//file_put_contents($logFile, $logHead, FILE_APPEND);
		foreach ($a as $url) {
			$req = new Request();
			$req->setFollowLocation(false);
			$resp = $req->execute($url);
			//file_put_contents($logFile, print_r($resp, true), FILE_APPEND);
			$aResults[md5($url)] = $this->_checkStatus($resp->responseStatus, $resp->responseText);
		}
		//file_put_contents($logFile, print_r($aResults, true) . $logTail, FILE_APPEND);
		header('Content-Type: application/json');
		echo json_encode($aResults);
		exit;
	}
	/**
	 * 
	 * 
	 * */
	 
	 private function _checkStatus($status, $text) {
		 if ($status != 200) {
			 return $status;
		 }
		 return $this->_checkText1118($text);
	 }
	 
	 private function _checkText1118($text) {
		 $text = mb_strtolower($text, 'UTF-8');
		 $failMessage = 'Это объявление закрыто владельцем. Оно более не актуально.';
		 $aKwords = ['объявление', 'закрыто', 'не', 'актуал'];
		 if (strpos($text, $failMessage) !== false) {
			 return 302;
		 }
		 $n = 0;
		 foreach ($aKwords as $word) {
			 if (strpos($text, $word) !== false) {
				 $n++;
			 }
		 }
		 if ($n == 4) {
			 return 302;
		 }
		 return 200;
	 }
}
new OrlyProxyChecker();
