// ==UserScript==
// @name        PmaExportGzip
// @namespace   http://test.loc/pma3/*
// @description  - Automatically set select "compression" in value "gzip" on a export page
// @include     http://test.loc/pma3/*
// @include     http://test.loc/pma/*
// @include     http://mysql.redz.ru/*
// @include     https://ruvip16.hostiman.ru/phpmyadmin/*

// @version     1
// @grant       none
// ==/UserScript==

/**
 * Supported versions PhpMyAdmin 4.5.4, 4.0.8, 4.9.1, 4.4.15.10
*/

window.onload = main;
function main(){
	setGzipSelect();
  setInterval(function(){
    setGzipSelect();
  }, 1000);
}
function e(i){return document.getElementById(i);}

function setGzipSelect(){
  var select = e('compression');
  if (select.tagName == 'SELECT') {
    selectByValue(select, 'gzip');
  }
}

/**
 * @description Выделяет элемент выпадающего списка по его value
 * @return {Boolean} если удалось найти такое значение и выделить, true
*/
function selectByValue(g, v) {
  if (g.value == v) {
    return;
  }
	for (var i = 0; i < g.options.length; i++) {
		if (g.options[i].value == v) {
			g.options[i].selected = true;
			g.selectedIndex = i;
			return true;
		}
	}
	return false;
}
