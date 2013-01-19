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
	$('#create_lyrics').hide();
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
	$('#create_lyrics').hide();
	$('#create_tracks_div,').empty().hide();
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
	html += '<p class="create"><select class="create_track_select">'+option+'</select><input type="text" value="" class="create_track_name" /> <input type="button" value="X" class="create_track_remove" /></p>';
	$('#create_track_tracklist').append(html);	
}


function createTrackShowHideButton(button,action){
	$p = $(button).parent();
	if(action == 'show'){
		$p.find('.create_track_select').removeAttr('disabled');
		$p.find('.create_track_name').removeAttr('disabled');
		$p.find('.create_track_edit_action').show();
		$p.find('.create_track_edit_action_cancel').show();
		$p.find('.create_track_edit').hide();
		$p.find('.create_track_lyrics').hide();
	}
	else if(action == 'hide'){
		$p.find('.create_track_select').attr('disabled','disabled');
		$p.find('.create_track_name').attr('disabled','disabled');
		$p.find('.create_track_edit_action').hide();
		$p.find('.create_track_edit_action_cancel').hide();
		$p.find('.create_track_edit').show();
		$p.find('.create_track_lyrics').show();
	}
	return false;
}

// Create Tracks Edit Track Button
function createTrackEdit(button){
	createTrackShowHideButton(button,'show');
}

// Create Tracks Edit Track Button
function createTrackEditCancel(button){
	createTrackShowHideButton(button,'hide');
}

// Create Tracks Edit Track Button Action 
function createTrackEditAction(button){
	$p = $(button).parent();
	var id_track = $(button).data('id_track');
	var pos = $p.find('.create_track_select').val();
	var name = $p.find('.create_track_name').val();
	console.log(id_track+' - '+pos+' - '+name);
	$.ajax({
		type: "POST",
		url: "php/ajax.php",
		data: "lg="+global_lang+"&action=create_tracks_edit&id_track="+id_track+"&pos="+pos+"&name="+encodeURIComponent(name),
		success: function(msg){
			var json = jQuery.parseJSON(msg);
			console.log(json);
			createTrackShowHideButton(button,'hide');
		}
	});
}

// Create Tracks
function createTracks(){
	var samePos = false;
	var arrayPos = new Array;
	var tracks = '';
	$('#create_track_tracklist p.create').each(function(){
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
		var id_album = $('#create_tracks_select_album').val();
		$.ajax({
			type: "POST",
			url: "php/ajax.php",
			data: "lg="+global_lang+"&action=create_tracks&id_album="+id_album+tracks,
			success: function(msg){
				var json = jQuery.parseJSON(msg);
				console.log(json);
				var insert_id = eval(json.data);
				var i=0;
				$('#create_track_tracklist p.create').each(function(){
					$(this).removeClass('create');
					$(this).find('.create_track_select').attr('disabled','disabled');
					$(this).find('.create_track_name').attr('disabled','disabled');
					$(this).find('.create_track_remove').remove();
					$(this).append('<input type="button" value="'+GLOBAL_ADMIN_EDIT+'" class="create_track_edit" /><input type="button" value="'+GLOBAL_ADMIN_LYRICS+'" class="create_track_lyrics" /><input style="display:none;" type="button" value="'+GLOBAL_ADMIN_OK+'" class="create_track_edit_action" data-id_track="'+insert_id[i]+'" /><input style="display:none;" type="button" value="'+GLOBAL_ADMIN_CANCEL+'" class="create_track_edit_action_cancel" />');
					i++;
				});
				$('#create_tracks_return').html('ok');
				setTimeout((function() { $('#create_tracks_return').empty(); }), 2000);
			}
		});
	}
	else{
		alert('same pos');
	}
}

// Create Lyrics Button
function createLyrics(button){
	var id_track = $(button).data('id_track');
	var id_lyrics = $(button).data('id_lyrics');
	$.ajax({
		type: "POST",
		url: "php/ajax.php",
		data: "lg="+global_lang+"&action=get_lyrics&id_track="+id_track,
		success: function(msg){
			var json = jQuery.parseJSON(msg);
			console.log(json);
			if(json.response == 'ok'){
				$('#lyrics_text').val('');
				if(json.data.nbr){
					$('#lyrics_text').val(json.data.data[0].text);
				}
				$('#create_lyrics_button').data('id_track',id_track);
				$('#create_lyrics_button').data('id_lyrics',id_lyrics);
				$('#create_lyrics').slideDown();
				$('#create_tracks_div p').not($(button).parent()).slideUp();
			}
		}
	});
}

// Create Lyrics Action Button
function createLyricsAction(button){
	var id_track = $(button).data('id_track');
	var id_lyrics = $(button).data('id_lyrics');
	var text = $('#lyrics_text').val();
	if(text != ''){
		$.ajax({
			type: "POST",
			url: "php/ajax.php",
			data: "lg="+global_lang+"&action=set_lyrics&id_track="+id_track+"&id_lyrics="+id_lyrics+"&text="+encodeURIComponent(text),
			success: function(msg){
				var json = jQuery.parseJSON(msg);
				console.log(json);
				if(json.response == 'ok'){
					$('#create_track_tracklist p .create_track_lyrics').each(function(){
						var p_id_track = $(this).data('id_track');
						if(id_track == p_id_track){
							if(json.data.insert_id){
								$(this).data('id_lyrics',json.data.insert_id);
								$(this).addClass('lyrics_set');
							}
						}
					});
					$('#lyrics_text').val('');
					$('#create_lyrics').hide();
					$('#create_tracks_div p').slideDown();
				}
			}
		});
	}
	else{
		alert('Lyrics empty');
	}
}

// Create Lyrics Cancel Button
function createLyricsCancel(button){
	$('#lyrics_text').val('');
	$('#create_lyrics').hide();
	$('#create_tracks_div p').slideDown();
}
