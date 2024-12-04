window.VersionReq = {
	onGetVersion: function(data) {
		this.onSuccess.call(this.ctx, data.v);
	},
	get: function(ctx, onSuccess) {
		var o = this, t = new Date();
		o.ctx = ctx;
		o.onSuccess = onSuccess;
		Rest._get(
			function(data){
				o.onGetVersion(data);
			},
			
			br + '/driveversion.json?' + 'm=' + t.getTime(), 
			
			function(data, responseText, info, xhr){}
		);
	}
}
