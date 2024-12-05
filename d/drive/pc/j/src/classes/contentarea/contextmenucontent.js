function ContextMenuContent() {
	this.name = "ContxtMenuContent";
}

ContextMenuContent.prototype.getHtmlTabMenuHtml = function() {
	var html = '\
		<div id="cmTabHtml" style="display:none">\
			<div class="contextMenu">\
				<div class="contextMenuItem" onclick="app.tabPanel.onClickUtf8()">\
					<div class="contextMenuItemIcon">\
					</div>\
					<div class="contextMenuItemText">' + L("Encoding")  + " UTF-8" + '</div>\
					<div class="cf"></div>\
				</div>\
				\
				<div class="contextMenuItem" onclick="app.tabPanel.onClickWindows1251()">\
					<div class="contextMenuItemIcon">\
					</div>\
					<div class="contextMenuItemText">' + L("Encoding")  + " Windows-1251" + '</div>\
					<div class="cf"></div>\
				</div>\
				\
				<div class="contextMenuItem" onclick="app.tabPanel.onClickKOI8R()">\
					<div class="contextMenuItemIcon">\
					</div>\
					<div class="contextMenuItemText">' + L("Encoding")  + " KOI8-R" + '</div>\
					<div class="cf"></div>\
				</div>\
				\
				<div class="contextMenuItem" onclick="app.tabPanel.onClickOther()">\
					<div class="contextMenuItemIcon">\
					</div>\
					<div class="contextMenuItemText">' + L("Other")   + '</div>\
					<div class="cf"></div>\
				</div>\
		</div>';
		return html;
}

ContextMenuContent.prototype.getBookmarkItemMenuHtml = function() {
	var html = '\
		<!-- User Bookmark menu -->\
		<div id="cmBmMenu" style="display:none">\
			<div class="contextMenu">\
				<div class="contextMenuItem" onclick="app.bookmarksManager.onClickOpen()">\
					<div class="contextMenuItemIcon">\
						<!--img src="./i/cm/sh16.png"-->\
					</div>\
					<div class="contextMenuItemText">' + L("Open") + '</div>\
					<div class="cf"></div>\
				</div>\
				<div class="contextMenuItem" onclick="app.bookmarksManager.onClickRemove()">\
					<div class="contextMenuItemIcon">\
						<!--img src="./i/cm/folder_new16.png"-->\
					</div>\
					<div class="contextMenuItemText">' + L("Remove bookmark") + '</div>\
					<div class="cf"></div>\
				</div>\
				<div class="contextMenuItem" onclick="app.bookmarksManager.onClickRename()">\
					<div class="contextMenuItemIcon">\
						<!--img src="./i/cm/filenew16.png"-->\
					</div>\
					<div class="contextMenuItemText">' + L("Rename bookmark") + '</div>\
					<div class="cf"></div>\
				</div>\
				<div class="contextMenuItem" onclick="app.bookmarksManager.onClickUp()">\
					<div class="contextMenuItemIcon">\
						<img src="./i/cm/up22.png">\
					</div>\
					<div class="contextMenuItemText">' + L("Move up") + '</div>\
					<div class="cf"></div>\
				</div>\
				<div class="contextMenuItem" onclick="app.bookmarksManager.onClickDown()">\
					<div class="contextMenuItemIcon">\
						<img src="./i/cm/down22.png">\
					</div>\
					<div class="contextMenuItemText">' + L("Move down") + '</div>\
					<div class="cf"></div>\
				</div>\
				<div class="contextMenuItem" onclick="app.bookmarksManager.onClickExportBookmarks()">\
					<div class="contextMenuItemIcon">\
						<img src="./i/cm/saveAll22.png">\
					</div>\
					<div class="contextMenuItemText">' + L("Export bookmarks") + '</div>\
					<div class="cf"></div>\
				</div>' +
				`<label for="importBmFi" class="contextMenuItem">
					<div class="contextMenuItemIcon">
						<img src="./i/cm/undo22.png">
					</div>
					<div class="contextMenuItemText">` + L("Import bookmarks") + `</div>
					<div class="cf"></div>
					<input type="file" id="importBmFi" accept=".json" style="display:none" onchange="app.bookmarksManager.onClickImportBookmarks(window.event)">
				</label>` + '\
			</div>\
		</div>\
		<!-- System Bookmark menu -->\
		<div id="cmBmSysMenu" style="display:none">\
			<div class="contextMenu">\
				<div class="contextMenuItem" onclick="app.bookmarksManager.onClickOpen()">\
					<div class="contextMenuItemIcon">\
						<!--img src="./i/cm/sh16.png"-->\
					</div>\
					<div class="contextMenuItemText">' + L("Open") + '</div>\
					<div class="cf"></div>\
				</div>\
			</div>\
		</div>';
	return html;
}

ContextMenuContent.prototype.getUploadItemHtml = function() {
	return `<label class="contextMenuItem">
					<div class="contextMenuItemIcon">
						<img src="./i/cm/undo16.png">
					</div>
					<div class="contextMenuItemText">` + L("Upload") + `</div>
					<div class="cf"></div>
					<input id="iFile" multiple type="file" style="display:none" onchange="app.tab.onClickUpload(window.event)">
				</label>`;
}
ContextMenuContent.prototype.getCatalogMenuHtml = function() {
	var html = '\
		<!-- context menu example -->\
		<div id="cmCatalog" style="display:none">\
			<div class="contextMenu">' + 
				this.getUploadItemHtml() +
				'<div class="contextMenuItem" onclick="app.tab.onClickOpen()">\
					<div class="contextMenuItemIcon">\
						<img src="./i/cm/open16.png">\
					</div>\
					<div class="contextMenuItemText">' + L("Open") + '</div>\
					<div class="cf"></div>\
				</div>\
				\
				<div class="contextMenuItem" onclick="app.tab.onClickOpenNewTab()">\
					<div class="contextMenuItemIcon">\
						&nbsp;\
					</div>\
					<div class="contextMenuItemText">' + L("Open in new tab") + '</div>\
					<div class="cf"></div>\
				</div>\
				\
				<div class="contextMenuItem" onclick="app.tab.onClickAddBookmark()">\
					<div class="contextMenuItemIcon">\
						<img src="./i/cm/folderStar16.png">\
					</div>\
					<div class="contextMenuItemText">' + L("Add bookmark") + '</div>\
					<div class="cf"></div>\
				</div>\
				<div class="contextMenuItem" onclick="app.tab.onClickPaste()">\
					<div class="contextMenuItemIcon">\
						<img src="./i/cm/pst16.png">\
					</div>\
					<div class="contextMenuItemText">' + L("Paste") + '</div>\
					<div class="cf"></div>\
				</div>\
				\
				<div class="contextMenuItem" onclick="app.tab.onClickRemove()">\
					<div class="contextMenuItemIcon">\
						<img src="./i/cm/cross16.png">\
					</div>\
					<div class="contextMenuItemText">' + L("Remove") + '</div>\
					<div class="cf"></div>\
				</div>\
				\
				<div class="contextMenuItem" onclick="app.tab.onClickRename()">\
					<div class="contextMenuItemIcon">\
						&nbsp;\
					</div>\
					<div class="contextMenuItemText">' + L("Rename") + '</div>\
					<div class="cf"></div>\
				</div>\
				\
				<div class="contextMenuItem" onclick="app.tab.onClickProps()">\
					<div class="contextMenuItemIcon">\
						<img src="./i/cm/pencil16.png">\
					</div>\
					<div class="contextMenuItemText">' + L("Properties") + '</div>\
					<div class="cf"></div>\
				</div>\
				<div class="contextMenuItem" onclick="app.tab.onClickAbout()">\
					<div class="contextMenuItemIcon">\
						<img src="./i/cm/info16.png">\
					</div>\
					<div class="contextMenuItemText">' + L("About") + '</div>\
					<div class="cf"></div>\
				</div>\
				\
			</div>\
		</div>\
		<!-- /context menu example -->\
		';
		return html;
}

ContextMenuContent.prototype.getArjItemMenuHtml = function() {
	return this.getDefaultFileCm("cmArch");
}
ContextMenuContent.prototype.getDefaultFileCm = function(i) {
	var html = '<div id="' + i + '" style="display:none">\
			<div class="contextMenu">' + 
			    this.getUploadItemHtml() + 
				'<div class="contextMenuItem" onclick="app.tab.onClickDownload()">\
					<div class="contextMenuItemIcon">\
						<img src="./i/cm/exec16.png">\
					</div>\
					<div class="contextMenuItemText">'+ L('Download') + '</div>\
					<div class="cf"></div>\
				</div>\
				<div class="contextMenuItem" onclick="app.tab.onClickSharing()">\
					<div class="contextMenuItemIcon">\
						<img src="./i/cm/web16.png">\
					</div>\
					<div class="contextMenuItemText">'+ L('Sharing') + '</div>\
					<div class="cf"></div>\
				</div>\
				<div class="contextMenuItem" onclick="app.tab.onClickCut()">\
					<div class="contextMenuItemIcon">\
						<img src="./i/cm/cut16.png">\
					</div>\
					<div class="contextMenuItemText">'+ L('Cut') + '</div>\
					<div class="cf"></div>\
				</div>\
				<div class="contextMenuItem" onclick="app.tab.onClickPaste()">\
					<div class="contextMenuItemIcon">\
						<img src="./i/cm/pst16.png">\
					</div>\
					<div class="contextMenuItemText">'+ L('Paste') + '</div>\
					<div class="cf"></div>\
				</div>\
				\
				<div class="contextMenuItem" onclick="app.tab.onClickRemove()">\
					<div class="contextMenuItemIcon">\
						<img src="./i/cm/cross16.png">\
					</div>\
					<div class="contextMenuItemText">' + L("Remove") + '</div>\
					<div class="cf"></div>\
				</div>\
				\
				<div class="contextMenuItem" onclick="app.tab.onClickRename()">\
					<div class="contextMenuItemIcon">\
						&nbsp;\
					</div>\
					<div class="contextMenuItemText">' + L("Rename") + '</div>\
					<div class="cf"></div>\
				</div>\
				\
				<div class="contextMenuItem" onclick="app.tab.onClickProps()">\
					<div class="contextMenuItemIcon">\
						<img src="./i/cm/pencil16.png">\
					</div>\
					<div class="contextMenuItemText">' + L("Properties") + '</div>\
					<div class="cf"></div>\
				</div>\
				\
				<div class="contextMenuItem" onclick="app.tab.onClickAbout()">\
					<div class="contextMenuItemIcon">\
						<img src="./i/cm/info16.png">\
					</div>\
					<div class="contextMenuItemText">' + L("About") + '</div>\
					<div class="cf"></div>\
				</div>\
				\
			</div>\
		</div>';
		
		return html;
}
