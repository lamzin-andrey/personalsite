w.r = "/sp/public";
function main() {
	de.act = "Dust";
	Rest._get(onGetToken, r + "/dast.json", de);
	e("viewerClose").onclick = onClickCloseJpg;
}
function onGetToken(d){
	Rest2._setToken(d.token, "_token");
	de.act = "getList";
	Rest._get(onList, r + "/wusbmodls.json", de);
}
function onList(d) {
	var i, SZ = sz(d.ls), t, b = e("hContent"), s;
	t = getTpl();
	t = str_replace(" c=\"", " class=\"", t);
	for (i = 0; i < SZ; i++) {
		s = str_replace("{name}", d.ls[i].name, t);
		s = str_replace("{sz}", d.ls[i].s, s);
		s = str_replace("{i}", d.ls[i].i, s);
		s = str_replace("{L}", d.ls[i].L, s);
		s = str_replace("{bg}", (i % 2 == 0 ? "ev" : "od"), s);
		b.innerHTML += s;
	}
	if (!SZ) {
		b.innerHTML = "А нету ничего!";
	}
	
}

function getTpl(){
	return '<div c="r {bg}" id="r{i}"><div c="float-left nm ml-2"><a href="{L}" target="_blank">{name} {sz}</a></div>\
	<img c="float-left" src="/d/drive/fd/i/exit48.png" title="В бан" onclick="ban({i})">\
	<img c="float-left" src="/d/drive/pc/i/reboot-v1.png" title="Одобрить" onclick="ok({i})">\
	<img c="float-left mt10p" src="/d/drive/pc/i/mi/jpg32.png" title="JPEG" onclick="lJpg({i})">\
	<div c="clearfix"></div>\
	</div>';
}

function ban(i) {
	de.act = "ban";
	Rest._post({i:i}, onBan, r + "/wusbmodrm.json", de);
}
function ok(i) {
	de.act = "ban";
	Rest._post({i:i}, onBan, r + "/wusbmodok.json", de);
}
function lJpg(i) {
	de.act = "lJpg";
	Rest._post({i:i}, onJpg, r + "/wusbmodjpg.json", de);
}
function onJpg(d) {
	if (d.d) {
		attr("hJpg", "src", "data:image/jpeg;base64," + d.d);
		hide("hContent");
		addClass("hJpgV", "strongBlock");
	}
}

function onBan(d) {
	if (d.i) {
		rm("r" + d.i);
	}
}

function onClickCloseJpg() {
	attr("hJpg", "src", "/d/drive/pc/i/ld/s.gif");
	removeClass("hJpgV", "strongBlock");
	show("hContent");
}

function de() {
	alert("Что-то не так при запросе " + de.act);
}
aevt(w, "load", main, w);
