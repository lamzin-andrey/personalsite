/**
 * @depends jQuery
 */
class TimeReport {
    /**
     * 
     * @param {Funciton} fGetInput функция, получающая данные для обработки
     * @param {Funciton} fShowOut функция, рендерящая результат
     * @param {Boolean} bSetStringForExcel = false
     */
    constructor(fGetInput, fShowOut, bSetStringForExcel = false) {
        this.fGetInput = fGetInput;
        this.fShowOut = fShowOut;
        this.sResult = '';
        this.bSetStringForExcel = bSetStringForExcel;
    }
    /**
     * @return String
    */
    calculate() {
        //$('#in').val()
		var a = this.fGetInput().split("\n"), s, i, j, buf, acc = {}, words = [], ch, re;
		for (i = 0; i < a.length; i++) {
			re = new RegExp("\\b", "gi");
			words = a[i].split(re);
			buf = [];
			for (j = 0; j < words.length; j++) {
				s = $.trim(words[j]);
				if (s && s != '-') {
					buf.push(s);
				}
			}
			words =  buf;
			this.addResult(words, acc);
		}
		var h, m, dHLow, dHHigh, hTotal = 0, mTotal = 0;
		words = [];
		for (i in acc) {
            buf = acc[i];
            mTotal += acc[i];
			h = Math.floor(buf / 60);
			m = buf - h * 60;
			a = {s: '<b>' + h + '</b> ч. <b>' + m + '</b> м. ', src:buf};
			a.hoursCeil = 'max <b>' + h + ',' + Math.ceil(m / 6) + '</b> ч. ';
			a.hoursFloor = 'min <b>' + h + ',' + Math.floor(m / 6) + '</b> ч. ';
			words.push('<p>' + i + '\t' + a.s + '\t' + a.hoursCeil + '\t' + a.hoursFloor + '</p>');
			acc[i] = a;
        }
        
        buf = mTotal;
        hTotal = Math.floor(mTotal / 60);
		mTotal = mTotal - hTotal * 60;
		a = {s: '<b>' + hTotal + '</b> ч. <b>' + mTotal + '</b> м.', src:buf};
		a.hoursCeil = 'max <b>' + hTotal + ',' + Math.ceil(mTotal / 6) + '</b> ч. ';
        a.hoursFloor = 'min <b>' + hTotal + ',' + Math.floor(mTotal / 6) + '</b> ч. ';
        words.push('<p>Общее время на все задачи:</p><p>' + a.s + '\t' + a.hoursCeil + '\t' + a.hoursFloor + '</p>');

		if (this.bSetStringForExcel) {
            buf = ['self', 'uncle', 'free', 'learn', 'free_learn', 'offline', 'dumml', 'family', 'journal'];
            s = new Date();
            
            var sM, sD;
            sM = s.getMonth() + 1;
            sM = sM < 10 ? '0' + sM : sM;
            sD = s.getDate();
            sD = sD < 10 ? '0' + sD : sD;
            re = [ (s.getDate() + '.' + sM + '.' + s.getFullYear()) ];
            for (i = 0; i < buf.length; i++) {
                if (String(acc[ buf[i] ]) != 'undefined') {
                    re.push(acc[ buf[i] ].hoursCeil);
                } else {
                    re.push('0');
                }
            }
            words.push(' ');
            words.push('date\t\t' + buf.join('\t'));
            words.push( re.join('\t') );
        }

		this.sResult = words.join('\n');
        this.addHttpToTrello();
        this.fShowOut(this.sResult);
        return this.sResult;
	}
	
	addHttpToTrello() {
		var s = this.sResult;
		s = s.replace(/\strello\.com/mig, ' https://trello.com');
		this.sResult = s;
	}
	
	addResult(aWords, oR) {
		var i, toTime, fromTime, name, srcName;
		if (aWords.length > 4) {
			fromTime = this.rawToMin(aWords[0], aWords[1]);
			toTime   = this.rawToMin(aWords[2], aWords[3]);
			if (toTime && fromTime && toTime - fromTime > 0) {
				name = aWords[4];
				if (name) {
					srcName = name;
					name = name.toLowerCase();
					oR[name] = oR[name] ? oR[name] : 0;
					oR[name] += toTime - fromTime;
				}
			}
		}
	}
	
	rawToMin(sHour, sMin) {
		sHour = parseInt(sHour, 10);
		sMin   = parseInt(sMin, 10);
		if (!isNaN(sHour) && !isNaN(sMin)) {
			sHour *= 60;
			sMin += sHour;
			return sMin;
		}
		return 0;
	}
}
export default TimeReport;