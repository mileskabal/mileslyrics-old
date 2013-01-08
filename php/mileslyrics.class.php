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
	
	private function createArtistCheck($name){
		$data = $this->mysql_request("SELECT","SELECT * FROM mileslyrics_artist WHERE name LIKE '%".$name."%';");
		return $data;
	}
	
	private function createArtistAdd($name){
		$data = $this->mysql_request("INSERT","INSERT INTO mileslyrics_artist (id_artist,name) VALUES (NULL,'".$name."');");
		return $data;
	}
	
	public function ajaxCreateArtist($name){
		$retour = '';
		$checkData = $this->createArtistCheck($name);
		if($checkData['response'] == 'ok'){
			if(!$checkData['nbr']){
				$addData = $this->createArtistAdd($name);
				$retour = $addData;
			}
			else{
				$retour = $checkData['data'];
			}
		}
		else{
			$retour = $checkData['error'];
		}
		return $retour;		
	}
	
	public function templateCreateArtist(){
		$html = '';
		$html .= "<h2>Création d'artiste</h2>";
		$html .= '<input type="text" id="create_artist_name" /><input type="button" id="create_artist_button" value="ok" />';
		return $html;
	}

	public function templateCreateAlbum(){
		$html = '';
		$html .= "<h2>Création d'album</h2>";
		$listArtist = $this->getListArtist();
		if($listArtist['response'] == 'ok'){
			//~ $html .= '<pre>'.print_r($listArtist['data'],true).'</pre>';
			$html .= '<select id="create_album_select_artist">';
			$html .= '<option value=""> -- Artist -- </option>';				
			foreach($listArtist['data'] as $artist){
				$html .= '<option value="'.$artist['id_artist'].'">'.$artist['name'].'</option>';				
			}
			$html .= '</select>';
		}
		$html .= '<div id="create_album_div" style="display:none;"><input type="text" id="create_album_name" /><input type="button" id="create_album_button" value="ok" /></div>';
		return $html;
	}
	
}


?>
