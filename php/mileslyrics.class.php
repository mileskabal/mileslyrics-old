<?php

class MilesLyrics{
	
	private $db_data;
	private $db;
	
	public function __construct($database){
		$this->db_data = $database;
		$connect = $this->connect();
		if($connect !== true){
			echo $connect;
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
	
	private function getListArtist(){
		$data = $this->mysql_request("SELECT","SELECT * FROM mileslyrics_artist");
		return $data;
	}
	
	private function getListAlbumByArtist($id_artist){
		$data = $this->mysql_request("SELECT","SELECT m_al.name FROM mileslyrics_artist_album m_ar_al 
		LEFT JOIN mileslyrics_album m_al ON m_ar_al.id_album=m_al.id_album 
		LEFT JOIN mileslyrics_artist m_ar ON m_ar_al.id_artist=m_ar.id_artist
		WHERE m_ar.id_artist=".$id_artist."
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
			//~ $html .= '<pre>'.print_r($listArtist['data'],true).'</pre>';
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
}


?>
