function fileMgrLayout() {
	return '<table id="fmHtml" cellpadding="0" cellspacing="0" class="layout">\
<tr>\
    <td class="sidebar"  width="300">\
		<div id="sidebarWrapper">\
			<div class="homeButtonsWrapper">\
				<img id="btnBack" class="pointer imgHomeP imgHomeLeft" src="./i/leftArrow32d.png">\
				<img id="btnFwd" class="pointer imgHomeP imgHomeRight" src="./i/rightArrow32.png">\
				<img id="btnUp" class="pointer imgHomeP imgHomeUp" src="./i/upArrow32d.png">\
				<img id="btnHome" class="pointer imgHomeP imgHome" src="./i/home32.png">\
			</div>\
			<div id="sbScroller" class="sbScroller">\
				\
				<div class="section" id="bookmarksBlock">\
					<div class="sectionTitle devText">��������</div>\
					<div class="sectionContent">\
						<div class="sectionItem ">\
							<img src="./i/home32.png" class="i24">\
							<span class="i24Text">andrey</span>\
						</div>\
						<div class="sectionItem ">\
							<img src="./i/folder32.png" class="i24">\
							<span class="i24Text">�����������</span>\
						</div>\
						<div class="sectionItem selected">\
							<img src="./i/folder32.png" class="i24">\
							<span class="i24Text">��������</span>\
						</div>\
						\
					</div>\
				</div>\
				\
				<div class="section" id="devicesBlock">\
					<div class="sectionTitle devText">����������</div>\
					<div class="sectionContent">\
						<div class="sectionItem ">\
							<img src="./i/disk32.png" class="i24">\
							<span class="i24Text">�������� �������</span>\
						</div>\
						<div class="sectionItem ">\
							<img src="./i/phone32.png" class="i24">\
							<span class="i24Text">SDM660 MTP SN:571746FA</span>\
						</div>\
						<div class="sectionItem selected">\
							<img src="./i/usb32.png" class="i24">\
							<span class="i24Text">KINGSTON</span>\
						</div>\
						\
					</div>\
				</div>\
				\
			</div>\
			\
		</div>\
    </td>\
    <td class="content" id="lastM">\
    <div id="contentArea">\
		\
		<div id="addressContainer" class="addressContainer">\
			<div id="addressLine" class="addressLine">\
				<img src="./i/folder32.png" id="addressLineIcon" class="addressLineIcon">\
				<input id="iAddressLine" class="iAddressLine">\
				<img src="./i/reload32.png" id="addressLineReloadIcon" class="addressLineIcon">\
			</div>\
			<div id="addressButtonPlacer" class="addressButtonPlacer">\
				<div class="addressButton addressButtonLeft">\
					<img id="addressButtonLeft" class="addressButtonLeftIcon" src="./i/addressButtonLeft.png">\
				</div>\
				<div class="addressButton  addressButtonWithIcon addressButtonActive ">\
					<img id="addressButtonRight" class="addressButtonIcon" src="./i/addressButtonRight.png">\
					<span>andrey</span>\
				</div>\
				<div class="addressButton">\
					<span>tmp</span>\
				</div>\
				\
				<div class="addressButton">\
					<span>00</span>\
				</div>\
				\
				<div class="addressButton">\
					<span>05</span>\
				</div>\
				\
				<div class="addressButton addressButtonRight">\
					<img class="addressButtonRightIcon" src="./i/addressButtonRight.png">\
				</div>\
				\
				<div class="cf"></div>\
			</div>\
		</div> <!-- addressContainer -->\
		\
		\
		<div id="tabsContainer" class="tabsContainer">\
			<div id="tabsNavLeft" class="tabsNavLeft">\
				<img class="imgTabNavLeft" src="./i/addressButtonLeft.png">\
			</div>\
			<div class="tabsPlacer" id="tabsPlacer"> <!-- Width tabPlacer calculate dynamic px -->\
				<div class="tab">		<!-- Width tab calculate dynamic px. Quantity tabs calculate dynamic. �� ������ ���� ����� �������, ����� ����� �����������. -->\
					<div class="tabName">05</div>\
					<div class="tabClose">\
						<img class="pointer imgBtnTabClose" src="./i/tabClose.png">\
					</div>\
				</div>\
				\
				<div class="tab active">\
					<div class="tabName">andrey</div>\
					<div class="tabClose">\
						<img class="pointer imgBtnTabClose" src="./i/tabClose.png">\
					</div>\
				</div>\
				\
				<div class="cf"></div>\
			</div>\
			<div id="tabsNavRight" class="tabsNavRight">\
				<img class="imgTabNavRight" src="./i/addressButtonRight.png">\
			</div>\
			<div class="cf"></div>\
		</div> <!-- tabsContainer -->\
		\
		\
		<div id="tabContent" class="tabContentMainContainer">\
			<div id="tabContentHeadersWr" style="overflow-x:hidden" class="tabContentHeadersWr">\
				<div id="tabContentHeaders" class="pointer tabContentHeaders">\
					<div id="tabContentHeaderFileName" class="tabContentHeader tabContentHeaderFileName fl">\
						<div class="tabContentHeaderName fl">��������</div>\
						<div class="tabContentHeaderImg fl">\
							<img class="imgTabContentHeaderImg" src="./i/tabContentHeaderImgB.png">\
						</div>\
						<div class="cf"></div>\
					</div>\
					\
					<div id="tabContentHeaderSize" class="tabContentHeader tabContentHeaderSize fl">\
						<div class="tabContentHeaderName fl">������</div>\
						<div class="tabContentHeaderImg fl">\
							<img class="imgTabContentHeaderImg d-none" src="./i/tabContentHeaderImgT.png">\
						</div>\
						<div class="cf"></div>\
					</div>\
					\
					<div id="tabContentHeaderType" class="tabContentHeader tabContentHeaderType fl">\
						<div class="tabContentHeaderName fl">���</div>\
						<div class="tabContentHeaderImg fl">\
							<img class="imgTabContentHeaderImg d-none" src="./i/tabContentHeaderImgT.png">\
						</div>\
						<div class="cf"></div>\
					</div>\
					\
					<div id="tabContentHeaderDate" class="tabContentHeader tabContentHeaderDate fl">\
						<div class="tabContentHeaderName fl">���� ���������</div>\
						<div class="tabContentHeaderImg fl">\
							<img class="imgTabContentHeaderImg d-none" src="./i/tabContentHeaderImgT.png">\
						</div>\
						<div class="cf"></div>\
					</div>\
					<div class="cf"></div>\
				</div><!-- / tabContentHeaders -->\
			</div>\
			<div class="cf"></div>\
			<!-- Width tabContentItem must be calculated dinamic. It eq sum(all headers width) -->\
			<div class="tabContentItems" id="tabItems" data-cmid="cmEmptyCatalogArea" data-id="1" data-handler="onContextMenu" data-handler-context="tab">\
				<div data-cmid="cmExample" data-id="f1001">\
					<div class="tabContentItem">\
						<div class="tabContentItemNameMain fl">\
							<div class="tabContentItemIcon fl">\
								<img class="imgTabContentItemIcon" src="./i/folder32.png">\
							</div>\
							<div class="tabContentItemName fl">����� �����</div>\
							<div class="cf"></div>\
						</div>\
						\
						<div class="tabContentItemSize fl">\
							<div class="tabContentItemName">4,1 ��</div>\
						</div>\
						\
						<div class="tabContentItemType fl">\
							<div class="tabContentItemName">���� �������� �������� �����</div>\
						</div>\
						\
						<div class="tabContentItemDate fl">\
							<div class="tabContentItemDate">2022-06-16 21:30:37</div>\
						</div>\
						<div class="cf"></div>\
					</div> <!-- /tabContentItem -->\
					<div class="cf"></div>\
				</div>\
				\
				<div>\
					<div class="tabContentItem active">\
						<div class="tabContentItemNameMain fl">\
							<div class="tabContentItemIcon fl">\
								<img class="imgTabContentItemIcon" src="./i/folder32.png">\
							</div>\
							<div class="tabContentItemName fl">���� ���-�� � ���-��</div>\
							<div class="cf"></div>\
						</div>\
						\
						<div class="tabContentItemSize fl">\
							<div class="tabContentItemName">4,1 ��</div>\
						</div>\
						\
						<div class="tabContentItemType fl">\
							<div class="tabContentItemName">�����</div>\
						</div>\
						\
						<div class="tabContentItemDate fl">\
							<div class="tabContentItemName">2022-06-16 21:30:37</div>\
						</div>\
						<div class="cf"></div>\
					</div> <!-- /tabContentItem -->\
					<div class="cf"></div>\
				</div>\
				\
				\
			</div><!-- / tabContentItems -->\
			\
			\
				\
		</div> <!-- /#tabContent -->\
		<div class="tabContentStatus">\
			<div class="tabContentStatusText" ><span id="statusLdrPlacer"></span> <span id="statusText">12 ��������, �������� 119 ��</span></div>\
		</div>\
		\
		\
		\
		\
	</div><!-- /contentArea -->\
    </td><!-- /layout -->\
</tr><!-- /layout -->\
</table><!-- /layout -->\
';
}


function setFMgrLayout() {
	try {
		appendChild(e('body'), 'div', fileMgrLayout(), {"class": "scr", "style": "display:none", id: "fmgr"});
	} catch(er) {
		alert(er);
	}
}

setFMgrLayout();
