/** @class LandGUIFileManager тут будет GUI для выбора файла, в который надо сохранить JSON если не удастся всё-таки задействовать */

class LandGUIFileManager {
	constructor () {

	}
	/**
	 * 
	 * @param {String} sCurrentDir 
	 * @param {String} sExtensionsFilter 
	 * @return Promise
	 */
	openFileDialog(sCurrentDir, sExtensionsFilter) {
		let that = this;

		return new Promise( (resolve, reject) =>{
			that.resolver = resolve;
			that.rejector = reject;
			console.log('Start timeout, we search file..');
			setTimeout(() => {
				console.log('Before Call onSelectFile..');
				that.onSelectFile();
			}, 1 * 1000);
		} );
	}
	onSelectFile() {
		console.log('Before Call this.resolver..');
		this.resolver('log01042020i1.log');
	}
}

export default LandGUIFileManager;