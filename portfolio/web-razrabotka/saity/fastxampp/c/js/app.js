var abcIsInitalize=0;var Map=[];
function initAbc(a,f){
	var n;n="012 3456789\n\r?!,.абвгдеёжзийклмнопрстуфхцчшщъыьэюяЙЦУКЕНГФЫВАПРОЛДЖЭЯЧСМИТЬБЮШЩЗХЪasdfghjklzxcvbnm-+qwertyuiopZXCVBNMASDFGHJKLQWERTYUIOP():/`=*[];'\t~@#$%^&_{}|\"<>№";
	if(abcIsInitalize){
		f.abc=n;
		f.m=Map;
		return;
	}
	var g,e,c=[],l,h,d;
	if(!validPassword(a,n)){
		Error("Invalid password");
	}
	for(g=0;g<n.length;g++){
		c[g]=g}e=0;
		for(g=0;g<n.length;g++){
			if(e>a.length-1){
				e=0;
			}
			ch=a.charAt(e);
			di=n.indexOf(ch);
			dI=c[di];I=c[g];
			l=I+dI;
			l=l>n.length-1?l-n.length:l;
			d=arrIndexOf(c,l);
			c[g]=l;
			c[d]=I;
			e++;
		}
		f.abc=n;
		Map=c;
		f.m=c;
		abcIsInitalize=1;
}
function arrIndexOf(a,b){
	for(var c=0;c<a.length;c++){
		if(a[c]==b){return c;}
	}
	return -1;
}
function validPassword(c,b){
	for(var a=0;a<sz(c);a++){
		if(!~b.indexOf(c.charAt(a))){
			return false;
		}
	}
	return true;
}
function decrypt(t,c){t=t.split(";");var l,h,f=0,o,e,a="",d,k,n,q={},g;initAbc(c,q);for(h=0;h<t.length;h++){f=f<c.length?f:0;e=String(charCode(c,f,q));e=e.replace("n","");o=t[h];if(o.charAt(0)=="n"){g=1;o=o.replace("n","")}else{g=0}o=+o;e=+e;d=o-e;k=getLimit(o,q,g);n=q.b;if(d<n){d=k-Math.abs(d-n);o=d}else{o=d}o=fromCharCode(o,g,q);a+=String(o);f++}return a}
function crypt(t,c){var l,h,f=0,o,e,a=[],d,q={},k,n,g;initAbc(c,q);for(h=0;h<t.length;h++){o=String(charCode(t,h,q));if(o.charAt(0)=="n"){g=1;o=o.replace("n","")}else{g=0}o=+o;f=f<c.length?f:0;e=String(charCode(c,f,q));e=e.replace("n","");e=+e;d=o+e;getLimit(d,q,g);k=q.L;n=q.b;if(d>k){o=n+d-k}else{o=d}if(g){o="n"+o}a.push(o);f++}return a.join(";")}
function fromCharCode(e,a,b){if(a){return String.fromCharCode(e)}var d=arrIndexOf(b.m,e-1);return b.abc.charAt(d)}
function charCode(h,f,d){var e,c,a,g=d.abc;e=g.indexOf(h.charAt(f));if(~e){e=d.m[e];return(e+1)}return"n"+h.charCodeAt(f)}
function getLimit(a,c,b){if(b){return getLimitN(a,c)}c.L=c.abc.length;c.b=1;return c.L}
function getLimitN(d,f){var e=[9,125,1025,1103],c,a;if(d>e[0]&&d<e[1]){c=e[1];a=e[0]}else{if(d>=e[2]&&d<=e[3]){c=e[3];a=e[2]}else{c=1000000;a=0}}f.L=c;f.b=a;return c}
window.onload=init;
function init(){
	window.W=window;
	window.D=document;
	W.root="/portfolio/web-razrabotka/saity/fastxampp/c";
	W.app={L:"лопата"};
	if(W.uid){
		hideScreens();
		show(hRepeatPwdScreen);
	}
	repeatPwdBtn.onclick=onRP;
	mainForm.onsubmit=onMainSubmit;
	nextPostBtnBtm.onclick=nextPostBtn.onclick=onNextPost;
	newPostBtn.onclick=onNewPost;
	editPostBtnBtm.onclick=editPostBtn.onclick=onEditPost;
	registerNowBtn.onclick=onRegisterNowClick;
	sendRegisterDataBtn.onclick=onSendRegisterDataClick;
	signInBtn.onclick=onSignInClick;
	textCommentBtn.onclick=onSendCommentClick;
	history.pushState(null,null,"/portfolio/web-razrabotka/saity/fastxampp/c/");
	W.onpopstate=onBackButton;
}
function onNewPost(){
	iCurrentPostId.value=0;
	hideScreens();
	iTitle.value="";
	iBody.value="";
	show(hEditPostScreen);
}
function onBackButton(a){
	a.preventDefault;hideScreens();
	show(hNovelScreen);
	success("Ваш комментарий появитсья после проверки модератором");
	W.scrollTo(0,0);
}
function onSendCommentClick(){
	hideScreens();
	show(hNovelScreen);
	setTimeout(function(){
		success("Ваш комментарий появится после проверки модератором");
		W.scrollTo(0,0);
	},3*1000);
	return false;
}
function onSignInClick(){
	var a={iLlogin:1,iLpwd:1};
	_map(a,1);
	W.app.pwd=a.iLpwd;
	_post(a,onSuccessLogin,"/signin.php");
	return false;
}
function onSuccessLogin(a){
	if(!a.error){
		W.password=W.app.pwd;
		if(a.id>0){
			postDataDoneHandler(a);
		}else{
			hideScreens();
			show(hEditPostScreen);
			success(a.message);
		}
	}else{
		W.scrollTo(0,0);
		error(a.error);
	}
}
function onSendRegisterDataClick(){
	var a={iLogin:1,iPwd:1,iPwdConfirm:1,iAgree:1};
	_map(a,1);
	app.pwd=a.iPwd;
	_post(a,onSendRegisterData,"/signup.php");
	return false;
}
function onSendRegisterData(a){
	if(!a.error){
		success(a.message);
		setTimeout(function(){
			hideScreens();
			iCurrentPostId.value=0;
			W.password=app.pwd;
			success("Создайте свою первую запись");
			show(hEditPostScreen);
		},5*1000);
	}else{
		error(a.error);
	}
}
function onRegisterNowClick(){hideScreens();show(hSignUpScreen);W.scrollTo(0,0);return false}
function onEditPost(){hideScreens();show(hEditPostScreen)}
function onNextPost(){
	var a=iCurrentPostId.value;
	a=a?a:0;
	_get(postDataDoneHandler,"/nextpost.php?id="+a);
}
function onMainSubmit(){
	var b={iBody:1,iTitle:1,iCurrentPostId:1};
	_map(b,1);
	try{
		b.iBody=crypt(W.app.L+b.iBody,W.password)
	}catch(a){
		if(a.message=="Invalid password"){
			error("Пароль содержит недопустимые символы");
			throw a;
		}else{
			throw a;
		}
	}
	try{
		b.iTitle=crypt(b.iTitle,W.password)
	}catch(a){
		if(a.message=="Invalid password"){
			error("Пароль содержит недопустимые символы");
			throw a;
		}else{
		throw a;
		}
	}
	_post(b,onSaveEditFormDataUserAction,"/post.php");
	return false;
}
function onSaveEditFormDataUserAction(a){
	postDataDoneHandler(a);
}
function onRP(){W.password=iRepeatPwd.value;_get(onGetLastPost,"/lastpost.php");return false}
function onGetLastPost(a){
	postDataDoneHandler(a);
}
function postDataDoneHandler(b){
	if(!b.error){
		var a=setCurrentPostData(b.id,b.text,b.title);
		if(a){
			hideScreens();
			show(hViewPostScreen);
			if(b.message){
				success(b.message);
			}
		}else{
			Map=[];
			abcIsInitalize=0;
			error("Неверный пароль!");
		}
	}else{
		if(b.state && b.state=="showform"){
			hideScreens();
			show(hEditPostScreen);
		} else {
			error(b.error);
		}
	}
}
function error(a){
	hide(hMessage);
	if(a.length>0){
		hErrorText.innerText=a;
		show(hError);
	}else{
		hide(hError);
	}
}
function success(a){hide(hError);if(a.length>0){hMessageText.innerText=a;show(hMessage)}else{hide(hMessage)}}
function setCurrentPostData(f,a,d){
	var b="";
	try{
		b=decrypt(a,W.password);
	}catch(c){
		if(c.message=="Invalid password"){
			error("Пароль содержит недопустимые символы");
			throw"e";
		}else{
			throw c;
		}
	}
	if(b.indexOf(W.app.L)===0){
		iBody.value=D.getElementById("hPostTextView").innerHTML=b.replace(W.app.L,"");
		try{
			iTitle.value=D.getElementById("hPostTitleView").innerHTML=decrypt(d,W.password);
		}catch(c){
			if(c.message=="Invalid password"){
				error("Пароль содержит недопустимые символы");
				throw c;
			}
		}
		iCurrentPostId.value=f;
		D.getElementById("hPostTextView").innerHTML="<p>"+D.getElementById("hPostTextView").innerHTML.replace(/\n/mig,"</p><p>")+"</p>";
		D.getElementById("hPostTitleView").innerHTML="<p>"+D.getElementById("hPostTitleView").innerHTML.replace(/\n/mig,"</p><p>")+"</p>";
		return true;
	}
	return false;
}
function hideScreens(){var a=D.getElementsByClassName("screenWrapper"),b,c;for(b=0;b<sz(a);b++){c=$$(a[b],"div")[0];if(c.tagName=="DIV"){hide(c)}}}
function _map(d,c){var f,e,b;for(b in d){f=$(b);e=f;if(e){if(e.tagName=="INPUT"||e.tagName=="TEXTAREA"){if(!c){e.value=d[b]}else{if(e.type=="checkbox"){d[b]=e.checked}else{d[b]=e.value}}}else{if(!c){if(e.type=="checkbox"){var a=d[b]=="false"?false:d[b];a=a?true:false;e.checked=a}else{e.innerText=d[b]}}else{d[b]=e.innerText}}}}}
function _get(b,a,c){_restreq("get",{},b,a,c)}
function _delete(b,a,c){_restreq("post",{},b,a,c)}
function _post(e,f,a,g){var d=document.getElementsByTagName("meta"),c,b;for(c=0;c<d.length;c++){if(attr(d[c],"name")=="app"){b=attr(d[c],"content");break}}if(b){e._token=b;_restreq("post",e,f,a,g)}}
function _patch(b,c,a,d){_restreq("patch",b,c,a,d)}
function _put(b,c,a,d){_restreq("put",b,c,a,d)}
function _restreq(e,b,c,a,d){if(!a){a=window.location.href}else{a=W.root+a}if(!d){d=defaultFail}switch(e){case"put":case"patch":case"delete":break}pureAjax(a,b,c,d,e)}
function pureAjax(c,d,g,b,a){var j=new XMLHttpRequest();var f=[];for(var e in d){f.push(e+"="+encodeURIComponent(d[e]))}var h=f.join("&");j.open(a,c);j.setRequestHeader("Content-Type","application/x-www-form-urlencoded");j.onreadystatechange=function(){if(j.readyState==4){var k={};if(j.status==200){try{var i=JSON.parse(String(j.responseText));g(i,j);return}catch(l){console.log(l);k.state=1;k.info="Fail parse JSON"}}else{k.state=1}if(k.state){b(j.status,j.responseText,k.info,j)}}};j.send(h)}
function defaultFail(a){W.requestSended=0;error("Не удалось обработать запрос, попробуйте снова")}
function storage(b,c){var a=window.localStorage;if(a){if(c===null){a.removeItem(b)}if(!(c instanceof String)){c=JSON.stringify(c)}if(!c){c=a.getItem(b);if(c){try{c=JSON.parse(c)}catch(d){}}}else{a.setItem(b,c)}}return c};
