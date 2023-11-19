window.VersionReq = {
	onGetVersion: function(data) {
		console.log(data.v);
		this.onSuccess.call(this.ctx, data.v);
	},
	get: function(ctx, onSuccess) {
		var o = this;
		o.ctx = ctx;
		o.onSuccess = onSuccess;
		Rest._get(
			function(data){
				o.onGetVersion(data);
			},
			
			br + '/driveversion.json?' + 'm=' + time(), 
			
			function(data, responseText, info, xhr){}
		);
	}
}
