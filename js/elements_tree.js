/**
 * Add jaxButton to generate tree
 */
$(function(){
	$('#button-generate').jaxButton({
		url:"processing/ajax_generate_image_tree.php",
		before: function(dataset){
			noty({text: 'Starting tree generation, this may take a while', type:'information'});
		} ,
		done: function(dataset, data, textStatus, jqXHR){
			try{
				var json = jQuery.parseJSON(data);

				$('#generation-date').text("Generated on "+json.date);
				$('#generation-log').html(json.output);
				noty({text: "Image generated in "+json.time+"s after "+json.attempt_number+" attempts", type:'success'});

				previous_src = $('#image-tree').attr('src');
				$('#image-tree').attr('src',previous_src.split('#')[0]+'#'+Date.now());

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