function showScreen(id) {
	var ls,  i, SZ;
	ls = cs(document, "scr");
	SZ = sz(ls);
	for (i = 0; i < SZ; i++) {
		hide(ls[i]);
	}
	show(id);
}
