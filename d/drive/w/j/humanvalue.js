function HumanValue() {
	
}
HumanValue.getHumanFilesize = function($n, $percision, $maxOrder, $pack) {
    $percision = String($percision) == 'undefined' ? 3 : $percision;
    $maxOrder = String($maxOrder) == 'undefined' ? 3 : $maxOrder;
    $pack = String($pack) == 'undefined' ? true : $pack;
    $percision = __php2js_clone_argument__($percision);
    $maxOrder = __php2js_clone_argument__($maxOrder);
    $pack = __php2js_clone_argument__($pack);
    $n = __php2js_clone_argument__($n);
    
    return HumanValue.getHumanValue($n,
		['b', 'Kb', 'Mb', 'Gb', 'Tb'],
		1024,
		$percision,
		$maxOrder,
		$pack
    );
}
HumanValue.getHumanValue = function($n, $units, $divider, $percision, $maxOrder, $pack) {
    var $unitIterator, $r, $a, $int, $add, $buf, $unitsSz, $s;
    $divider = String($divider) == 'undefined' ? 1000 : $divider;
    $percision = String($percision) == 'undefined' ? 3 : $percision;
    $maxOrder = String($maxOrder) == 'undefined' ? 3 : $maxOrder;
    $pack = String($pack) == 'undefined' ? true : $pack;
    $divider = __php2js_clone_argument__($divider);
    $percision = __php2js_clone_argument__($percision);
    $maxOrder = __php2js_clone_argument__($maxOrder);
    $pack = __php2js_clone_argument__($pack);
    $n = __php2js_clone_argument__($n);
    $units = __php2js_clone_argument__($units);
    
    $unitIterator = 0;
    $r = strval($n) + ' ' + $units[$unitIterator];
    if ($pack) {
        $a = explode('.', $r);
        $int = $a[0];
        $add = $a[1] ?? '';
        $buf = [];
        $buf.push(dechex($int));
        if ($add) {
            $buf.push(dechex($add));
        }
        $buf.push($units[$unitIterator]);
        $r = implode('g', $buf);
    }
    
    
    
    $unitsSz = count($units);
    do {
        $n = round($n, $percision);
        $s = strval($n);
        $a = explode('.', $s);
        $int = $a[0];
        $add = $a[1] ?? '';
        if (strlen($int) <= $maxOrder || $unitIterator > ($unitsSz - 1) ) {
            if ($pack) {
                $buf = [];
                $buf.push(dechex($int));
                if ($add) {
                    $buf.push(dechex($add));
                }
                $buf.push($units[$unitIterator]);
                return implode('g', $buf);
            }
            return $s + ' ' + $units[$unitIterator];
        }
        $n = $n / $divider;
        $unitIterator++;
        } while(true);
    
    return $r;
}

