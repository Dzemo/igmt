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
 * @param  {string} options_elements_list 
 */
function addTrLink(link_type, form_id, cardinal, options_elements_list){

	var index = 0;
	$('#'+form_id+'-'+link_type+' table tbody tr').each(function(){
		if($(this).data('index') > index)
			index = $(this).data('index');
	});
	index++;

	templateTd = '<tr class="link-tr link-tr-{{cardinal}}" data-index="{{index}}">';
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
	templateTd+= '				onclick="removeTrLink(\'{{link_type}}\',\'{{form_id}}\',\'{{cardinal}}\', {{index}});"';
	templateTd+= '				>';
	templateTd+= '		Remove</button>';
	templateTd+= '	</td>';
	templateTd+= '</tr>';

	templateTd = templateTd.replace(new RegExp('\{\{index\}\}', 'g'), index);
	templateTd = templateTd.replace(new RegExp('\{\{form_id\}\}', 'g'), form_id);
	templateTd = templateTd.replace(new RegExp('\{\{link_type\}\}', 'g'), link_type);
	templateTd = templateTd.replace(new RegExp('\{\{cardinal\}\}', 'g'), cardinal);
	templateTd = templateTd.replace(new RegExp('\{\{options_elements_list\}\}', 'g'), options_elements_list);

	$(templateTd).insertBefore('#'+form_id+'-'+link_type+' .form-table-link-'+cardinal+' tbody tr:last');

	$('#'+form_id+'-'+link_type+'-select-element-'+index+'-'+cardinal).SumoSelect();

	//Updating link count
	span = $('a[href="#'+form_id+'-'+link_type+'"] span.link-counter-'+cardinal+':last');
	var counter = parseInt(span.html());
	counter = isNaN(counter) ? 0 : counter + 1;
	span.html(counter);

	//if one cardinal and counter = 1 hide add
	if(cardinal == 'one'){
		$('#'+form_id+'-'+link_type+' .form-table-link-'+cardinal+' td.button-td').parent().hide();
	}
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

	//Updating link count
	span = $('a[href="#'+form_id+'-'+link_type+'"] span.link-counter-'+cardinal+':last');
	var counter = parseInt(span.html());
	counter = isNaN(counter) ? 0 : counter - 1;
	span.html(counter);

	//if one cardinal and counter = 1 show add
	if(cardinal == 'one'){
		console.log('#'+form_id+'-'+link_type+' .form-table-link-'+cardinal+' td.button-td');
		$('#'+form_id+'-'+link_type+' .form-table-link-'+cardinal+' td.button-td').parent().show();
	}
}




