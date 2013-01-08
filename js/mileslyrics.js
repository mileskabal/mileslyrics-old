$(document).ready(function(){
	
	// Create Artist Button
	$("#create_artist_button").live("click", function(){
		var name = $("#create_artist_name").val();
		if(name != ''){
			$.ajax({
				type: "POST",
				url: "php/ajax.php",
				data: "action=create_artist&name="+name,
				success: function(msg){
					var json = jQuery.parseJSON(msg);
					console.log(json);
				}
			});
		}
		else{
			alert('Empty artist field');
		}
	});
	
	// Create Album Select Artist
	$("#create_album_select_artist").change(function(){
		var id_artist = $(this).val();
		if(id_artist != ''){
			$('#create_album_div').show();
		}
		else{
			$('#create_album_div').hide();
		}
	});
	
	// Create Album Button
	$("#create_album_button").live("click", function(){
		var name = $("#create_album_name").val();
		if(name != ''){
			$.ajax({
				type: "POST",
				url: "php/ajax.php",
				data: "action=create_album&name="+name,
				success: function(msg){
					var json = jQuery.parseJSON(msg);
					console.log(json);
				}
			});
		}
		else{
			alert('Empty album field');
		}
	});
	
	$("#close_admin").live("click", function(){
		$("#admin").hide();
	});
	
});

jwerty.key('a,d,m,i,n', function () { $("#admin").show(); });
