/**
 * @class TestSm [artfon]
*/
function TestSm() {
	window.w = window;
	w.SE2D.app = w.SE2D.canvas.app = this;// SE2D.setApp(this);
	w.SE2D.gridCell = 8; //для оптимизации расчета столкновений, 8 взято как сторона "кирпича"
	w.SE2D.onLoadImages = this.onInit;
	w.SE2D.addGraphResources(["/d/drive/pc/i/logo.jpg", "t"
	]);
	
	//SE2D.canvas.onmousemove = this.onMouseMove;
	w.SE2D.onEnterFrame = this.onEnterFrame;
}
/**
 * @description this is SE2D
*/
TestSm.prototype.onInit = function() {
		var o = this;
		hide("logoWr");
		show("btlg");
		this.app.t = w.SE2D._root.t;
		this.app.t.visible = 1;
		this.app.t.scaleX = 1;
		this.app.t.scaleY = 1;
		this.app.t.go(/*150, 100*/ 1,1);

		this.app.dr = 3; 
		this.app.dx = 1;
		
		
		setInterval(function(){
		  o.app.dr += o.app.dx;
		  if (o.app.dr > 16 || o.app.dr < 3) {
			  o.app.dx *= -1;
			  
		  }
		}, 100);
		
		
	
		return;
}
/**
 * @description this is SE2D
*/
TestSm.prototype.onEnterFrame = function(e) {
  var app = this.app;
  if (!app || !app.t) {
	  return;
  }
  app.t.rotation += app.dr;
  
  //console.log(app.dr);
}
v("s", "recentdir")

window.addEventListener("load", function() {
	new SimpleEngine2D("btlg", 44);
	new TestSm();
});
