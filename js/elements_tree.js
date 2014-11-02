function generateTree(){
	$('#button-generate-element-tree-generate').hide();
	$('#button-generate-element-tree-generating').show();

	var data_post = {};

	$.ajax({type:"POST",
			url:"processing/ajax_generate_image_tree.php", 
			data: data_post
	}).always(function(){		
		$('#button-generate-element-tree-generate').show();
		$('#button-generate-element-tree-generating').hide();

	}).done(function(result){
		try{
			var json = jQuery.parseJSON(result);

			$('#generation-date').text("Generated on "+json.date);
			$('#generation-log').html(json.output);
			noty({text: "Image generated in "+json.time+"s", type:'success'});

		}catch(err){
			noty({text: 'Error while parsing json response', type:'error'});
		}
	}).error(function(jqXHR, textStatus, errorThrown){		
		text = textStatus;
		if(errorThrown) text+= ": "+errorThrown;
		noty({text: text, type:'error'});
	});
}

function toggleGenerationLog(){
	if($('#generation-log').is(':visible')){
		$('#generation-log').hide();
		$('#button-toggle-generation-log').text('Show generation log');
	}
	else{
		$('#generation-log').show();
		$('#button-toggle-generation-log').text('Hide generation log');
	}
}