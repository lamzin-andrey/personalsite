<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="./ip.css">
		<meta charset="WINDOWS-1251">
		<meta name="viewport" content="width=device-width,scale=1.0">
		<title>������ ���� ������ �� ip</title>
	</head>
	<body>
		<div class="mx-auto mw-320px border-blue h100">
			<div class="title">��� ip:</div>
			<div class="val"><?=($_SERVER['REMOTE_ADDR'] ?? '�� ��������')?></div>
			<div class="title">������:</div>
			<div class="val"><?=utils_cp1251($country)?></div>
			<div class="title">�����:</div>
			<div class="val"><?=utils_cp1251($city)?></div>
			<div class="mt-3 text-center">
				<a class="button" href="/blog/">� ����</a>
			</div>
		</div>
	</body>
</html>
