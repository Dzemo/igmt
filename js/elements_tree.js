/**
 * Add jaxButton to generate tree
 */
$(function(){
	$('#button-generate').jaxButton({
		url:"processing/ajax_generate_image_tree.php", 
		done: function(dataset, data, textStatus, jqXHR){
			try{
				var json = jQuery.parseJSON(data);

				$('#generation-date').text("Generated on "+json.date);
				$('#generation-log').html(json.output);
				noty({text: "Image generated in "+json.time+"s", type:'success'});

				previous_src = $('#image-tree').attr('src');
				$('#image-tree').attr('src',previous_src.split('#')[0]+'#'+new Date());

			}catch(err){
				noty({text: 'Error while parsing json response: '+err, type:'error'});
			}
		}
	});
});

/**
 * Toggle the generation log
 */
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