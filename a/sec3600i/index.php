<!DOCTYPE html>
<html xml:lang="ru" lang="ru" <?php if ( strpos($_SERVER['HTTP_USER_AGENT'], 'Android 2.3') !== false) echo ' manifest="example.appcache" '; ?> ><!-- manifest="example.appcache" -->
<head>
	<script src="/a/sec3600i/j/registersw.js"></script>
	<meta charset="windows-1251" >
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no">
	<!-- meta http-equiv="Cache-Control" content="max-age=3600, must-revalidate"  /-->
	<meta http-equiv="Cache-Control" content="max-age=3600"  />
	<meta http-equiv="Cache-Control" content="public">
    <!-- meta http-equiv="Expires" content="Mon, 16 Nov 2020 00:00:01 GMT"  /-->
	<title>3600i </title>
	<?php if ( strpos($_SERVER['HTTP_USER_AGENT'], 'Android 2.3') === false) echo ' <link rel="manifest" href="/a/sec3600i/manifest.json"> '; ?>
	<link rel="stylesheet" type="text/css" href="./s/b.css?1">
	<link href="./i<?php if ( strpos($_SERVER['HTTP_USER_AGENT'], 'Android 2.3') !== false) echo '/16/'; else echo '/'; ?>favicon.ico" rel="shortcut icon" type="image/x-icon" >
</head>
<body>
    <b  class="scr" id="mainscr">
      <b id="hWork">
         <b id="bigNum">
          <i id="h">00</i>:<i id="hM">00</i>:<i id="hS">00</i>:<i id="hMs">00</i>
         </b><!-- end big numbers -->
        <b id="hPoints"></b>
       </b>
        <b class="buttons-wrap">
          <b  id="buttons">
		    <b id="reset">Старт</b>
		    <b id="menu">
				<b id="bq"  class="bb">
				  <b id="ba">
					   <b id="bat"> <b id="bac"> <b id="bart">
					  &nbsp;
					  </b></b></b>
				  </b>
				</b>
            </b>
            <b id="stop">Выход</b>
            <b id="cl"></b>
         </b>
       </b>
    </b>
    <b id="audioplacer">
		<audio id="Aclick">
			<source src="m/0.mp3" type="audio/mp3">
		</audio>
	</b>
	<script src="./j/a.js"></script>
	<script src="./j/jquery.min.js"></script>
	<script src="./j/cache_client.js"></script>
	<script src="./j/cache_conf.js"></script>
	<script>
		try{
			console.log('Run CC@');
			new CacheClient();
		} catch(e){console.log('Here no here');}
	</script>
	
</body>
</html>
