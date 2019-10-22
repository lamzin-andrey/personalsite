<div class="fmain">
	<div class="fborder">&nbsp;</div>
	<div class="fcnt">
		<table><tr>
		<td>
			<!--LiveInternet counter--><script type="text/javascript">
document.write("<a href='//www.liveinternet.ru/click' "+
"target=_blank><img src='//counter.yadro.ru/hit?t44.7;r"+
escape(document.referrer)+((typeof(screen)=="undefined")?"":
";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
";"+Math.random()+
"' alt='' title='LiveInternet' "+
"border='0' width='31' height='31'><\/a>")
</script><!--/LiveInternet-->
	</td>
	<td>
	&nbsp;&nbsp;&nbsp;
		<script src="http://freesoft.ru/res/inf.php?id=734540&cp=utf8">
		</script>
	</td>
	<td>
		&nbsp;&nbsp;&nbsp;
		<script src="http://freesoft.ru/res/inf.php?id=725665&cp=utf8">
		</script>
	</td>
	<td class="more_products">
		<a href="/"><?=$sAuthorLinkText?></a>
	</td>
		</tr></table>
	</div>
</div>
<script src="/p/sbadmin2/vendor/jquery/jquery.slim.min.js"></script>
<script src="/j/sources/landlib/net/rest.js"></script>
<script>
	
	var id = parseInt(window.rid, 10), o = {};
	if (isNaN(id)) {
		console.log('id is NaN');
	}
	o.id = id;
	o.url = location.href.split('?')[0];
	o.type = o.url.indexOf('/portfolio/') == -1 ? 2 : 1;
	Rest._token = 'open';
	Rest._post(o, function(){}, '/p/stat/c.jn/', function(){});
</script>
