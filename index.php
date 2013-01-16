<?php

require_once('config.inc.php');
require_once('php/lg/common.php');
require_once('php/mileslyrics.class.php');

$database = array(_DB_HOST, _DB_USER, _DB_PASS, _DB_DATABASE);
$milesLyrics = new MilesLyrics($database);

$content = '';
$content .= '<h1>'._SITE_NAME.'</h1>';
$content .= '<h3>'._WELCOME.'</h3>';
?>
<!DOCTYPE HTML>
<html>
<head>
	<meta charset="UTF-8">
	<title>milesLyrics</title>
	<script type="text/javascript" src="js/jquery.1.7.min.js"></script>
	<script type="text/javascript" src="js/jwerty.js"></script>
	<script type="text/javascript" src="js/mileslyrics.js"></script>
	<script type="text/javascript" src="js/ready.js"></script>
	<script type="text/javascript"><?php echo $milesLyrics->javascript; ?></script>
</head>
<body>
	<pre><?php echo $content; ?></pre>

	<div id="admin" style="display:block;">
		<input type="button" id="close_admin" value="X" />
		<!--
		<div id="createArtist">
			<?php echo $milesLyrics->templateCreateArtist(); ?>
		</div>

		<div id="createAlbum">
			<?php echo $milesLyrics->templateCreateAlbum(); ?>
		</div>
		-->
		
		<div id="createTracks">
			<?php echo $milesLyrics->templateCreateTracks(); ?>
		</div>
	</div>
	
</body>
</html>

