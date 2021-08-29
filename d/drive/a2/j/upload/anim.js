function LoaderAnim(imgId, minFrame, maxFrame, pBarId, startPBarX, maxPBarX)  {
	this.minFrame = minFrame;
	this.maxFrame = maxFrame;
	this.currentFrame = 1;
	this.img = e(imgId);
	this.pause = true;
	
	var o = this;
	this.img.onload = function(){
		if (o.pause) {
			return;
		}
		setTimeout(function(){
			if (o.pause) {
				return;
			}
			o.onTickSlide();
		}, 1000);
	}
	
	this.pBar = e(pBarId);
	this.startPBarX = startPBarX;
	this.maxPBarX = maxPBarX;
	this.currentPBar = startPBarX;
}

var P = LoaderAnim.prototype;

P.onTickSlide = function(){
	this.currentFrame++;
	if (this.currentFrame > this.maxFrame) {
		this.currentFrame = this.minFrame;
	}
	attr(this.img, 'src', '/d/drive/a2/i/up/' + this.currentFrame + '.png');
}

P.run = function(){
	this.pause = false;
	this.onTickSlide();
	
	var o = this;
	this.pBarIval = setInterval(function() {
		o.onTickPbar();
	}, 42);
}

P.onTickPbar = function(){
	if (!this.pBar) {
		return;
	}
	this.currentPBar++;
	if (this.currentPBar > this.maxPBarX) {
		this.currentPBar = this.startPBarX;
	}
	this.pBar.style['margin-left'] = this.currentPBar + '%';
}

P.stop = function() {
	this.pause = true;
	this.currentFrame = this.minFrame;
	attr(this.img, 'src', '/d/drive/a2/i/up/' + this.currentFrame + '.png');
	
	if (this.pBarIval) {
		clearInterval(this.pBarIval);
		this.currentPBar = this.startPBarX;
		this.pBar.style['margin-left'] = this.currentPBar + '%';
	}
	
}
