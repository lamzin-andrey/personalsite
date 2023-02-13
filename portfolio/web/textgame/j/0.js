window.onload = function() {
	e("bGo").onclick = function () {
		location.href = "/portfolio/web/textgame/" + e("iNum").value + ".html";
	}
}

function e(i) {
	return document.getElementById(i);
}
