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
			if(isset($_POST['confirm']) && $_POST['confirm'] == '1'){
				$ajax['data'] = $milesLyrics->ajaxCreateArtist($_POST['name'],true);
			}
			else{
				$ajax['data'] = $milesLyrics->ajaxCreateArtist($_POST['name']);
			}
		}
		else{
			$ajax['error'] = 'No artist name';
		}
	}
	// Create Album Select Artist
	elseif($action == 'create_album_select_artist'){
		if(isset($_POST['id_artist']) && $_POST['id_artist'] != ''){
			$ajax['response'] = 'ok';
			$ajax['data'] = $milesLyrics->ajaxCreateAlbumSelectArtist($_POST['id_artist']);
			//~ $ajax['data'] = 'WESH '.$_POST['id_artist'];
		}
		else{
			$ajax['error'] = 'No id_artist';
		}
	}
	// Create Album
	elseif($action == 'create_album'){
		if(isset($_POST['name']) && $_POST['name'] != '' && isset($_POST['id_artist']) && $_POST['id_artist'] != ''){
			$ajax['response'] = 'ok';
			$ajax['data'] = 'WESH '.$_POST['name'].' - '.$_POST['id_artist'];
			$ajax['data'] = $milesLyrics->ajaxCreateAlbum($_POST['name'],$_POST['id_artist']);
		}
		else{
			$ajax['error'] = 'No artist album or id_artist';
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
