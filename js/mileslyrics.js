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
						$('#create_artist_confirm').html('');
						$('#create_artist_return').html('ok');
						setTimeout((function() { $('#create_artist_return').html(''); $('#create_artist_name').val(''); }), 2000);
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
					setTimeout((function() { $('#create_album_return').html(''); $('#create_album_name').val(''); }), 2000);
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
		$('#create_album_return').html('');
	}
}

jwerty.key('a,d,m,i,n', function () { $("#admin").show(); });
