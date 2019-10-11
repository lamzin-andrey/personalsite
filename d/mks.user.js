// ==UserScript==
// @name        MoiKrugStat
// @namespace   https://moikrug.ru/*
// @include     https://moikrug.ru/*
// @version     1
// @grant       none
// @description Подсчитывает для каждой страницы сайта moikrug.ru количество вакансий требующих знания популярных фреймвёрков. Установите этот скрипт, пройдите на страницу вакансий и когда загрузка страницы будет завершена нажмите Ctrl+C. Вы увидете отчёт популярности фреймвёрков в проектах с вакансиями и среднюю предлагаемую зарплату. В случае, если зарпплата указана в формате "от... до...", учитывается значение "от.
// ==/UserScript==

function onLoad() {
  
  window.targets = {
    'Symfony':{count:0, pays:[]},
    'Yii':{count:0, pays:[]},
    'Vue.js':{count:0, pays:[]},
    'React.js':{count:0, pays:[]},
    'Laravel':{count:0, pays:[]},
    'Angular':{count:0, pays:[]},
    'Phalcon':{count:0, pays:[]},
    'Kohana':{count:0, pays:[]},
    'Codeigniter':{count:0, pays:[]},
    'MySQL':{count:0, pays:[]},
    'PostgreSQL': {count:0, pays:[]}
  };
  
  var body = document.getElementsByTagName('body')[0];
  body.addEventListener('keyup', onKeyUp, false);
  //alert('Aga');
}

function showStat() {
  var ls = document.getElementsByClassName('skill'), i, item, title, j, a, pay;
  for (i = 0; i < ls.length; i++) {
    item = ls[i];
    if (item.tagName == 'A') {
      title = item.innerHTML;
      for (j in window.targets) {
        if (~title.indexOf(j)) {
          pay = grabPay(item);
          window.targets[j].count++;
          window.targets[j].pays.push(pay);
        }
      }
    }
  }
  console.log( window.targets );
  
  //sort
  var aBuf = [], o;
  for (j in window.targets) {
    o = new Object();
    o.name = j;
    o.count  = window.targets[j].count;
    o.pay = avg(window.targets[j].pays);
    aBuf.push(o);
  }
  
  aBuf = aBuf.sort( (oI, oJ) => {
    if (oI.count < oJ.count) {
      return -1;
    }
    if (oI.count > oJ.count) {
      return 1;
    }
    if (oI.count == oJ.count) {
      return 0;
    }
    
  } );
  
  console.log(aBuf);
  
  //generate report
  
  var a = [];
  
  for (j = 0; j < aBuf.length; j++) {
    a.push(aBuf[j].name + ': ' + aBuf[j].count + ', ' + money(aBuf[j].pay) + ' р.');
  }
  alert(a.join('\n'));
}


function avg(aInt) {
  var i, price = 0;
  for (i = 0; i < aInt.length; i++) {
    price += parseInt(aInt[i]);
  }
  return parseInt(price / aInt.length);
}


function grabPay(el) {
  var raw = '0', allow = '0123456789', s = '', i, ch, a, j, from, to;
  try {
  	raw = el.parentNode.parentNode.getElementsByClassName('salary')[0].innerText.toLowerCase();
  } catch(e) {
    console.log(e);
  }
  
  if (~raw.indexOf('до')) {
    a = raw.split('до');
    from = extractNumsFromString(a[0]);
    to = extractNumsFromString(a[1]);
    
    if (!isNaN(from)) {
      raw = String(from);
    } else {
      raw = String(to);
    }
  }
  
  
  return extractNumsFromString(raw);
}


function extractNumsFromString(raw) {
  var allow = '0123456789', s = '', i, ch;
  for (i = 0; i < raw.length; i++) {
    ch = raw.charAt(i);
    if (~allow.indexOf(ch)) {
        s += ch;
    }
  }
  return parseInt(s);
}

/**
	 * @description Remove all no numbers chars  
	*/
	function nums(s){
		s = s.replace(/[\D]/mig, '');
		return s;
	}

/**
	 * @description Splits a long number of three characters. For example argument 1000000 return '1 000 000'
	*/
function money(s){
  s = String(s);
  var i, a = [], j = 0;
  s = nums(s);
  for (i = s.length - 1; i > -1 ; i--, j++) {
    if (j > 0 && (j % 3) ==  0) {
      a.push(' ');
    }
    a.push(s.charAt(i));
  }
  s = a.reverse().join('');
  return s;
}


function onKeyUp(evt) {
  console.log(evt);
  if (evt.ctrlKey == true && evt.keyCode == 67) {
    showStat();
  }
}

window.onload = onLoad;
