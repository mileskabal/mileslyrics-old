<?php

class MilesLyrics{
	
	private $db_data;
	private $db;
	
	public $javascript;
	
	public function __construct($database){
		$this->db_data = $database;
		$connect = $this->connect();
		if($connect !== true){
			echo $connect;
		}
		$lg = 'eng';
		if(isset($_REQUEST['lg']) && $_REQUEST['lg'] != ''){
			$lg = $_REQUEST['lg'];
			switch($_REQUEST['lg']){
				case 'eng':
					include('lg/eng.php');
				break;
				case 'fr':
					include('lg/fr.php');
				break;
				default:
					include('lg/'._CONFIG_LANG.'.php');
					$lg = _CONFIG_LANG;
				break;
			}	
		}
		else{
			$lg = _CONFIG_LANG;
			include('lg/'._CONFIG_LANG.'.php');
		}
		
		$this->javascript = '';
		$this->javascript .= 'var global_lang = "'.$lg.'";';
		$arr = get_defined_constants(true); 
		foreach($arr['user'] as $key => $value){
			if(substr($key,0,3) != '_DB'){
				$this->javascript .= 'var GLOBAL'.$key.'= "'.$value.'";';
			}
		}
		
	}
	
	private function connect(){
		if(!$this->db = mysql_connect($this->db_data[0],$this->db_data[1],$this->db_data[2])) return mysql_error();
		if(!mysql_select_db($this->db_data[3])) return "Unable to connect to db : ".$this->db_data[3];
		mysql_query("SET NAMES UTF8");
		return true;
	}
	
	private function mysql_request($type,$rq){
		$data = array('nbr'=>0,'response'=>'error');
		if($reponse = mysql_query($rq,$this->db)){
			$data['response'] = 'ok';
			switch($type){
				case 'SELECT' :
					$data['nbr'] = mysql_num_rows($reponse);
					$data['data'] = array();
					while($lg = mysql_fetch_assoc($reponse)){
						array_push($data['data'],$lg);
					}
				break;
				case 'INSERT' :
					$data['insert_id'] = mysql_insert_id();
				break;
			}
		}
		else{
			$data['error'] = mysql_error();
		}
		return $data;
	}
	
	private function getListArtist($album=false){
		$rq = "SELECT * FROM mileslyrics_artist";
		if($album){
			$rq = "SELECT m_ar.id_artist,m_ar.name 
			FROM mileslyrics_artist_album m_ar_al
			LEFT JOIN mileslyrics_artist m_ar ON m_ar.id_artist=m_ar_al.id_artist
			LEFT JOIN mileslyrics_album m_al ON m_al.id_album=m_ar_al.id_album
			GROUP BY m_ar.id_artist;";
		}
		$data = $this->mysql_request("SELECT",$rq);
		return $data;
	}
	
	private function getListAlbumByArtist($id_artist){
		$data = $this->mysql_request("SELECT","SELECT m_al.id_album, m_al.name FROM mileslyrics_artist_album m_ar_al 
		LEFT JOIN mileslyrics_album m_al ON m_ar_al.id_album=m_al.id_album 
		LEFT JOIN mileslyrics_artist m_ar ON m_ar_al.id_artist=m_ar.id_artist
		WHERE m_ar.id_artist=".$id_artist."
		");
		return $data;
	}
	
	private function getListTracksByAlbum($id_album){
		$data = $this->mysql_request("SELECT","SELECT mt.* FROM mileslyrics_track_album mta
		LEFT JOIN mileslyrics_track mt ON mt.id_track=mta.id_track
		LEFT JOIN mileslyrics_album ma ON ma.id_album=mta.id_album
		WHERE mta.id_album=".$id_album."
		ORDER BY mt.pos
		");
		return $data;
	}
	
	private function createArtistCheck($name){
		$data = $this->mysql_request("SELECT","SELECT * FROM mileslyrics_artist WHERE name LIKE '%".mysql_real_escape_string($name)."%';");
		return $data;
	}
	
	private function createArtistAdd($name){
		$data = $this->mysql_request("INSERT","INSERT INTO mileslyrics_artist (id_artist,name) VALUES (NULL,'".mysql_real_escape_string($name)."');");
		return $data;
	}
	
	private function createAlbum($name,$id_artist=0){
		$data = $this->mysql_request("INSERT","INSERT INTO mileslyrics_album (id_album,name) VALUES (NULL,'".mysql_real_escape_string($name)."');");
		$id_album = $data['insert_id'];
		if($id_artist && $id_album){
			$data2 = $this->mysql_request("INSERT","INSERT INTO mileslyrics_artist_album (id_artist,id_album) VALUES (".$id_artist.",".$id_album.");");
		}
		return $data;
	}
	
	private function createTrack($id_album,$pos,$name){
		$data = $this->mysql_request("INSERT","INSERT INTO mileslyrics_track (id_track,name,pos) VALUES (NULL,'".mysql_real_escape_string($name)."',".$pos.");");
		$id_track = $data['insert_id'];
		if($id_track){
			$data2 = $this->mysql_request("INSERT","INSERT INTO mileslyrics_track_album (id_track,id_album) VALUES (".$id_track.",".$id_album.");");
		}
		return $data;
	}

	private function editTrack($id_track,$pos,$name){
		$data = $this->mysql_request("UPDATE","UPDATE mileslyrics_track SET name='".mysql_real_escape_string($name)."', pos=".$pos." WHERE id_track=".$id_track.";");
		//~ return "UPDATE mileslyrics_track SET name='".mysql_real_escape_string($name)."', pos=".$pos." WHERE id_track=".$id_track.";";
		return $data;
	}
	
	public function ajaxCreateArtist($name,$confirm=false){
		$return = '';
		if($confirm){
			$checkData = array('response'=>'ok','nbr'=>0);
		}
		else{
			$checkData = $this->createArtistCheck($name);
		}
		if($checkData['response'] == 'ok'){
			if(!$checkData['nbr']){
				$addData = $this->createArtistAdd($name);
				$return = $addData;
			}
			else{
				$rt = '';
				foreach($checkData['data'] as $entry){
					$rt .= '<p>'.$entry['name'].'</p>';
				}
				$rt .= '<p><input type="button" id="create_artist_button_confirm" value="confirm" /><input type="button" id="create_artist_button_cancel" value="cancel" /></p>';
				$return = array($rt);
			}
		}
		else{
			$return = $checkData['error'];
		}
		return $return;		
	}
	
	public function templateCreateArtist(){
		$html = '';
		$html .= '<h2>'._ADMIN_CREATE_ARTIST.'</h2>';
		$html .= '<div id="create_artist_confirm"></div>';
		$html .= '<input type="text" id="create_artist_name" /><input type="button" id="create_artist_button" value="ok" />';
		$html .= '<div id="create_artist_return"></div>';
		return $html;
	}

	public function ajaxCreateAlbumSelectArtist($id_artist){
		$return = _NO_ALBUM;
		$data = $this->getListAlbumByArtist($id_artist);
		if(count($data['data'])){
			$return = '';
			foreach($data['data'] as $album){
				$return .= '<p>'.$album['name'].'</p>';
			}
		}
		return $return;
	}
	
	public function ajaxCreateAlbum($name,$id_artist=0){
		$data = $this->createAlbum($name,$id_artist);
		return print_r($data,true);
	}

	public function templateCreateAlbum(){
		$html = '';
		$html .= '<h2>'._ADMIN_CREATE_ALBUM.'</h2>';
		$listArtist = $this->getListArtist();
		if($listArtist['response'] == 'ok'){
			$html .= '<select id="create_album_select_artist">';
			$html .= '<option value=""> -- '._ARTIST.' -- </option>';				
			foreach($listArtist['data'] as $artist){
				$html .= '<option value="'.$artist['id_artist'].'">'.$artist['name'].'</option>';				
			}
			$html .= '</select>';
		}
		$html .= '<div id="create_album_div" style="display:none;"><input type="text" id="create_album_name" /><input type="button" id="create_album_button" value="ok" /></div>';
		$html .= '<div id="create_album_return"></div>';
		return $html;
	}
	
	public function ajaxCreateTracksSelectArtist($id_artist){
		$return = _NO_ALBUM;
		$data = $this->getListAlbumByArtist($id_artist);
		if(count($data['data'])){
			$return = '';
			$return .= '<option value=""> -- '._ALBUM.' --</option>';
			foreach($data['data'] as $album){
				$return .= '<option value="'.$album['id_album'].'">'.$album['name'].'</option>';
			}
		}
		return $return;
	}
	
	public function ajaxCreateTracksSelectAlbum($id_album){
		$return = '';
		$data = $this->getListTracksByAlbum($id_album);
		$return .= '<div id="create_track_tracklist">';
		if(count($data['data'])){
			foreach($data['data'] as $track){
				$option = '<option value="">-pos-</option>';
				for($i=1;$i<100;$i++){$optionSelected = ''; if($i == $track['pos']){$optionSelected = ' selected="selected"';}$option .= '<option value="'.$i.'"'.$optionSelected.'>'; if($i<10){$option .= '0';} $option .= $i.'</option>';}
				$return .= '<p><select class="create_track_select" disabled>'.$option.'</select><input type="text"  class="create_track_name" value="'.$track['name'].'" disabled /> <input type="button" value="'._ADMIN_EDIT.'" class="create_track_edit" /><input type="button" value="'._ADMIN_LYRICS.'" class="create_track_lyrics" /><input style="display:none;" type="button" value="'._ADMIN_OK.'" class="create_track_edit_action" data-id_track="'.$track['id_track'].'" /><input style="display:none;" type="button" value="'._ADMIN_CANCEL.'" class="create_track_edit_action_cancel" /></p>';
			}
		}
		else{
			$option = '<option value="">-pos-</option>';
			for($i=1;$i<100;$i++){$option .= '<option value="'.$i.'">'; if($i<10){$option .= '0';} $option .= $i.'</option>';}
			$return .= '<p class="create"><select class="create_track_select">'.$option.'</select><input type="text" value="" class="create_track_name" /> <input type="button" value="X" class="create_track_remove" /></p>';
		}
		$return .= '</div>';
		$return .= '<p><input type="button" value="'._ADMIN_ADD_TRACK.'" id="create_track_add" /><input type="button" value="'._ADMIN_SAVE.'" id="create_track_button" /></p>';
		
		return $return;
	}
	
	public function ajaxCreateTracksEdit($id_track,$track_pos,$track_name){
		$data = $this->editTrack($id_track,$track_pos,$track_name);
		return $data;
	}

	public function ajaxCreateTracks($id_album,$track_pos,$track_name){
		$return = '';
		$insert_id = array();
		for($i=0;$i<count($track_pos);$i++){			
			$data = $this->createTrack($id_album,$track_pos[$i],$track_name[$i]);
			array_push($insert_id,$data['insert_id']);
		}
		$return = json_encode($insert_id);
		return $return;
	}
	
	public function templateCreateTracks(){
		$html = '';
		$html .= '<h2>'._ADMIN_CREATE_TRACKS.'</h2>';
		$listArtist = $this->getListArtist(true);
		if($listArtist['response'] == 'ok'){
			$html .= '<select id="create_tracks_select_artist">';
			$html .= '<option value=""> -- '._ARTIST.' -- </option>';				
			foreach($listArtist['data'] as $artist){
				$html .= '<option value="'.$artist['id_artist'].'">'.$artist['name'].'</option>';				
			}
			$html .= '</select>';
		}
		$html .= '<select id="create_tracks_select_album" style="display:none;">';
		$html .= '</select>';
		$html .= '<div id="create_tracks_div" style="display:none;"></div>';
		$html .= '<div id="create_tracks_return"></div>';
		return $html;
	}
}


?>
