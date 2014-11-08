/**
 * Initialisation :
 * -SumoSelect
 * -Colorpicker
 * -jaxButton on save button for modal edit
 * -jaxButton on delete button for modal delete
 */
$(function(){
	//SumoSelect 
	$('.sumo-select').SumoSelect();

	//Color picker
	$( ".colorpicker-red, .colorpicker-green, .colorpicker-blue" ).slider({
		orientation: "horizontal",
		range: "min",
		max: 255,
		value: 127,
		//slide: onChangeSlider,
		change: onChangeSlider
	});
	$('.color-input-color-css').change(onChangeInput);

	//jaxButton on save button for modal edit
	$('.button-save-category').jaxButton({
		url:"processing/ajax_edit_category.php", 
		getData: function(dataset){
			var form_id = dataset.formId;
			var data = {
							id: $('#'+form_id+'-input-id').val(),
							name: $('#'+form_id+'-input-name').val(),
							description: $('#'+form_id+'-textarea-description').val(),
							color: $('#'+form_id+'_color-css').val()
						};
			return data;
		},
		before: function(dataset){
			noty({text: 'Saving category '+$('#'+dataset.formId+'-input-name').val(), type:'information'});
			return true;
			},
		done: function(dataset, data, textStatus, jqXHR){
			try{
				var json = jQuery.parseJSON(data);
				if(json.hasOwnProperty('errors')){
					console.log(json.errors);
					for(var i in json.errors){
						noty({text: json.errors[i], type:'error'});
					}
				}
				else{
					if(json.hasOwnProperty('redirect')){
						setTimeout(function(){
							document.location.href=json.redirect;
						},750);
					}
					if(json.hasOwnProperty('message')){
						noty({text: json.message, type:'success'});
					}
				}					
			}catch(err){
				noty({text: 'Error while parsing json response: '+err, type:'error'});
			}
		}
	});

	//jaxButton on delete button for modal delete
	$('.button-delete-category').jaxButton({
		url:"processing/ajax_delete_category.php", 
		getData: function(dataset){
			var form_id = dataset.formId;
			var data = {
							id: $('#'+form_id+'-input-id').val(),	
							name: $('#'+form_id+'-input-name').val()						
						};
			return data;
		},
		before: function(dataset){
			var form_id = dataset.formId;

				if($('#'+form_id+'-input-name').val().toLowerCase() == $('#'+form_id+'-input-confirm-name').val().toLowerCase()){				
					noty({text: 'Deleting category '+$('#'+form_id+'-input-name').val(), type:'information'});
					return true;
				}
				else{				 
					$('#'+form_id+'-input-confirm-name').val('')
					noty({text: 'Confirmation name is incorrect', type:'warning'});
					return false;
				}

			},
		done: function(dataset, data, textStatus, jqXHR){
			try{
				var json = jQuery.parseJSON(data);
				if(json.hasOwnProperty('errors')){
					console.log(json.errors);
					for(var i in json.errors){
						noty({text: json.errors[i], type:'error'});
					}
				}
				else{
					if(json.hasOwnProperty('redirect')){
						setTimeout(function(){
							document.location.href=json.redirect;
						},750);
					}
					if(json.hasOwnProperty('message')){
						noty({text: json.message, type:'success'});
					}
				}					
			}catch(err){
				noty({text: 'Error while parsing json response: '+err, type:'error'});
			}
		}
	});

});

/**
 * Slider change handler :
 * update input, select and swatch
 */
function onChangeSlider(){
	var form_id = this.id.split('_')[0];

	var red = $( "#"+form_id+"_red" ).slider( "value" ),
		green = $( "#"+form_id+"_green" ).slider( "value" ),
		blue = $( "#"+form_id+"_blue" ).slider( "value" );

	var colorHex = "#" + hexFromRGB( red, green, blue );

	if($("#"+form_id+"_color-css").val() != colorHex)		
		$('#'+form_id+'_color-css').val(colorHex);	

	//Try to select a suggested color if the selected color existe	
	if($("#"+form_id+"_select-colorexist option[value='"+colorHex+"']").length > 0){

		if($("#"+form_id+"_select-colorexist").val() != colorHex)
			$("#"+form_id+"_select-colorexist").val(colorHex);
	}
	else{

		if($("#"+form_id+"_select-colorexist").val() != 'no-match')
			$("#"+form_id+"_select-colorexist").val('no-match');
	}

	//Update color preview
	$( "#"+form_id+"_swatch" ).css( "background-color", colorHex );	

}

/**
 * Input change handler
 * Update select, slider and swatch
 */
function onChangeInput(){
	var form_id = this.id.split('_')[0];
	var colorHex = $("#"+form_id+"_color-css").val().toLowerCase();

	//Set input to lower case
	if(colorHex != $("#"+form_id+"_color-css").val())			
	   $("#"+form_id+"_color-css").val(colorHex); //toLowerCase

	//update slider value
	var colorRgb = hexToRgb(colorHex);
	if(colorRgb){

		if($( "#"+form_id+"_red" ).slider( "value") != colorRgb.r)
			$( "#"+form_id+"_red" ).slider( "value", colorRgb.r );

		if($( "#"+form_id+"_green" ).slider( "value") != colorRgb.g)
			$( "#"+form_id+"_green" ).slider( "value", colorRgb.g );

		if($( "#"+form_id+"_blue" ).slider( "value") != colorRgb.b)
			$( "#"+form_id+"_blue" ).slider( "value", colorRgb.b );
	}

	//Try to select a suggested color if the selected color existe	
	if($("#"+form_id+"_select-colorexist option[value='"+colorHex+"']").length > 0){

		if($("#"+form_id+"_select-colorexist").val() != colorHex)
			$("#"+form_id+"_select-colorexist").val(colorHex);
	}
	else{

		if($("#"+form_id+"_select-colorexist").val() != 'no-match')
			$("#"+form_id+"_select-colorexist").val('no-match');
	}

	//Update color preview
	$( "#"+form_id+"_swatch" ).css( "background-color", colorHex );	
}

/**
 * Set the color for a form
 * Update input, slider, select and swatch
 * @param {string} colorHex "#000000"
 * @param {string} form_id
 */
function SetColorForForm(colorHex, form_id){	
	colorHex = colorHex.toLowerCase();

	//Update input value
	if(colorHex != $("#"+form_id+"_color-css").val())			
	   $("#"+form_id+"_color-css").val(colorHex); //toLowerCase

	//update slider value
	var colorRgb = hexToRgb(colorHex);
	if(colorRgb){

		if($( "#"+form_id+"_red" ).slider( "value") != colorRgb.r)
			$( "#"+form_id+"_red" ).slider( "value", colorRgb.r );

		if($( "#"+form_id+"_green" ).slider( "value") != colorRgb.g)
			$( "#"+form_id+"_green" ).slider( "value", colorRgb.g );

		if($( "#"+form_id+"_blue" ).slider( "value") != colorRgb.b)
			$( "#"+form_id+"_blue" ).slider( "value", colorRgb.b );
	}

	//Try to select a suggested color if the selected color existe	
	if($("#"+form_id+"_select-colorexist option[value='"+colorHex+"']").length > 0){

		if($("#"+form_id+"_select-colorexist").val() != colorHex)
			$("#"+form_id+"_select-colorexist").val(colorHex);
	}
	else{

		if($("#"+form_id+"_select-colorexist").val() != 'no-match')
			$("#"+form_id+"_select-colorexist").val('no-match');
	}

	//Update color preview
	$( "#"+form_id+"_swatch" ).css( "background-color", colorHex );	
}

/**
 * Select change handler
 * Update input, slider and swatch
 * 
 * @param  {string} form_id 
 */
function onChangeSelect(form_id){

	var colorHex = $("#"+form_id+"_select-colorexist").val().toLowerCase();

	//Update input value
	if($("#"+form_id+"_color-css").val() != colorHex)
		$("#"+form_id+"_color-css").val(colorHex);

	//update slider value
	var colorRgb = hexToRgb(colorHex);
	if(colorRgb){

		if($( "#"+form_id+"_red" ).slider( "value") != colorRgb.r)
			$( "#"+form_id+"_red" ).slider( "value", colorRgb.r );

		if($( "#"+form_id+"_green" ).slider( "value") != colorRgb.g)
			$( "#"+form_id+"_green" ).slider( "value", colorRgb.g );

		if($( "#"+form_id+"_blue" ).slider( "value") != colorRgb.b)
			$( "#"+form_id+"_blue" ).slider( "value", colorRgb.b );
	}

	//Update color preview
	$( "#"+form_id+"_swatch" ).css( "background-color", colorHex );	
}


/**
 * Open the modal for a specified if and update the colorpicker values
 * @param  {string} form_id
 */
function openModalEditCategory(form_id, category_id){
	//Populate colorexist option
	if($('#'+form_id+'_select-colorexist').children().length == 0){
		$('#select-colorexist-option option').clone().appendTo('#'+form_id+'_select-colorexist');
		$('#'+form_id+'_select-colorexist').SumoSelect();
	}

	//Set color in input (which will update color in the other field)
	if(form_id == "edit-category-new"){
		//For new form, generate a random color
		colorHex = "#" + hexFromRGB(Math.floor(Math.random() * 255) + 1, Math.floor(Math.random() * 255) + 1, Math.floor(Math.random() * 255) + 1);
		SetColorForForm(colorHex, form_id);
	}
	else{
		colorHex = $('#category-'+category_id+' .color-td span').html();
		SetColorForForm(colorHex, form_id);
	}

	//Open Modal
	$('#'+form_id).bPopup();
}

/**
 * Close the edit modal for the specified from
 * @param  {string} form_id
 */
function closeModalEditCategory(form_id){
	//Close modal
	$('#'+form_id).bPopup().close();
}

/**
 * Transform a color from rgb to hexadecimal value (without #)
 * @param  {mixed} r 
 * @param  {mixed} g 
 * @param  {mixed} b 
 * @return {string}   
 */
function hexFromRGB(r, g, b) {
	var hex = [
		r.toString( 16 ),
		g.toString( 16 ),
		b.toString( 16 )
	];
	$.each( hex, function( nr, val ) {
		if ( val.length === 1 ) {
			hex[ nr ] = "0" + val;
		}
	});
	return hex.join( "" ).toLowerCase();
}

/**
 * Transform a color from hexadecimal value (# optionnal, 6 value aka no ccc) to {r, g b} color
 * @param  {string} hex 
 * @return {array}     {r, g, b} or null if the parameter is not a hex color
 */
function hexToRgb(hex) {
    var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result ? {
        r: parseInt(result[1], 16),
        g: parseInt(result[2], 16),
        b: parseInt(result[3], 16)
    } : null;
}