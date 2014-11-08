/**
 * Initialisation :
 * -SumoSelect
 * -Jquery-ui tabs for modal edit
 * -category-filter change handler
 * -jaxButton on save button for modal edit
 * -jaxButton on delete button for modal delete
 */
$(function(){

	//SumoSelect 
	$('.sumo-select').SumoSelect();

	//Jquery-ui tabs for modal edit
	$('.modal-edit-element .form-content').tabs();

	//category-filter change handler and show selected category
	$('#category-filter').change(function(){
		var selectedCategory = $(this).val();

		if(selectedCategory == "all"){
			$('.element-tr').show();
		}
		else{
			$('.element-tr').hide();
			$('.category-'+selectedCategory).show();
		}
	});
	var selectedCategory = $('#category-filter').val();
	if(selectedCategory == "all"){
		$('.element-tr').show();
	}
	else{
		$('.element-tr').hide();
		$('.category-'+selectedCategory).show();
	}

	//jaxButton on save button for modal edit
	$('.button-save-element').jaxButton({
		url:"processing/ajax_edit_element.php", 
		getData: function(dataset){
			var form_id = dataset.formId;
			var data = {
							id: $('#'+form_id+'-input-id').val(),
							name: $('#'+form_id+'-input-name').val(),
							description: $('#'+form_id+'-textarea-description').val(),
							category: $('#'+form_id+'-select-category').val(),
							need: getDataForLinkTypeAndCardinal(form_id, 'need', 'many'),
							allow: getDataForLinkTypeAndCardinal(form_id, 'allow', 'many'),
							extend: getDataForLinkTypeAndCardinal(form_id, 'extend', 'one'),
							extendedby: getDataForLinkTypeAndCardinal(form_id, 'extend', 'many'),
							regress: getDataForLinkTypeAndCardinal(form_id, 'evolve', 'one'),
							evolve: getDataForLinkTypeAndCardinal(form_id, 'evolve', 'many'),
						};
			return data;
		},
		before: function(dataset){
			noty({text: 'Saving element '+$('#'+dataset.formId+'-input-name').val(), type:'information'});
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
	$('.button-delete-element').jaxButton({
		url:"processing/ajax_delete_element.php", 
		getData: function(dataset){
			var form_id = dataset.formId;
			var data = {
							id: $('#'+form_id+'-input-id').val(),	
							name: $('#'+form_id+'-input-name').val()						
						};
			return data;
		},
		before: function(dataset){
			noty({text: 'Deleting element '+$('#'+dataset.formId+'-input-name').val(), type:'information'});
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

});

/**
 * Retrive post data for a link type
 * @param  {string} form_id   
 * @param  {string} link_type 
 * @param  {string} cardinal	many|one
 * @return {mixed}  			Return an array for cardinal many or the element/null for cardinal one	         
 */
function getDataForLinkTypeAndCardinal(form_id, link_type, cardinal){
	link_array = [];
	$('#'+form_id+'-'+link_type+' .link-tr-'+cardinal).each(function(){
		link_array.push({
			link_id: $('.'+form_id+'-'+link_type+'-input-linkid',this).val(),
			target_id: $('.'+form_id+'-'+link_type+'-select-element',this).val(),
			conditions: $('.'+form_id+'-'+link_type+'-textarea-condition',this).val()
		});
	});

	if(cardinal == 'many'){
		return link_array;
	}
	else {
		if(link_array.length == 1) 
			return link_array[0];
		else 
			return null;
	}
}

/**
 * Add a tr with select for element and texterea for conditons to add a link
 * @param  {string} link_type                  need|allow|extend|evolve
 * @param  {string} form_id               
 * @param  {string} cardinal                   many|one
 */
function addTrLink(link_type, form_id, cardinal){

	var index = 0;
	$('#'+form_id+'-'+link_type+' table tbody tr').each(function(){
		if($(this).data('index') > index)
			index = $(this).data('index');
	});
	index++;

	templateTd = '<tr class="link-tr link-tr-tmp link-tr-{{cardinal}}" data-index="{{index}}">';
	templateTd+= '	<td class="{{link_type}}-element">';
	templateTd+= '		<input 	type="hidden"';
	templateTd+= '			class="{{form_id}}-{{link_type}}-input-linkid"';
	templateTd+= '			name="{{form_id}}-{{link_type}}-input-linkid-{{index}}-{{cardinal}}"';
	templateTd+= '			value=""';
	templateTd+= '			>';
	templateTd+= '		<select';
	templateTd+= '			id="{{form_id}}-{{link_type}}-select-element-{{index}}-{{cardinal}}"';
	templateTd+= '			name="{{form_id}}-{{link_type}}-select-element-{{index}}-{{cardinal}}"';
	templateTd+= '			class="sumo-select {{form_id}}-{{link_type}}-select-element"';
	templateTd+= '			>{{options_elements_list}}';
	templateTd+= '		</select>';
	templateTd+= '	</td>';
	templateTd+= '	<td class="{{link_type}}-condition">';
	templateTd+= '		<textarea';
	templateTd+= '				row="30" col="2"';
	templateTd+= '				class="{{form_id}}-{{link_type}}-textarea-condition"';
	templateTd+= '				name="{{form_id}}-{{link_type}}-textarea-condition-{{index}}-{{cardinal}}"';
	templateTd+= '				></textarea>';
	templateTd+= '	</td>';
	templateTd+= '	<td class="{{link_type}}-remove">';
	templateTd+= '		<button type="button"';
	templateTd+= '		        class="button button-remove"';
	templateTd+= '				onclick="removeTrLink(\'{{link_type}}\',\'{{form_id}}\',\'{{cardinal}}\', {{index}});"';
	templateTd+= '				>';
	templateTd+= '		Remove</button>';
	templateTd+= '	</td>';
	templateTd+= '</tr>';

	options_elements_list = $('#all-option-elements').html();

	templateTd = templateTd.replace(new RegExp('\{\{index\}\}', 'g'), index);
	templateTd = templateTd.replace(new RegExp('\{\{form_id\}\}', 'g'), form_id);
	templateTd = templateTd.replace(new RegExp('\{\{link_type\}\}', 'g'), link_type);
	templateTd = templateTd.replace(new RegExp('\{\{cardinal\}\}', 'g'), cardinal);
	templateTd = templateTd.replace(new RegExp('\{\{options_elements_list\}\}', 'g'), options_elements_list);

	$(templateTd).insertBefore('#'+form_id+'-'+link_type+' .form-table-link-'+cardinal+' tbody tr:last');

	$('#'+form_id+'-'+link_type+'-select-element-'+index+'-'+cardinal).SumoSelect();

	updateLinkCounter(form_id, link_type, cardinal);
}

/**
 * Close the edit modal for the specified from
 * @param  {string} form_id
 */
function closeModalEditElement(form_id){

	//Delete new tr
	$('.link-tr-tmp').remove()


	//Updating link count
	
	//Need-many
	link_type = 'need';
	cardinal = 'many';
	updateLinkCounter(form_id, link_type, cardinal)
	
	//Allow-many
	link_type = 'allow';
	cardinal = 'many';
	updateLinkCounter(form_id, link_type, cardinal)
	
	//extend-one
	link_type = 'extend';
	cardinal = 'one';
	updateLinkCounter(form_id, link_type, cardinal)
	
	//extend-many
	link_type = 'extend';
	cardinal = 'many';
	updateLinkCounter(form_id, link_type, cardinal)
	
	//evolve-one
	link_type = 'evolve';
	cardinal = 'one';
	updateLinkCounter(form_id, link_type, cardinal)
	
	//evolve-many
	link_type = 'evolve';
	cardinal = 'many';
	updateLinkCounter(form_id, link_type, cardinal)

	//Close modal
	$('#'+form_id).bPopup().close();
}

/**
 * Remove a tr link with a specified index
 * @param  {string} link_type                  need|allow|extend|evolve
 * @param  {string} form_id    
 * @param  {string} cardianl                   many|one
 * @param  {int}    index           
 */
function removeTrLink(link_type, form_id, cardinal, index){
	//Remove tr
	$('#'+form_id+'-'+link_type+' .link-tr-'+cardinal+'[data-index='+index+']').remove();

	updateLinkCounter(form_id, link_type, cardinal);
}


/**
 * Update link counter for a specified form_id, link_type and cardinal. If
 * cardinal is 'one', show/hide the add button according to counter
 * @param  {string} form_id   
 * @param  {string} link_type 
 * @param  {string} cardinal 
 */
function updateLinkCounter(form_id, link_type, cardinal){
	count = $('#'+form_id+'-'+link_type+' .form-table-link-'+cardinal+' .link-tr').length;
	$('a[href="#'+form_id+'-'+link_type+'"] span.link-counter-'+cardinal).html(count);

	if(cardinal == 'one' && count == 1){
		$('#'+form_id+'-'+link_type+' .form-table-link-'+cardinal+' td.button-td').parent().hide();
	}
	else{
		$('#'+form_id+'-'+link_type+' .form-table-link-'+cardinal+' td.button-td').parent().show();
	}
}