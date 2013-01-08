<?php

define("_SITE_NAME","milesLyrics");

if(isset($_GET['lg']) && $_GET['lg'] != ''){
	switch($_GET['lg']){
		case 'eng':
			include('eng.php');
		break;
		case 'fr':
			include('fr.php');
		break;
		default:
			include(_CONFIG_LANG.'.php');
		break;
	}	
}
else{
	include(_CONFIG_LANG.'.php');
}

?>
