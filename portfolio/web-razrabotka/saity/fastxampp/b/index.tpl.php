<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=windows-1251" />
	<title>��������� ������ � ������� � ����������</title>
	<script src="/portfolio/web-razrabotka/saity/fastxampp/js/landcacherswinstaller.js"></script>
	<meta name="keywords" content="������ � �������,��� � �������, ������ ������ ����,���� � 2050 ����" />
	<link rel="stylesheet" href="./css/main.css" type="text/css">
	<meta name="viewport" content="initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, width=device-width, height=device-height,target-densitydpi=device-dpi, user-scalable=no" />
</head>
<body>
	<div class="main">
		<header><h3>��������� ������ � �������!</h3></header>
		<p>����� ���������� ������ � �������? ������ ����, ������� ��� ������ ���������. �� ������ ��������� ������ ������ ����, ������ ���� �����������, 
		� ������ ��������� ��� ������������, ������� ������ ��������� ��� �� ���� �����.</p>
		<form method="POST" action="">
			<? if(trim($error)):?>
				<div class="bg-danger rose">
					<p><?=$error?></p>
				</div>
			<? endif?>
			<? if(trim($message)):?>
				<div class="bg-success success light-green">
					<p><?=$message?></p>
				</div>
			<? endif?>
			<div>
				<label for="subject"> ���� ������</label>
			</div>
			<div>
				<input type="text" value="<?=$subject?>" name="subject" id="subject">
			</div>
			<div>
				<label for="email"> Email ����������</label>
			</div>
			<div>
				<input type="email" value="<?=$email?>" name="email" id="email">
			</div>
			<div>
				<label> <input type="checkbox" value="1" <?=$toAllChecked?> name="toAll"> ��� ��������� ��� ����!</label>
			</div>
			<div>
				<label for="date">
					����� ���������
				</label>
			</div>
			<div>
				<input type="date" value="<?=$date?>" name="date" id="date" >
			</div>
			<div>
				<label>
					����� ���������
					<textarea rows="10" style="width:99%; resize:none" name="body"><?=$body?></textarea>
				</label>
			</div>
			<div>
				<label>������� ������� � �����������</label>
			</div>
			<div>
				<img id="cap" width="200" src="img/random/index.php">
			</div>
			<div>
				<a href="#" onclick="document.getElementById('cap').setAttribute('src', 'img/random/index.php); return false;">�������� �������</a>
			</div>
			<div>
				<input type="text" name="code" autocomplete="off"> <input type="submit" value="���������">
			</div>
			
		</form>
	</div>
	<script>window.rid = '00';</script><?php /* //TODO! */?>
	<script src="/p/sbadmin2/vendor/jquery/jquery.min.js"></script>
    <script src="/j/sources/landlib/net/rest.js"></script>
    <script src="/portfolio/web-razrabotka/saity/fastxampp/js/land_cache_client.js"></script>
    <script src="/portfolio/web-razrabotka/saity/fastxampp/b/js/cacheclient.js"></script>TODO
    <script>
        /*new FxCacheClient(); TODO
        var id = parseInt(window.rid, 10), o = {};
        if (isNaN(id)) {
            console.log('id is NaN');
        }
        o.id = id;
        o.url = location.href.split('?')[0];
        o.type = o.url.indexOf('/portfolio/') == -1 ? 2 : 1;
        Rest._token = 'open';
        Rest._post(o, function(){}, '/p/stat/c.jn/', function(){});*/
    </script>
</body>

</html>
