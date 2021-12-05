function sortSettingDlgInit() {
	e('bCloseSortSetings').onclick = onClickBClose;
	e('cbSortByNameAsc').addEventListener('click', onClickChSortByNameAsc, true);
	e('cbSortByNameDesc').addEventListener('click', onClickChSortByNameDesc, true);
	e('cbSortBySizeAsc').addEventListener('click', onClickChSortBySizeAsc, true);
	e('cbSortBySizeDesc').addEventListener('click', onClickChSortBySizeDesc, true);
	e('cbSortByModifyTimeAsc').addEventListener('click', onClickChSortByModifyTimeAsc, true);
	e('cbSortByModifyTimeDesc').addEventListener('click', onClickChSortByModifyTimeDesc, true);
	
	var currentSort = fileList.getStoredSort(),
		name = 'cbSortBy';
	if (currentSort.t == 'name') {
		name += 'Name';
	}
	if (currentSort.t == 'size') {
		name += 'Size';
	}
	if (currentSort.t == 'time') {
		name += 'ModifyTime';
	}
	
	if (currentSort.d == 'd') {
		name += 'Asc';
	} else {
		name += 'Desc';
	}
	
	onClickA2Cb({currentTarget:e(name)});
	
}

function onClickBClose() {
	hideLoader();
	hideOptions();
}

function onClickChSortByNameAsc(evt) {
	storage('sort', {t: 'name', d: 'a'});
	closeSortSettDlg();
}

function onClickChSortByNameDesc(evt) {
	storage('sort', {t: 'name', d: 'd'});
	closeSortSettDlg();
}

function onClickChSortBySizeAsc(evt) {
	storage('sort', {t: 'size', d: 'a'});
	closeSortSettDlg();
}

function onClickChSortBySizeDesc(evt) {
	storage('sort', {t: 'size', d: 'd'});
	closeSortSettDlg();
}

function onClickChSortByModifyTimeAsc(evt) {
	storage('sort', {t: 'time', d: 'a'});
	closeSortSettDlg();
}

function onClickChSortByModifyTimeDesc(evt) {
	storage('sort', {t: 'time', d: 'd'});
	closeSortSettDlg();
}

function closeSortSettDlg() {
	var ls = storage('f' + currentDir),
		bc = path.replace('/wcard', '');
	fileList.renderCurrentDir(ls, bc);
	hideOptions();
}
