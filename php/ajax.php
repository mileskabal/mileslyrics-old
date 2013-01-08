<?php

require_once('../config.inc.php');
require_once('lg/common.php');
require_once('mileslyrics.class.php');

$database = array(_DB_HOST, _DB_USER, _DB_PASS, _DB_DATABASE);
$milesLyrics = new MilesLyrics($database);

$ajax = array('response'=>'error');

if(isset($_POST['action']) && $_POST['action'] != ''){
	
	$action = $_POST['action'];
	$ajax['action'] = $action;
	
	// Create Artist
	if($action == 'create_artist'){
		if(isset($_POST['name']) && $_POST['name'] != ''){
			$ajax['response'] = 'ok';
			$ajax['data'] = $milesLyrics->ajaxCreateArtist($_POST['name']);
		}
		else{
			$ajax['error'] = 'No artist name';
		}
	}
	// Create Album
	elseif($action == 'create_album'){
		if(isset($_POST['name']) && $_POST['name'] != ''){
			$ajax['response'] = 'ok';
			$ajax['data'] = 'WESH MORRAY';
		}
		else{
			$ajax['error'] = 'No artist album';
		}
	}
	else{
		$ajax['error'] = 'No action ['.$action.']';
	}
}
else{
	$ajax['error'] = 'No action';
}


$ajax = json_encode($ajax);
echo $ajax;

?>
