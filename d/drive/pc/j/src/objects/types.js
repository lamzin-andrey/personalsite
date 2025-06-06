window.Types = {
	get:function(path) {
		var a = path.split('.'),
			ext = a[sz(a) - 1].toLowerCase(),
			r = {
				t: L('File'),
				i: 0,
				c: 'cmDefault'
			},
			images = In('png', 'jpg', 'jpe', 'jpeg', 'jfif', 'gif', 'bmp'),
			texts  = In('txt', 'cpp', 'c', 'py', 'pas', 'json', 'rb', 'cs', 'pl', 'ini', 'conf', 'php'),
			docs   = In('doc', 'docx', 'odf', 'xlsx', 'xls', 'csv', 'pdf', 'ppt'),
			audio  = In('mp3', 'wav', 'ogg', 'flac', ''),
			video  = In('mp4', 'avi', 'wma', 'mov', 'vob'),
			exe    = In('exe', 'run'),
			arch    = In('gz', 'bzip', 'tar', '7z', 'rar', 'zip'),
			web    = In('html', 'htm'),
			sh     = In('bat', 'sh');
		if (images[ext]) {
			r.t = L('Image') + ' ' + ext.toUpperCase();
			// r.i = path;
			r.c = 'cmImage';
		} else if (texts[ext]) {
			r.t = L('Text file');
			r.i = root +  '/i/mi/txt32.png';
			r.c = 'cmText';
		} else if (docs[ext]) {
			r.t = L('Document') + ' ' + ext.toUpperCase();
			r.i = root +  '/i/mi/document32.png';
			r.c = 'cmDocument';
		} else if (audio[ext]) {
			r.t = L('Audio') + ' ' + ext.toUpperCase();
			r.i = root +  '/i/mi/sound32.png';
			r.c = 'cmAudio';
		} else if (video[ext]) {
			r.t = L('Video') + ' ' + ext.toUpperCase();
			r.i = root +  '/i/mi/video32.png';
			r.c = 'cmVideo';
		} else if (arch[ext]) {
			r.t = L('Archive') + ' ' + ext.toUpperCase();
			r.i = root + '/i/mi/tar32.png';
			r.c = 'cmArch';
		} else if (web[ext]) {
			r.t = L('Web page') + ' ' + ext.toUpperCase();
			r.i = root + '/i/mi/html32.png';
			r.c = 'cmWeb';
		} else if (ext == 'desktop') {
			r.t = L('Desktop file');
			r.i = root + '/i/exec32.png';
			r.c = 'cmDesktop';
		} else if (ext == 'swf') {
			r.t = L('Shockwave Flash Application');
		} else if (ext == 'exe') {
			r.t = L("MS Windows application");
		} else if (ext == 'jar') {
			r.t = L("Executable Jar File");
		} else if (ext == 'mts') {
			r.t = L('Top Box video record');
			r.i = root + '/i/cm/vlc32.png';
		}
		if (ext == 'mov') {
			r.t = L('Quick Time video');
			r.i = root + '/i/cm/vlc32.png';
		}
		if (ext == 'zip') {
			r.i = root + '/i/mi/zip32.png';
		}
		
		if (!r.i) {
			r.i = root + '/i/mi/' + ext + '32.png';
		}
		
		
		return r;
	}
};
