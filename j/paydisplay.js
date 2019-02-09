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
	},
	/**
	 * @description ��������� ����� � ���� email
	*/
	onChangeEmailValue:function(evt){
		
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
		s = o.iSum.val();
		o.iSum.val( TextFormat.money(s) );
		s = TextFormat.nums(s);
		nSum = parseInt(s, 10);
		console.log(nSum);
		if (isNaN(nSum) || !nSum) {
			o.setEmailError();
			success = 0;
		}
		if (nSum > o.iSum.attr('data-max') || nSum < o.iSum.attr('data-min')) {
			o.setEmailError();
			success = 0;
		}
		if (s.trim().charAt(0) == '0') {
			o.setEmailError();
			success = 0;
		}
		if (success) {
			B4Lib.setSuccessInputViewById(o.iSum.attr('id'));
			o.setDisplayValues(nSum);
		}
		setTimeout(function(){
			o.isChangeSumProcess = false;
			if (success) {
				B4Lib.setSuccessInputViewById(o.iSum.attr('id'));
				o.setDisplayValues(nSum);
			}
		}, 200);
	},
	/**
	 * @description ���������� ����������� ����� � ����� � ���� �����
	*/
	setDisplayValues:function(nSum) {
		var o = this,
			hourPay = 400,
			minPay = 20 / 3,
			h = Math.floor(nSum / hourPay),
			sumTail = nSum - h * hourPay,	//������� ����� ����� ����������� �����
			m = Math.floor(sumTail / (minPay));
		o.iHourDisplay.text(h + ' ' + TextFormat.pluralize(h, '���', '����', '�����'));
		o.iMinDisplay.text(m + ' ' + TextFormat.pluralize(m, '������', '������', '�����'));
	},
	/**
	 * @description ��������� ����� � ���� ����� 
	*/
	setEmailError:function(evt){
		B4Lib.setErrorById(PayDisplay.iSum.attr('id'), '������� �������� �� 20 �� 60 000');
	}
	
}
PayDisplay.init();


