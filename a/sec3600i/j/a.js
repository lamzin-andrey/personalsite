var d  =  document,
w  =  window,
nav = navigator,
nMs = 0,
nS =  0,
nM = 0,
nH = 0,
intMs,
intSec,
nFirstPoint = 1,
nCurrentPoint = 1,
nCurrentPointReal = 0,
menuIsVisible = 0,
isStop = 0,
p = ':',
points = {},
touchEvents = [],
funcs = [];
function strt(){
  if (nav.userAgent.toLowerCase().indexOf('android 2.3.6') != -1) {
	w.isA2 = 1;
    astl('2.css');
    funcs.push(onLoadA236);
  } else {
    astl('s.css');
  }
  buildPoints();
  fixScr();
  setListeners();
}
function  setListeners() {
  onClickStart();
  e( 'reset').addEventListener( 'click', onClickStart, true);
  e('stop').addEventListener( 'click', onClickStop, true);
  e('menu').addEventListener( 'click', onClickPoint, true );
  e('hPoints').addEventListener('touchmove', onTouchPoints, true );
  e('hPoints').addEventListener('touchstart', onTouchStartPoints, true );
  //document.addEventListener('touchend', onTouchEndPoints, true );
}

function onTouchStartPoints(e) {
	if (e.changedTouches.length > 0) {
		var y = e.changedTouches[0].clientY;
		touchEvents.push( {y:y, t:new Date().getTime()} );
	}
}

function onTouchPoints(e){
	e.preventDefault();
	if (e.changedTouches.length > 0) {
		var t = new Date().getTime(),
			y = e.changedTouches[0].clientY;
		var prev = touchEvents[touchEvents.length - 1];
		if (prev && prev.y && prev.t) {
			if (y > prev.y) {
				nCurrentPoint--;
				if (nCurrentPoint <= nFirstPoint) {
					nFirstPoint--;
				}
				if (nCurrentPoint < 1 || nFirstPoint < 1) {
					nCurrentPoint = nFirstPoint = 1;
					buildPoints();
					beepClick();
					return;
				}
			} else if (y < prev.y) {
				nCurrentPoint++;
				if (nCurrentPoint > 3) {
					nFirstPoint++;
				}
				beepClick();
			}
			buildPoints();
		}
	}
}

function onClickStart(){
  beepClick();
   if (intMs || isStop){
     clearInterval( intMs );
     clearInterval( intSec );
     intMs = intSec = null;
     nMs = nM = nH = nS = 0;
     hMs.innerHTML  = hS.innerHTML  = hM.innerHTML  = h.innerHTML  = '00';
     var sz, i, ls;
     nCurrentPointReal = nCurrentPoint = nFirstPoint = 1;
     isStop = 0;
     points = {};
     buildPoints();
     reset.innerHTML  = 'Старт';
     e('stop').innerHTML  = 'Назад';
     setMenuButton(0);
     return ;
   }
  setMenuButton(1);
  e ( 'reset' ).innerHTML  = 'Сброс';
  e('stop').innerHTML  = 'Стоп';
  nFirstPoint = nCurrentPoint = 1;
  buildPoints();
  intMs  = setInterval( onMsTime, 10 );
  intSec = setInterval( onSecTime, 1000 )
} 

function onClickStop(){
  beepClick();
  if (intMs){
    clearInterval( intMs );
    clearInterval( intSec );
    intMs = intSec = null;
    e( 'stop' ).innerHTML  = 'Выход';
    points[nCurrentPoint] = z(nH) + p + z(nM) + p + z(nS) + p + z(nMs);
    nCurrentPoint++;
    if (nCurrentPoint > 4) {
      nFirstPoint++;
    }
    buildPoints();
    isStop = 1;
    setMenuButton(0);
    return;
  }
  if (!w.isA2) {
	  try {
		  window.close();
	  } catch(e) {
		  alert('Не удалось закрыть окно, ' + e);
		  location.href = '/a';
	  }
  } else {
	  location.href = '/a';
  }
}
function onClickPoint(){
  setTimeout(function(){e('menu').blur();e('reset').focus();}, 500);
  if (!menuIsVisible) {
	  return;
  }
  beepClick();
  nCurrentPointReal = nCurrentPointReal ? nCurrentPointReal : 1;
  nCurrentPoint =  nCurrentPointReal;
  nFirstPoint = nCurrentPoint - 3;
  if (nFirstPoint < 1) {
	  nFirstPoint = 1;
  }
  
  points[nCurrentPoint] = z(nH) + p + z(nM) + p + z(nS) + p + z(nMs);
  nCurrentPoint++;
  if (nCurrentPoint > 4) {
	  nFirstPoint++;
  }
  nCurrentPointReal = nCurrentPoint;
  buildPoints();
}
function setMenuButton(v) {
	menuIsVisible = v;
	if (!v) {
		addClass('bq', 'bb');
	} else {
		removeClass('bq', 'bb');
	}
}
function astl(st) {
  var h  = d.getElementsByTagName('head')[0],
  r  = './s/',
  s  = d.createElement ('link');
  s.type  = 'text/css';
  s.rel = 'stylesheet';
  s.href = r + st;// + '?' + Math.random();
  h.appendChild (s);
}
function onSecTime(  ){
  nMs =  0;
  nS++;
  var add = 0;
  add = nS > 59 ? 1 : 0;
  nS = nS > 59 ? 0 : nS;
  nM += add;
  add = nM > 59 ? 1 : 0;
  nM = nM  > 59 ? 0 : nM;
  nH += add;
  h.innerHTML  = z(nH);
  hM.innerHTML  = z(nM);
  hS.innerHTML  =  z(nS);
  
}
function onMsTime(  ){
  var k = Math.random(  ) > 0.5 ? 1 : 2;
  nMs += k;
  nMs = nMs > 99 ? 0 : nMs;
  nMs = z( nMs );
  hMs.innerHTML = nMs;
}
function z( n ){
  n = Number( n );
  n = n < 10 ? ( '0' + n ) : n;
  return n;
}
function fixScr() {
	var imgs = document.getElementsByTagName('img'), k, im, tr, str;
	for (k = 0; k < imgs.length; k++) {
		str = imgs[k].getAttribute('alt');
		if (str == '000webhost logo' || str == 'www.000webhost.com') {
			im = imgs[k];
			while(im.tagName != 'DIV') {
				im = im.parentNode;
				tr++;
				if (tr > 10) {
					break;
				}
			}
			if(im.tagName == 'DIV') {
				im.parentNode.removeChild(im);
				window.rwl = 1;
			}
		}
	}
	for (k = 0; k < funcs.length; k++) {
		if (funcs[k] instanceof Function) {
			funcs[k]();
		}
	}
}
function onLoadA236() {
	d.body.style['min-height'] = '400px';
	d.body.style['border'] = 'black 1px solid';
	var y = 90;
	w.scrollTo(0, y);
	setTimeout(function(){
		w.scrollTo(0, y);
	}, 1000);
}
function buildPoints() {
	var tpl = '<b class="sti [a]">\
			<i  class="inum">[n]</i><i class="rsl">[s]</i>\
		  </b>', i, s, j, v, q = '';
	e('hPoints').innerHTML = '';
	for (i = 0, j = nFirstPoint; i < 4; i++, j++) {
		s = tpl.replace('[n]', j);
		v = points[j] ? points[j] : '00:00:00:00';
		s = s.replace('[s]', v);
		v = j == nCurrentPoint ? 'a' : '';
		s = s.replace('[a]', v);
		q += s;
	}
	e('hPoints').innerHTML = q;
}
function beepClick() {
	/*e('audioplacer').innerHTML = '';
	e('audioplacer').innerHTML = '<audio id="Aclick">\
		<source src="m/0.mp3" type="audio/mp3">\
	</audio>';*/
	e('Aclick').play();
	//setTimeout(function(){e('Aclick').pause();}, 1000);
	
}
function e(i) {
	if (i && i.tagName || d == i) return i;
	return d.getElementById(i);
}
function removeClass(obj, css) {
	obj = e(obj);
	var c = obj.className, re = /[0-9a-zA-Z\-_]+/gm,
	arr = c.match(re),
	i, result = [];
	if (arr) for (i = 0; i < arr.length; i++) {
		if (arr[i] !== css) {
			result.push(arr[i]);
		}
	}
	obj.className = result.join(' ');
}
function addClass(obj, css) {
	obj = e(obj);
	removeClass(obj, css);
	obj.className += ' ' + css;
}
window.onload  = strt;
