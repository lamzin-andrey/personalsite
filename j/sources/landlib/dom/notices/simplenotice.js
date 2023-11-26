window.SimpleNotice = {
	/**
	 * 
	 * @param {String} message 
	 * @param {String} title 
	 * @param {String} image 
	 */
	show:function(message, title, image){
		if (window.Notification) {
			function _notify(message, title, image) {
				var n = {};
				n.body = message;
				n.title = title;
				n.icon = image;
				n = new Notification(n.title, n);
				n.onclick = function() {
					window.focus();
					setTimeout(function(){
						n.close();}, 
					1000);
				}
			}
			if (Notification.permission === 'granted') {
				_notify(message, title, image);
			} else {
				if (!window.permissionNotifyRequested) {
					window.permissionNotifyRequested = 1;
					Notification.requestPermission(function(permission) {
						if (permission === 'granted') {
							_notify(message, title, image);
						}
					});
				}
			}
		}
	}
};