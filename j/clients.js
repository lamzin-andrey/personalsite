window.Clients = {
	/**
	 * @property {jQuery Submit input} iSubmit ������������ ������
	*/
	iSubmit:$('#subm'),

	/**
	 * @property {jQuery Text input} iEmail ����� ����� email
	*/
	iEmail:$('#email'),

	/**
	 * @property {jQuery Password input} iPassword ����� ����� ������
	*/
	iPassword:$('#password'),
	
	init:function() {
		var o = this;
		o.iSubmit.click(function(evt){evt.preventDefault(); return o.onSubmit(evt)});
	},
	/**
	 * @description
	*/
	onSubmit:function(evt) {
		evt.preventDefault();
		var o = this;
		console.log('I call');
		if (!o.validate()) {
			return false;	
		}
		o.iSubmit.prop('disabled', true);
		//_post({email:PayDisplay.iEmail.val(), sum:o.iSum.val(), comment:o.iComment1.val()}, function(d){o.onDataSuccess(d);}, '/q/app/pay.php', function(d,b,c){o.onDataFail(d,b,c);});
		setTimeout(function(){
			o.iSubmit.prop('disabled', false);
			o.iPassword.val('');
			B4Lib.setErrorById(o.iEmail, '������������ � ����� ������� � ������� �� ������');
		}, 500);
		return false;
	},
	/**
	 * @description ���������, ������ �� �������� email  � �������� �� ������
	 * @return {Boolean} bSuccess
	*/
	validate:function(){
		var o = this, bSuccess = true;
		if (!o.iEmail.val().trim()) {
			B4Lib.setErrorById(o.iEmail, '���� "Email" ����������� ��� ����������');
			bSuccess = false;
		}
		if (!o.iPassword.val().trim()) {
			B4Lib.setErrorById(o.iPassword, '���� "������" ����������� ��� ����������');
			bSuccess = false;
		}
		if (!Validator.isValidEmail(o.iEmail.val())) {
			B4Lib.setErrorById(o.iEmail, '����� ������������ email');
			bSuccess = false;
		}
		if (bSuccess) {
			B4Lib.setSuccessInputViewById(o.iEmail);
			B4Lib.setSuccessInputViewById(o.iPassword);
		}
		return bSuccess;
	},
	/**
	 * @description
	*/
	onDataSuccess:function(data) {
		var o = this;
		if (data.appErrors) {
			return o.onDataFail(data);
		}
		o.iRec.val(data.num);
		//�������� � ����������� id ����������
		o.iComment1.val( o.iComment1.val() + ' ;' + data.itr);
		o.iComment2.val( o.iComment2.val() + ' ;' + data.itr);
		$('#paytype').val( $('#bs').prop('checked') ? $('#bs').val() : $('#ps').val());
		o.iForm.submit();
	},
	/**
	 * @description
	*/
	onDataFail:function(a, b, c) {
		var o = this, pd = PayDisplay, t;
		if (a.appErrorFields) {
			t = a.appErrorFields;
			if (t.email) {
				pd.iEmail.val(t.email);
				pd.validateEmail();
			}
			if (t.sum) {
				pd.iSum.val(t.sum);
				pd.validateSum();
			}
		} else if (data.oneError) {
			alert(data.oneError);
		} else {
			alert('���-�� ����� �� ���, �������� �������� � ���������� ��� ���');
		}
	}
}
W = window;
W.root = '';
function getToken(){return 'q';}
