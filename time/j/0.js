W.addEventListener('load', onInit);

function onInit() {
	var rawTime = e('hTimeview').innerHTML,
		a = rawTime.split(' '),
		aDate = a[0].split('.'),
		aTime = a[1].split(':'),
		sDatetime = aDate[2] + '-' + aDate[1] + '-' + aDate[0] + ' ' + aTime[0] + ':' + aTime[1] + ':' + aTime[2];
	W.aTime = aTime;
	W.aDate = aDate;
	W.time = strtotime(sDatetime);
	
	setInterval(function() {
		onTick();
	}, 1000);
}

function onTick() {
	W.time++;
	var s = tpl().replace('{d}', z(date('d', W.time)));
	s = s.replace('{m}', z(date('m', W.time)));
	s = s.replace('{Y}', z(date('Y', W.time)));
	s = s.replace('{H}', z(date('H', W.time)));
	s = s.replace('{i}', z(date('i', W.time)));
	s = s.replace('{s}', z(date('s', W.time)));
	e('hTimeview').innerHTML = s;
}

function tpl() {
	return '<span>{d}</span>.<span>{m}</span>.<span>{Y}</span> <span>{H}</span>:<span>{i}</span>:<span>{s}</span>';
}

function z(n) {
	n = intval(n);
	if (n < 10) {
		return ('0' + n);
	}
	return strval(n);
}
