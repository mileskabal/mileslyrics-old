$(document).ready(function(){
	
	// Create Artist Button
	$("#create_artist_button").live("click", function(){
		createArtist();
	});
	
	// Create Artist Button confirm
	$("#create_artist_button_confirm").live("click", function(){
		createArtist(true);
	});
		
	// Create Artist Button cancel
	$("#create_artist_button_cancel").live("click", function(){
		$('#create_artist_confirm').html('');
	});
	
	// Create Album Select Artist
	$("#create_album_select_artist").change(function(){
		createAlbumSelectArtist();
	});
	
	// Create Album Button
	$("#create_album_button").live("click", function(){
		createAlbum();
	});
	
	// Create Tracks Select Artist
	$("#create_tracks_select_artist").change(function(){
		createTracksSelectArtist();
	});
	
	// Create Tracks Select Album
	$("#create_tracks_select_album").change(function(){
		createTracksSelectAlbum();
	});
	
	// Create Tracks Add Track Button
	$("#create_track_add").live("click", function(){
		createTrackAdd();
	});
	
	// Create Tracks Remove Track Button
	$(".create_track_remove").live("click", function(){
		$(this).parent().remove();
	});
	
	// Create Tracks Button
	$("#create_track_button").live("click", function(){
		createTracks();
	});
	
	// Close Admin Button
	$("#close_admin").live("click", function(){
		$("#admin").hide();
	});
	
});


// Create Artist ACTION AJAX
function createArtist(confirm){
	var addconfirm = '';
	if(confirm) addconfirm = '&confirm=1'; 
	var name = $("#create_artist_name").val();
	if(name != ''){
		$.ajax({
			type: "POST",
			url: "php/ajax.php",
			data: "lg="+global_lang+"&action=create_artist&name="+encodeURIComponent(name)+addconfirm,
			success: function(msg){
				var json = jQuery.parseJSON(msg);
				console.log(json);
				if(json.response == 'ok'){
					if(json.data.length){
						$('#create_artist_confirm').html(json.data[0]);
					}
					else{
						$('#create_artist_confirm').empty();
						$('#create_artist_return').html('ok');
						setTimeout((function() { $('#create_artist_return').empty(); $('#create_artist_name').val(''); }), 2000);
					}
				}
				else{
					alert("error");						
					alert(json.error);						
				}
			}
		});
	}
	else{
		alert('Empty artist field');
	}
}

// Create Album ACTION AJAX
function createAlbum(){
	var name = $("#create_album_name").val();
	var id_artist = $("#create_album_select_artist").val();
	if(name != ''){
		$.ajax({
			type: "POST",
			url: "php/ajax.php",
			data: "lg="+global_lang+"&action=create_album&name="+encodeURIComponent(name)+"&id_artist="+id_artist,
			success: function(msg){
				var json = jQuery.parseJSON(msg);
				console.log(json);
				if(json.response == 'ok'){
					$('#create_album_return').html('ok');
					setTimeout((function() { $('#create_album_return').empty(); $('#create_album_name').val(''); }), 2000);
				}
				else{
					$('#create_album_return').html('error');
				}
			}
		});
	}
	else{
		alert('Empty album field');
	}
}

// Create Album Select Artist ACTION AJAX
function createAlbumSelectArtist(){
	var id_artist = $('#create_album_select_artist').val();
	if(id_artist != ''){
		$.ajax({
			type: "POST",
			url: "php/ajax.php",
			data: "lg="+global_lang+"&action=create_album_select_artist&id_artist="+id_artist,
			success: function(msg){
				var json = jQuery.parseJSON(msg);
				console.log(json);
				$('#create_album_div').show();
				$('#create_album_return').html(json.data);
			}
		});
	}
	else{
		$('#create_album_div').hide();
		$('#create_album_return').empty();
	}
}


// Create Tracks Select Artist ACTION AJAX
function createTracksSelectArtist(){
	$('#create_tracks_div').empty().hide();
	var id_artist = $('#create_tracks_select_artist').val();
	if(id_artist != ''){
		console.log(id_artist);
		$.ajax({
			type: "POST",
			url: "php/ajax.php",
			data: "lg="+global_lang+"&action=create_tracks_select_artist&id_artist="+id_artist,
			success: function(msg){
				var json = jQuery.parseJSON(msg);
				console.log(json);
				$('#create_tracks_select_album').html(json.data).show();
			}
		});
	}
	else{
		$('#create_tracks_select_album').empty().hide();
	}
}

// Create Tracks Select Album ACTION AJAX
function createTracksSelectAlbum(){
	$('#create_tracks_div').empty().hide();
	var id_album = $('#create_tracks_select_album').val();
	if(id_album != ''){
		console.log(id_album);
		$.ajax({
			type: "POST",
			url: "php/ajax.php",
			data: "lg="+global_lang+"&action=create_tracks_select_album&id_album="+id_album,
			success: function(msg){
				var json = jQuery.parseJSON(msg);
				console.log(json);
				$('#create_tracks_div').html(json.data).show();
			}
		});
	}
}

// Create Tracks Add Track Button ACTION
function createTrackAdd(){
	var html = '';
	var option = '<option value="">-pos-</option>';
	for(i=1;i<100;i++){option += '<option value="'+i+'">'; if(i<10){option += '0';} option += i+'</option>';}
	html += '<p><select class="create_track_select">'+option+'</select><input type="text" value="" class="create_track_name" /> <input type="button" value="X" class="create_track_remove" /></p>';
	$('#create_track_tracklist').append(html);	
}

// Create Tracks
function createTracks(){
	var samePos = false;
	var arrayPos = new Array;
	var tracks = '';
	$('#create_track_tracklist p').each(function(){
		$track = $(this);
		var pos = $track.find('.create_track_select').val();
		var name = $track.find('.create_track_name').val();
		if(arrayPos.indexOf(pos) > -1){
			samePos = true;
		}
		else{
			arrayPos.push(pos);
			tracks += "&pos[]="+pos+"&name[]="+encodeURIComponent(name);
		}
	});
	
	if(!samePos){
		console.log(tracks);
		$.ajax({
			type: "POST",
			url: "php/ajax.php",
			data: "lg="+global_lang+"&action=create_tracks"+tracks,
			success: function(msg){
				var json = jQuery.parseJSON(msg);
				console.log(json);
				$('#create_tracks_return').html(json.data);
			}
		});
	}
	else{
		alert('same pos');
	}
}


jwerty.key('a,d,m,i,n', function () { $("#admin").show(); });
