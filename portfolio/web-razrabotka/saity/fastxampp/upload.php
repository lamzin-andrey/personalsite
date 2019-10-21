<?php
	//echo"::<pre>"; print_r(file_get_contents('php://input'));echo"</pre>";
	if (isset($_POST['tout'])) {
		//file_put_contents(__DIR__ . '/uploadlog.log', ($_POST['tout'] . "\n"), FILE_APPEND);
		$e = $_POST['tout'];
		$data = base64_decode($e);
	} else {
		$data = file_get_contents('php://input');
		file_put_contents(__DIR__ . '/uploadlog.log', ($data . "\n"), FILE_APPEND);
	}
	if ($data) {
		
		
		$filename = dirname(__FILE__) . '/uploads/' . date('YmdHis') . '.tmp';
		file_put_contents($filename, $data);
		$sz = getImageSize($filename);
		$mime = (isset($sz['mime']) ? $sz['mime'] : '');
		$destFile = '';
		if ($mime == 'image/jpeg') {
			$destFile = str_replace('.tmp', '.jpg', $filename);
		}
		if ($mime == 'image/png') {
			$destFile = str_replace('.tmp', '.png', $filename);
		}
		if ($mime == 'image/gif') {
			$destFile = str_replace('.tmp', '.gif', $filename);
		}
		if ($destFile) {
			rename($filename, $destFile);
		}
	} else if (count($_FILES)){
		file_put_contents(__DIR__ . '/uploadlog.log', (print_r($_POST, 1) . "\n"), FILE_APPEND); 
		move_uploaded_file($_FILES['file']['tmp_name'], dirname(__FILE__) . '/uploads/' . $_FILES['file']['name']);
	}
	
	
