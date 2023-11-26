/**
 * @class LandFileManager Надстройка над FileSystem для более удобной работы с файловой системой
*/
class LandFileManager {

	/**
	 * 
	 * @param {Number} nRequestedSizeMb число в мегабайтах, сколько хотите отжать диска под свои нужды
	 */
	constructor (nRequestedSizeMb) {
		if (window.File && window.FileReader && window.FileList && window.Blob /*&& window.requestFileSystem*/) {
			this._isAccessible = true;
			this._requestedSizeMb = nRequestedSizeMb;
		} else {
			this._isAccessible = false;
		}
	}
	/**
	 * @return Boolean true если браузер в принципе предоставляет возможность писать в файлы
	*/
	isAccessible() {
		return this._isAccessible;
	}
	/**
	 * @param {String} sFileName  полное имя файла
	 * @param {String} sData строка для записи
	 * @param {Function} fResultHandler функция - обработчик результата успешной или неуспешной записи в файл
	 * 						fResultHandler argument {success: Boolean, [errorInfo: String]}
	 */
	writeFile(sFileName, sData, fResultHandler) {
		if (this.writeProcessIsStart) {
			this.fResultHandler({error: true, errorInfo: 'Already running process writing to file "' + this.currentFileName + '"'});
			return;
		}
		/** @property {Boolean}	writeProcessIsStart	принимает true когда вызван writeFile и остается в этом состоянии, пока запись в файл не завершена (в том числе и неуспешно) */
		this.writeProcessIsStart = true;
		/** @property {String} sLastError сюда собираютсяч все ошибки по ходу записи данных в файл */
		this.sLastError = '';
		this.currentFileName = sFileName;
		this.currentStringData = sData;
		this.fResultHandler = fResultHandler;
		//PERSISTENT | TEMPORARY
		window.webkitRequestFileSystem(window.PERSISTENT, this._requestedSizeMb * 1024 * 1024,
			(fileSystem) => {this.onInitFileSystemForWrite(fileSystem);}, 
			(oError) => {this.onFailWriteFile(oError);});
	}

	/**
	 * TODO избавиться от использования переменных использующихся для write операции
	 * @param {String} sFileName  полное имя файла
	 * @param {Function} fResultHandler функция - обработчик результата успешной или неуспешной записи в файл
	 * 						fResultHandler argument {success: Boolean, data:String,[errorInfo: String]}
	 */
	readFile(sFileName,  fResultHandler) {
		if (this.writeProcessIsStart) {
			this.fResultHandler({error: true, errorInfo: 'Already running process writing to file "' + this.currentFileName + '"'});
			return;
		}
		/** @property {Boolean}	writeProcessIsStart	принимает true когда вызван writeFile и остается в этом состоянии, пока запись в файл не завершена (в том числе и неуспешно) */
		this.writeProcessIsStart = true;
		/** @property {String} sLastError сюда собираютсяч все ошибки по ходу записи данных в файл */
		this.sLastError = '';
		this.currentFileName = sFileName;
		this.fResultHandler = fResultHandler;
		//PERSISTENT | TEMPORARY
		window.webkitRequestFileSystem(window.TEMPORARY, this._requestedSizeMb * 1024 * 1024,
			(fileSystem) => {this.onInitFileSystemForRead(fileSystem);}, 
			(oError) => {this.onFailWriteFile(oError);});
	}

	/**
	 * @description Обработка успешного запроса к доступу к файловой системе
	 * @param {FileSystem} fileSystem 
	 */
	onInitFileSystemForRead(fileSystem) {
		fileSystem.root.getFile(this.currentFileName, {create: false}, (oFileEntry) => { this.onFileEntry(oFileEntry, true); }, (oError) => { this.onFailWriteFile(oError); } );
	}

	/**
	 * @description Обработка успешного запроса к доступу к файловой системе
	 * @param {FileSystem} fileSystem 
	 */
	onInitFileSystemForWrite(fileSystem) {
		fileSystem.root.getFile(this.currentFileName, {create: true}, (oFileEntry) => { this.onFileEntry(oFileEntry); }, (oError) => { this.onFailWriteFile(oError); } );
	}
	/**
	 * @description Когда стал доступен fileEntry
	 * @param {FileEntry} oFileEntry Позволяет создать FileWriter, больше нам от него ничего и не надо
	 */
	onFileEntry(oFileEntry, bForRead = false) {
		if (!bForRead) {
			oFileEntry.createWriter((oFileWriter) => { this._writeCurrentString(oFileWriter); }, (oError) => {this.onFailWriteFile(oError); });
		} else {
			//TODO это эксперименты
			// Get a File object representing the file,
			// then use FileReader to read its contents.
			console.log('fP = ', oFileEntry.getParent((a, b, c) => { console.log(a, b, c); }, (a, b, c) => { console.log(a, b, c); }));
			var that = this;
		    oFileEntry.file(function(file) {
				var reader = new FileReader();
				reader.onloadend = function(e) {
				  /*var txtArea = document.createElement('textarea');
				  txtArea.value = this.result;
				  document.body.appendChild(txtArea);*/
					var result = this.result;
					that.fResultHandler({success:true, data:result});
				};
				reader.readAsText(file);
			 }, (e) => { this.onFailWriteFile(e); });
		}
	} 
	/**
	 * @description Собственно, запись в файл, но сначала создадим ещё обработчиков разных случаев
	 * @param {FileWriter} oFileWriter
	 */
	_writeCurrentString(oFileWriter) {

		
		oFileWriter.onwriteend = () => {
			this.writeProcessIsStart = false;
			this.fResultHandler({success: true});
		};
		let blob = new Blob(['hw']);
		//blob.append(this.currentStringData);
		oFileWriter.write(blob); /*.getBlob('text/plain')*/
		

		
		oFileWriter.onerror = (oError) => {
			this.writeProcessIsStart = false;
			this.sLastError += oError.toString() + '\n';
			this.fResultHandler({success: false, errorInfo: this.sLastError});
		}
		
	}
	/**
	 * @description Обработка не успешного запроса к доступу к файловой системе
	 * @param {Object} oError
	 */
	onFailWriteFile(oError) {
		console.log(oError);
		this.writeProcessIsStart = false;
		switch (oError.code) {
			/*case FileError.QUOTA_EXCEEDED_ERR:
				this.sLastError += 'QUOTA_EXCEEDED_ERR\n';
				break;
			case FileError.NOT_FOUND_ERR:
				this.sLastError += 'NOT_FOUND_ERR\n';
				break;
			case FileError.SECURITY_ERR:
				this.sLastError += 'SECURITY_ERR\n';
				break;
			case FileError.INVALID_MODIFICATION_ERR:
				this.sLastError += 'INVALID_MODIFICATION_ERR\n';
				break;
			case FileError.INVALID_STATE_ERR:
				this.sLastError += 'INVALID_STATE_ERR\n';
				break;*/
			default:
				let s = oError.toString();
				this.sLastError += (s ? (s + '\n') : 'Unknown Error\n');
				break;
		}
		this.fResultHandler({success: false, errorInfo: this.sLastError});
	}
}

export default LandFileManager;