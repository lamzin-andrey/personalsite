window.PayDisplay = {
	/**
	 * @property {jQuery HtmlInput} iEmail ���� ����� email
	*/
	iEmail : $('#email'),
	
	/**
	 * @property {jQuery HtmlInput} iEmail ���� ����� �����
	*/
	iSum : $('#sum'),
	
	/**
	 * @property {jQuery HtmlInput} iSumval ������� ���� �� ��������� �����
	*/
	iSumval : $('#sumVal'),
	
	/**
	 * @property {jQuery HtmlInput} iHour �������� ����� ������
	*/
	iHour : $('#hour'),
	
	/**
	 * @property {jQuery HtmlInput} iHourDisplay ������������ �������� ����� ������
	*/
	iHourDisplay : $('#hourDisplay'),
	
	/**
	 * @property {jQuery HtmlInput} iMin �������� ����� ������
	*/
	iMin : $('#min'),
	
	/**
	 * @property {jQuery HtmlInput} iMinDisplay ������������ �������� ����� ������
	*/
	iMinDisplay : $('#minDisplay'),
	
	/**
	 * @description
	*/
	init:function() {
		var o = this;
		o.iSum.on('input', function(evt){ o.onChangeSumValue(evt); })
		o.iSum.on('keyup', function(evt){ o.onChangeSumValue(evt); })
		o.iEmail.on('input', function(evt){ o.onChangeEmailValue(evt); })
		o.iEmail.on('keyup', function(evt){ o.onChangeEmailValue(evt); })
		if (o.iSum.val().trim()) {
			o.validateSum();
		}
		if (o.iEmail.val().trim()) {
			o.validateEmail();
		}
	},
	/**
	 * @description ��������� ����� � ���� email
	*/
	onChangeEmailValue:function(evt){
		this.validateEmail();
	},
	/**
	 * @description ��������� ����� � ���� email
	*/
	validateEmail:function() {
		var o = this, s = o.iEmail.val().trim();
		if (!Validator.isValidEmail(s)) {//TODO
			B4Lib.setErrorById(o.iEmail.attr('id'), '������������ email');
			o._bIsValidEmail = 0;
		} else {
			B4Lib.setSuccessInputViewById(o.iEmail.attr('id'));
			o._bIsValidEmail = 1;
		}
	},
	/**
	 * TODO ���������� ����������� ����� ��� ������ �����
	 * @description ��������� ����� � ���� ����� 
	*/
	onChangeSumValue:function(evt){
		var o = this, nSum, success = 1, s;
		if (o.isChangeSumProcess) {
			return;
		}
		o.isChangeSumProcess = true;
		o.validateSum();
		setTimeout(function(){
			o.isChangeSumProcess = false;
			if (o._bIsValidSum) {
				o.setSuccessSumField(o._nSum);
			}
		}, 100);
	},
	/**
	 * @description ��������� ��������� �����. 
	 * ������������� _nSum, _bIsValidSum
	*/
	validateSum:function(){
		var o = this, s, nSum, success = 1;
		o._bIsValidSum = 1;
		s = o.iSum.val();
		o.iSum.val( TextFormat.money(s) );
		s = TextFormat.nums(s);
		o._nSum = nSum = parseInt(s, 10);
		if (isNaN(nSum) || !nSum) {
			o.setSumError();
			success = 0;
		}
		if (nSum > o.iSum.attr('data-max') || nSum < o.iSum.attr('data-min')) {
			o.setSumError();
			o._bIsValidSum = success = 0;
		}
		if (s.trim().charAt(0) == '0') {
			o.setSumError();
			o._bIsValidSum = success = 0;
		}
		if (o._bIsValidSum) {
			o.setSuccessSumField(nSum);
		}
	},
	/**
	 * @description ���������� ������������ �������� ����� � ����� � ��������� ������� ���� �����
	*/
	setSuccessSumField:function(nSum){
		var o = this;
		B4Lib.setSuccessInputViewById(o.iSum.attr('id'));
		o.setDisplayValues(nSum);
		o.iSumval.val(nSum);
		Payform.iSum.val(nSum);
	},
	/**
	 * @description ���������� ����������� ����� � ����� � ���� �����
	*/
	setDisplayValues:function(nSum) {
		var o = this,
			p = Payform,
			hourPay = 400,
			minPay = 20 / 3,
			h = Math.floor(nSum / hourPay),
			sumTail = nSum - h * hourPay,	//������� ����� ����� ����������� �����
			m = Math.floor(sumTail / (minPay)),
			sTplHMComment = '������ �����: ���������������� 1 ���, {N} ��., ���������������� 3 ������, {M} ��.',
			sTplHComment = '������ ������: ���������������� 1 ���, {N} ��',
			sTplMComment = '������ ������: ���������������� 3 ������, {M} ��',
			s = '';
			
		h = isNaN(h) ? 0 : h;
		m = isNaN(m) ? 0 : m;
		
		o.iHourDisplay.text(h + ' ' + TextFormat.pluralize(h, '���', '����', '�����'));
		o.iMinDisplay.text(m + ' ' + TextFormat.pluralize(m, '������', '������', '�����'));
		o.iHour.val(h);
		o.iMin.val(m);
		
		//��������� �������� ����� yamoney
		m = Math.ceil(m / 3);
		if (h && m) {
			s = sTplHMComment;
		} else if (h) {
			s = sTplHComment;
		} else if (m) {
			s = sTplMComment;
		}
		s = s.replace('{N}', h);
		s = s.replace('{M}', m);
		p.iComment1.val(s);
		p.iComment2.val(s);
	},
	/**
	 * @description ��������� ����� � ���� ����� 
	*/
	setSumError:function(evt){
		var o = PayDisplay;
		B4Lib.setErrorById(o.iSum.attr('id'), '������� �������� �� 20 �� 60 000');
		o.setDisplayValues(0);
	}
	
}

