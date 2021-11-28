window.A2CbData = {};
window.addEventListener('load', onLoadA2Cb, false);


function onClickA2Cb(evt){
	var t = evt.currentTarget,
				 img = ee(t, 'img')[0],
				 inp = ee(t, 'input')[0],
				 gb = 'green-border',
				 inputId = inp.id,
				 groupName,
				 group,
				 currentInput;
	if(inp.value == 1){
			inp.value = 0;
			attr(img, 'src', root + '/i/check_intive.jpg');
			removeClass(img, gb);
	} else {
			attr(img, 'src', root +	'/i/check_active.jpg');
			inp.value = 1;
			addClass(img, gb);
	}
	
	// apply radio behavior if it need
	initGroupsDataA2Cb();
	groupName = A2CbData.groups.itemToGroup[inputId];
	if (groupName) {
		group = A2CbData.groups.groupsToItem[groupName];
	}
	
	if (group) {
		sz(group);
		for (i = 0; i < SZ; i++) {
			if (group[i] == inputId) {
				continue;
			}
			currentInput = e(group[i]);
			if (currentInput) {
				t = currentInput.parentNode;
				img = ee(t, 'img')[0];
				currentInput.value = 0;
				attr(img, 'src', root + '/i/check_intive.jpg');
				removeClass(img, gb);
			}
		}
	}
	
}

function onLoadA2Cb(){
	var ls = cs(document, 'cbWrapper'), i;
	for(i = 0; i < sz(ls); i++){
		//ls[i].onclick = onClickA2Cb;
		ls[i].addEventListener('click', onClickA2Cb, false);
	}
}
/**
 * @description Set radio behavior for 
 * @param {Array} idList
*/
function setRadioBehaviorForCboxes(idList) {
	initGroupsDataA2Cb();
	var i, groupName = 'g' + Math.random();
	sz(idList);
	for (i = 0; i < SZ; i++) {
		A2CbData.groups.itemToGroup[idList[i]] = groupName;
		if (!A2CbData.groups.groupsToItem[groupName]) {
			A2CbData.groups.groupsToItem[groupName] = [];
		}
		A2CbData.groups.groupsToItem[groupName].push(idList[i]);
	}
}

function initGroupsDataA2Cb() {
	A2CbData.groups = A2CbData.groups || {};
	A2CbData.groups.itemToGroup = A2CbData.groups.itemToGroup || {};
	A2CbData.groups.groupsToItem = A2CbData.groups.groupsToItem || {};
}
