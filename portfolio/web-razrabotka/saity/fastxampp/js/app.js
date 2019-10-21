$(document).addEvent('domready', initalize); 
function initalize() {
	//localStorage.clear(); // TODO remove this str
	window.App = {};
	initLocationInputs();
	initAddForm();
	initAutorizeForm();
	initUserControls();
}
function initUserControls() {
	
}
//
function onAuthorize(data) {
	if (data.success == 0) {
		$("autherror").removeClass("hide");
		$$(".aformwrap")[0].setStyle("height", "220px");
	} else {
		window.location.href = "/cabinet";
		$("autherror").removeClass("hide").addClass("hide");
		$$(".aformwrap")[0].setStyle("height", "170px");
	}
}
function initAutorizeForm() {
	if ($("alayer") && $("authlink")) {
		$("authlink").onclick = function() {
			if (to_i(uid)) {
				return true;
			}
			$("alayer").toggleClass("hide");
			$("login").focus();
			//mainsfrorm
			Tool.enableInputs("mainsfrorm",  !$("alayer").hasClass("hide") );
			
			return false;
		}
	}
	if ($("aop")) {
		$("aop").onclick = function() {
			Tool.post("/cabinet", {action:"login", phone:$("login").value, password:$("password").value}, onAuthorize);
		}
		$("password").onkeydown = function(e) {
			if (e.keyCode == 13) {
				Tool.post("/cabinet", {action:"login", phone:$("login").value, password:$("password").value}, onAuthorize);
			}
		}
	}
}
function initUploader() {
	try {
		var uploadReq = new Request.File({
			url: "/podat_obyavlenie?ajaxUpload=1",
			//onRequest: progress.setStyles.pass({display: 'block', width: 0}, progress),
			onProgress: function(event){
				var loaded = event.loaded, total = event.total;
				//progress.setStyle('width', parseInt(loaded / total * 100, 10).limit(0, 100) + '%');
			},
			onComplete: function(path){
				//progress.setStyle('width', '100%');
				$("ipath").value =  $("imgview").src = path.trim();
			}
		});
		for (var i = 0; i < sz($("image").files); i++) {
			uploadReq.append("file", $("image").files[i]);
		}
		uploadReq.send();	
	} catch(e) {
		App.sendForm = true;
	}
}
function onAddAdv(data) {
	$("addsubmit").disabled = false;
	var i, s, arr = [];
	if (data.success == 0) {
		for (i in data) {
			if (i != "success") {
				arr.push(data[i]);
			}
		}
		s = arr.join("<p>");
		$("mainsfrormerror").set("html", s).setStyle("display", "block");
		$("mainsfrormsuccess").set("html", '').setStyle("display", "none");
		scrollUp();
	} else if (data.success == 1) {
		$("mainsfrormsuccess").set("html", '<p>' + data.msg + '</p>').setStyle("display", "block");
		$("mainsfrormerror").set("html", '').setStyle("display", "none");
		scrollUp();
		if (!window.noredir) {
			setTimeout( function() {
				s = '';
				if ($("people").checked) {
					s += "&people=1";
				}
				if ($("box").checked) {
					s += "&box=1";
				}
				if ($("term").checked) {
					s += "&term=1";
				}
				if ($("far").checked) {
					s += "&far=1";
				}
				if ($("near").checked) {
					s += "&near=1";
				}
				if ($("piknik").checked) {
					s += "&piknik=1";
				}
				window.location.href = "/?city=" + $("city").value + "&country=" + $("country").value + "&region=" + $("region").value + s;
			}, 5*1000);
		}
		$("add").getElements("input").each(
			function(i) {
				if (i.type != "checkbox") {
					i.value = '';
				}
			}
		);
		$("addtext").value = "";
	}
}
function scrollUp() {
	window.App.scrollYDiv = 2;
	window.App.scrollYI = setInterval(up, 100);
}
function up() {
	var y = window.scrollY, dy = y - Math.round(y / window.App.scrollYDiv );
	if (dy < 1 || isNaN(dy)) dy = 0;
	window.scrollTo(0, dy);
	window.App.scrollYDiv -= 0.5;
	//console.log(dy);
	if (dy == 0) {
		clearInterval(window.App.scrollYI);
	}
}
function initAddForm() {
	if ($("add")) {
		$("add").onsubmit = function() {
			if (App.sendForm) {
				return true;
			}
			var data = {}, addr = window.location.href.split('?')[0];
			$("add").getElements("input").each(
				function(i) {
					if (i.type != "checkbox") {
						data[i.name] = i.value;
					} else {
						if (i.checked) {
							data[i.name] = 1;
						}
					}
				}
			);
			$("add").getElements("select").each(
				function(i) {
					data[i.name] = i.value;
				}
			);
			data.addtext = $("addtext").value;
			$("addsubmit").disabled = true;
			Tool.post(addr, data, onAddAdv);
			return false;
		}
		$("image").onchange = initUploader;
	}
	if ($$("a.smbr").length) {
		$$("a.smbr")[0].onclick = function() {
			$("cpi").src = "/images/random?e=" + Math.random();
			return false;
		}
	}
}
//locationgroup
function initLocationInputs() {
	//handlers
	if (!$("country") || !$("region") || !$("city")) {
		return;
	}
	$("country").onchange = function() {
		var cid = to_i(this.options[this.options.selectedIndex].value);
		if (cid) {
			Tool.cachepost("/location", {action:"region", countryId:cid}, onRegionList);
			localStorage.setItem("country", cid);
		}
	}
	$("region").onchange = function() {
		var cid = to_i(this.options[this.options.selectedIndex].value);
		if (cid) {
			Tool.cachepost("/location", {action:"city", regionId:cid}, onCityList);
			localStorage.setItem("region", cid);
		}
	}
	$("city").onchange = function() {
		var cid = to_i(this.options[this.options.selectedIndex].value);
		if (cid) {
			localStorage.setItem("city", cid);
		}
	}
	Tool.cachepost("/location", {action:"country"}, onCountryList);
}
//loadcountries
function fillLocSelect(id, data, name, getLast, lex) {
	var sl = $(id), n = 1;
	sl.options.length = 0;
	sl.options[0] = new Option(lex, 0);
	if(sz(data.list)) {
		data.list.each(
			function (i) {
				sl.options[n] = new Option(i[name], i.id);
				n++;
			}
		);
	}
	//get last
	if (getLast) {
		if (to_i(localStorage.getItem(id))) {
			selectByValue(id, localStorage.getItem(id));
		}else {
			$(id).onchange();
		}
	}
}
function onCountryList(data) {
	//var id = "country";
	//fillLocSelect(id, data, id + "_name", true, "Выберите страну");
	Tool.cachepost("/location", {action:"region", countryId:3}, onRegionList);
	localStorage.setItem("country", 3);
}
//load regions
function onRegionList(data) {
	var id = "region";
	fillLocSelect(id, data, id + "_name", true, "Выберите регион");
}
//load cities
function onCityList(data) {
	var id = "city";
	fillLocSelect(id, data, id + "_name", true, 'Выберите регион');
}		

	
