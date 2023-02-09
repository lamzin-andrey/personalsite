window.addEventListener('load', onLoad);

function onLoad() {
	var ul = document.getElementById("bList"),
		ls = ul.getElementsByTagName("li"),
		i,
		a = [], s;
	
	for (i = 0; i < ls.length; i++) {
		a.push(ls[i].innerHTML);
	}
	
	ul.innerHTML = "";
	
	for (i = a.length - 1; i > -1; i--) {
		s = '<li>' + a[i] + '</li>';
		ul.innerHTML += s;
	}
}
