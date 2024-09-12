class SwaggerCalcUI {
	constructor() {
		this.bCalc = e('bCalculate');
		this.iUrl = e('iUrl');
		this.iJson = e('iJson');
		this.iYamlResp200 = e('iYamlResp200');
		this.calc = new SwaggerCalc(this);
		this.setListeners();
	}
	
	setListeners(){
		let o = this;
		aevt(o.bCalc, "click", o.onClickCaculate, o);
	}
	
	onClickCaculate(ev){
		let o = this, r;
		r  =  o.calc.process(v('iMethod'), v('iUrl'), v('iJson'));
		v('iYamlResp200', r);
	}
}

window.addEventListener("load", () =>{
	new SwaggerCalcUI();
});
