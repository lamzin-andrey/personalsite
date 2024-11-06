function AddressPanel() {
	this.name = "AddressPanel";
	this.buttonAddress   = new ButtonAddress();
}
AddressPanel.prototype.setPath = function(s, id) {
	this.buttonAddress.setPath(s, id);
}

AddressPanel.prototype.resize = function(s) {
	this.buttonAddress.render();
}


AddressPanel.prototype.showButtonAddress = function() {
	this.buttonAddress.show();
}
AddressPanel.prototype.show = function() {
	this.buttonAddress.show();
	
}
q("types");
