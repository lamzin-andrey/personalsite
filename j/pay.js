window.Payform = {
	/**
	 * @property {jQuery Submit input} iSubmit Отображаемая кнопка
	*/
	iSubmit:$('#subm'),
	
	/**
	 * @property {jQuery HtmlInput} iComment1 Текст комментария
	*/
	iComment1:$('#comment'),
	
	/**
	 * @property {jQuery HtmlInput} iComment2 Текст комментария 2
	*/
	iComment2:$('#comment2'),
	
	/**
	 * @property {jQuery HtmlInput} iRec Получатель денег
	*/
	iRec: $('#rec'),
	
	/**
	 * @property {jQuery HtmlInput} iLabel
	*/
	iLabel: $('#label'),
	
	/**
	 * @property {jQuery HtmlInput} iSum
	*/
	iSum: $('#sumForPay'),
	
	/**
	 * @property {jQuery HtmlForm} iForm
	*/
	iForm: $('#yaform'),
	
	
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
		_post({email:PayDisplay.iEmail.val(), sum:o.iSum.val(), comment:o.iComment1.val()}, function(d){o.onDataSuccess(d);}, '/q/app/pay.php', function(d,b,c){o.onDataFail(d,b,c);});
		return false;
	},
	/**
	 * @description
	*/
	onDataSuccess:function(data) {
		var o = this;
		if (data.appErrors) {
			return o.onDataFail(data);
		}
		//сравнить номер в iRec с пришедшим
		if (data.num !== '410014426382768') {
			alert('Неверный номер кошелька получателя!');
			return;
		}
		o.iRec.val(data.num);
		//Добавить в комментарий id транзакции
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
			alert('Что-то пошло не так, обновите страницу и попробуйте ещё раз');
		}
	}
}
W = window;
W.root = '';
function getToken(){return 'q';}
