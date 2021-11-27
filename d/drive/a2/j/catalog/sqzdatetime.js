function SqzDatetime() {}
SqzDatetime.SQZ_NUMS_ALPHABET = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
/**
 * @param TODO PDateTime $dateTime
**/
SqzDatetime.sqzDatetime = function($dateTime) {
    var $sDt, $aDt, $year, $month, $day, $hour, $min, $sec;
    
    $dateTime = __php2js_clone_argument__($dateTime);
    
    if (!$dateTime) {
        return '2021-11-23 00:00:00';
    }
    $sDt = $dateTime.format('Y,m,d,H,i,s');
    $aDt = explode(',', $sDt);
    $year = SqzDatetime.sqz($aDt[0]);
    $month = SqzDatetime.sqz($aDt[1]);
    $day = SqzDatetime.sqz($aDt[2]);
    $hour = SqzDatetime.sqz($aDt[3]);
    $min = SqzDatetime.sqz($aDt[4]);
    $sec = SqzDatetime.sqz($aDt[5]);
    
    return $day + $month + $year + $hour + $min + $sec;
    
}
// TODO normalize
SqzDatetime.sqz = function($s) {
    
    var $n, $symbols, $limit, $left, $sL, $right;
    
    $s = __php2js_clone_argument__($s);
    
    $n = intval($s);
    $s = strval($n);
    $symbols = SqzDatetime.SQZ_NUMS_ALPHABET;
    
    $limit = 59;
    if ($n < $limit) {
        return $symbols[$n];
    }
    
    // If number >= 59:

    if ($n > 1899 && $n < 3000) {
        $left = 1900;
        $sL = 'X';
        if ($n > 2000) {
            $left = 2000;
            $sL = 'Y';
        }
        $right = $n - $left;
        if ($right < $limit) {
            return $sL + $symbols[$right];
        }
        return 'Z' + $s;
    }
    
    return 'Z' + $s;
    
}
/**
 * @param {String} $sqzDt
 * @param {Boolean} $engFormat = true
 * @return String
*/
SqzDatetime.desqzDatetime = function($sqzDt, $engFormat) {
    var $sz, $day, $month, $hour, $min, $sec, $yearType, $length, $year;
    $sqzDt = __php2js_clone_argument__($sqzDt);
    $engFormat = String($engFormat) == 'undefined' ? true : $engFormat;
    $engFormat = __php2js_clone_argument__($engFormat);
    if (!$sqzDt) {
		if ($engFormat) {
			return 'Unknown';
		}
		return l('Unknown');
    }
    
    
    $sz = strlen($sqzDt);
    $day = SqzDatetime.desqz($sqzDt.charAt(0));
    $month = SqzDatetime.desqz($sqzDt.charAt(1));
    $hour = SqzDatetime.desqz($sqzDt.charAt($sz - 3));
    $min = SqzDatetime.desqz($sqzDt.charAt($sz - 2));
    $sec = SqzDatetime.desqz($sqzDt.charAt($sz - 1));
    
    $yearType = $sqzDt.charAt(2);
    if ('Z' == $yearType) {
        $length = $sz - 6;
        $year = substr($sqzDt, 3, $length);
    } else if ('X' == $yearType) {
        $year = '19' + SqzDatetime.desqz($sqzDt.charAt(3));
    } else if ('Y' == $yearType) {
        $year = '20' + SqzDatetime.desqz($sqzDt.charAt(3));
    }
    
    if ($engFormat) {
		return $year + '-' + $month + '-' + $day + ' ' + $hour + ':' + $min + ':' + $sec;
	}
	$year = substr($year, 2, 2);
	return $day  + '.' + $month + '.' + $year + ' '  + $hour + ':' + $min + ':' + $sec;
}
/**
 * @param {String} $sqzDt
 * @return String
*/
SqzDatetime.desqz = function($s) {
    
    var $n;
    
    $s = __php2js_clone_argument__($s);
    
    $n = strpos(SqzDatetime.SQZ_NUMS_ALPHABET, $s);
    if (false === $n) {
        return '-1';
    }
    
    $s = strval($n);
    
    if ($n < 10) {
        $s = '0' + $s;
    }
    
    return $s;
}

