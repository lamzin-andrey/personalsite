/**
 * @depends https://github.com/eligrey/FileSaver.js/blob/master/src/FileSaver.js
 * */
class LandFileInputFS {
	/**
	 * @param {FileInput} fileInput input[type=file]
	 * @param {Number|String} n. Index in fileInput.files collection. May be String "all"
	 * @return Promise resolve as String or Array (if n = "all")
	*/
	async readfile(fileInput, n=0) {
		if ("all" != n) {
			n = parseInt(n);
			n = isNaN(n) ? 0 : n;
		}
		// TODO "all" support
		return new Promise( (resolve, reject) =>{
			this.resolver = resolve;
			this.rejector = reject;
			let fr = new FileReader();
			
			fr.onloadend = (result) => {
				resolve(fr.result);
			};
			
			fr.readAsText(fileInput.files[n]);
			
		} );
		
		
	}
	
	writefile(sName, data) {
		if (data instanceof Object) {
			data = JSON.stringify(data);
		}
		if (typeof(data) == "string") {
			data = new Blob([data], {type: "text/plain;charset=utf-8"});
		}
		window.saveAs(data, sName);
	}
	
}


// export default LandFileInputFS;
