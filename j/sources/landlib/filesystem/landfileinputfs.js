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
			console.log('Start timeout, we read file..');
			let fr = new FileReader();
			
			fr.onloadend = (result) => {
				let JSONData = fr.result;
				console.log('Read complete!', result);
				resolve(fr.result);
			};
			
			fr.readAsText(fileInput.files[n]);
			
		} );
		
		
	}
}


// export default LandFileInputFS;
