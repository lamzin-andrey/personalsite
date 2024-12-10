class T {
	constructor(){
		window.LST = `Hello!`;
	}
	async a() {
		setTimeout(() => {
		  window.LAE = 1;
		}, 1);
	}
}

window.t = new T();
t.a();
