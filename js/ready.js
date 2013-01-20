$(document).ready(function(){
	
	
	// MENU //
	// ARTIST
	$('#menu ul.artist li.artist > span').live("click", function(){
		$(this).parent().children('ul.album').slideToggle();
		return false;
	});
	$('#menu ul.artist li.artist > span').live("hover", function(){
		$(this).css('cursor','pointer');
	});
	$('#menu ul.artist li.artist > span').live("out", function(){
		$(this).css('cursor','main');
	});	
	// ALBUM
	$('#menu ul.artist li ul.album li > span').live("click", function(){
		$(this).parent().children('ul.track').slideToggle();
		return false;
	});
	$('#menu ul.artist li ul.album li.album > span').live("hover", function(){
		$(this).css('cursor','pointer');
	});
	$('#menu ul.artist li ul.album li.album > span').live("out", function(){
		$(this).css('cursor','main');
	});
	// TRACK
	$('#menu ul.artist li ul.album li.album ul.track li.track a').live("click", function(e){
		getLyrics($(this).data('id_track'));
		e.preventDefault();
		return false;
	});
	
	
	
	////////////////////////////////
	// ADMIN BUTTON AND ACTION
	////////////////////////////////
		
	// Create Artist Button
	$("#create_artist_button").live("click", function(){
		createArtist();
		return false;
	});
	
	// Create Artist Button confirm
	$("#create_artist_button_confirm").live("click", function(){
		createArtist(true);
		return false;
	});
		
	// Create Artist Button cancel
	$("#create_artist_button_cancel").live("click", function(){
		$('#create_artist_confirm').empty();
		return false;
	});
	
	// Create Album Select Artist
	$("#create_album_select_artist").change(function(){
		createAlbumSelectArtist();
		return false;
	});
	
	// Create Album Button
	$("#create_album_button").live("click", function(){
		createAlbum();
		return false;
	});
	
	// Create Tracks Select Artist
	$("#create_tracks_select_artist").change(function(){
		createTracksSelectArtist();
		return false;
	});
	
	// Create Tracks Select Album
	$("#create_tracks_select_album").change(function(){
		createTracksSelectAlbum();
		return false;
	});
	
	// Create Tracks Add Track Button
	$("#create_track_add").live("click", function(){
		createTrackAdd();
		return false;
	});
	
	// Create Tracks Remove Track Button
	$(".create_track_remove").live("click", function(){
		$(this).parent().remove();
		return false;
	});
	
	// Create Tracks Edit Track Button
	$(".create_track_edit").live("click", function(){
		createTrackEdit(this);
		return false;
	});
	
	// Create Tracks Edit Track Action Button
	$(".create_track_edit_action").live("click", function(){
		createTrackEditAction(this);
		return false;
	});

	// Create Tracks Edit Track Cancel Button
	$(".create_track_edit_action_cancel").live("click", function(){
		createTrackEditCancel(this);
		return false;
	});
	
	// Create Tracks Button
	$("#create_track_button").live("click", function(){
		createTracks();
		return false;
	});
	
	// Create Lyrics Button
	$(".create_track_lyrics").live("click", function(){
		createLyrics(this);
		return false;
	});
	
	// Create Lyrics Cancel Button
	$('#create_lyrics_button_cancel').live("click", function(){
		createLyricsCancel(this);
		return false;
	});

	// Create Lyrics Action Button
	$('#create_lyrics_button').live("click", function(){
		createLyricsAction(this);
		return false;
	});
	
	// Close Admin Button
	$("#close_admin").live("click", function(){
		$("#admin").hide();
		$("#wrapper").show();
		return false;
	});
	
});

jwerty.key('a,d,m,i,n', function () { $("#admin").show(); $("#wrapper").hide(); });
