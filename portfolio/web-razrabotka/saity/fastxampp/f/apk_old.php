<?php
//TODO все apk переместить в пaпку old
//TODO drop all log files
file_put_contents(dirname(__FILE__)."/u/out/1000/srvlog.txt", print_r($_FILES, 1) . print_r($_POST, 1) );
move_uploaded_file($_FILES["file"]["tmp_name"], dirname(__FILE__)."/u/out/1000/" . $_FILES["file"]["name"]);
