<?php

define("_SITE_NAME","milesLyrics");

if(isset($_REQUEST['lg']) && $_REQUEST['lg'] != ''){
	$lg = $_REQUEST['lg'];
	switch($_REQUEST['lg']){
		case 'eng':
			include('eng.php');
		break;
		case 'fr':
			include('fr.php');
		break;
		default:
			include(_CONFIG_LANG.'.php');
			$lg = _CONFIG_LANG;
		break;
	}	
}
else{
	$lg = _CONFIG_LANG;
	include(_CONFIG_LANG.'.php');
}

?>
