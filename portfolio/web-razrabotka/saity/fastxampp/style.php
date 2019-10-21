<?php

function dbfr_getbv($s, $name) {
	$st = strpos($s, $name);
    $s = substr($s, $st + strlen("$name/"));
    return intval($s);
}


function dbfr_defineBrowser() { 
	$old_version = false;
	$s = strtolower( $_SERVER["HTTP_USER_AGENT"] );//navigator.userAgent.toLowerCase();
	//die($s);
	$ie = 0; $firefox = 0; $opera = 0; $chrome = 0; $ie7 = 0; $ie8 = 0; $ie9 = 0; $ie10 = 0; $safari = 0;
	if ( strpos($s, "msie") !== false) {
	    $ie = 1;
	    if (strpos($s, "10.") !== false) {
	        $ie10 = 1;
	    }
	    if (strpos($s, "9.") !== false) {
	        $ie9 = 1;
	        $old_version = true;
	    }
	    if (strpos($s, "8.") !== false) {
	        $ie8 = 1;
	        $old_version = true;
	    }
	    if (strpos($s, "7.") !== false) {
	       $ie7 = 1;
	       $old_version = true;
	    }
	}
	if (strpos($s, "firefox") !== false) {
		$firefox = 1;
		$v = dbfr_getbv($s, "firefox");
		$old_version = ($v != 0 && $v < 10);
	}
	if (strpos($s, "opera") !== false) {
		$opera = 1;
		$v = dbfr_getbv($s, "version");
        $old_version = ($v != 0 && $v < 11);
	}
	if (strpos($s, "safari") !== false && strpos($s, "chrom") === false) {
	    $safari = 1;
	    $v = dbfr_getbv($s, "version");
        $old_version = ($v != 0 && $v < 4);
	}
	if (strpos($s, "chrom") !== false) {
	    $chrome = 1;
	    $v = dbfr_getbv($s, "chrome");
        $old_version = ($v != 0 && $v < 4);
	}
	define("IE",   $ie);
	define("IE7",  $ie7);
	define("IE8",  $ie8);
	define("IE9",  $ie9);
	define("IE10", $ie10);
	define("FIREFOX", $firefox);
	define("OPERA",   $opera);
	define("CHROME",  $chrome);
	define("SAFARI",  $safari);
}
	
	dbfr_defineBrowser();
	$bst = "ch_style";
	if (CHROME == 1) {
		$bst="ch_style";
}

