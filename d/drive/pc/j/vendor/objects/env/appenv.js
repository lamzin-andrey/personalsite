function DevNull(){}
/**
 *  envObject.USER = window.USER;
    envObject.IS_KDE = window.IS_KDE;
    envObject.IS_KDE5 = window.IS_KDE5;
    envObject.IS_XFCE = window.IS_XFCE;
    envObject.IS_UNITY = window.IS_UNITY;
	envObject.IS_MINT = window.IS_MINT;
	envObject.USER_KDE_AUTORUN_FOLDER = window.USER_AUTORUN_FOLDER;
	envObject.XFCE_ICON_THEME = window.XFCE_ICON_THEME;
	envObject.QDJS_VERSION = window.QDJS_VERSION;
 * */
window.AppEnv = {
	init:function(aCallback, aPreCallback) {
		this.config = {};
		this.readLastSettings();
		this.aCallback = aCallback;
		this.onUserData();
	},
	onUserData:function() {
		var envObject = {}, F = false;
		window.USER = storage("username");
		window.USER = window.USER ? window.USER : "wusb";
		
		envObject.USER = window.USER;
		envObject.IS_KDE = F;
		envObject.IS_KDE5 = envObject.IS_KDE5 = F;
		envObject.IS_XFCE = F;
		envObject.IS_UNITY = F;
		envObject.IS_MINT = F;
		envObject.USER_KDE_AUTORUN_FOLDER = F;
		envObject.XFCE_ICON_THEME = F;
		envObject.QDJS_VERSION = F;
		
		
		if (this.aCallback && this.aCallback[1] instanceof Function) {
			this.aCallback[1].apply(this.aCallback[0]);
		}
	},
	readLastSettings:function() {
		this.config = {
			USER: 'wusb',
			IS_KDE: false,
			IS_KDE5: false,
			IS_XFCE: false,
			IS_UNITY: false,
			IS_MINT: false,
			USER_KDE_AUTORUN_FOLDER: '',
			XFCE_ICON_THEME: '',
			QDJS_VERSION : ''
		};
		
	}
	
};
v("s", "files")
