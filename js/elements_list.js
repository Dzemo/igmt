/**
 * Retrive post data for a link type
 * @param  {string} form_id   
 * @param  {string} link_type 
 * @return {array}           
 */
function getPostArrayForLink(form_id, link_type){
	link_array = [];
	$('#'+form_id+'-'+link_type+' .link-tr').each(function(){
		link_array.push({
			link_id: $('.'+form_id+'-'+link_type+'-input-linkid',this).val(),
			target_name: $('.'+form_id+'-'+link_type+'-select-element',this).val(),
			conditions: $('.'+form_id+'-'+link_type+'-textarea-condition',this).val()
		});
	});
	return link_array;
}

/**
 * Add a tr with select for element and texterea for conditons to add a link
 * @param  {string} link                  need|allow|extend|extendedBy
 * @param  {string} form_id               
 * @param  {string} options_elements_list 
 */
function addLink(link, form_id, options_elements_list){

	var index = 0;
	$('#'+form_id+'-'+link+' table tbody tr').each(function(){
		if($(this).data('index') > index)
			index = $(this).data('index');
	});
	index++;

	templateTd = '<tr class="link-tr" data-index="{{index}}">';
	templateTd+= '	<td class="{{link}}-element">';
	templateTd+= '		<input 	type="hidden"';
	templateTd+= '			class="{{form_id}}-{{link}}-input-linkid"';
	templateTd+= '			name="{{form_id}}-{{link}}-input-linkid-{{index}}"';
	templateTd+= '			value=""';
	templateTd+= '			>';
	templateTd+= '		<select';
	templateTd+= '			id="{{form_id}}-{{link}}-select-element-{{index}}"';
	templateTd+= '			name="{{form_id}}-{{link}}-select-element-{{index}}"';
	templateTd+= '			class="sumo-select {{form_id}}-{{link}}-select-element"';
	templateTd+= '			>{{options_elements_list}}';
	templateTd+= '		</select>';
	templateTd+= '	</td>';
	templateTd+= '	<td class="{{link}}-condition">';
	templateTd+= '		<textarea';
	templateTd+= '				row="30" col="2"';
	templateTd+= '				class="{{form_id}}-{{link}}-textarea-condition"';
	templateTd+= '				name="{{form_id}}-{{link}}-textarea-condition-{{index}}"';
	templateTd+= '				></textarea>';
	templateTd+= '	</td>';
	templateTd+= '	<td class="{{link}}-remove">';
	templateTd+= '		<button type="button"';
	templateTd+= '				onclick="$(this).parent().parent().remove();"';
	templateTd+= '				>';
	templateTd+= '		Remove</button>';
	templateTd+= '	</td>';
	templateTd+= '</tr>';

	templateTd = templateTd.replace(new RegExp('\{\{index\}\}', 'g'), index);
	templateTd = templateTd.replace(new RegExp('\{\{form_id\}\}', 'g'), form_id);
	templateTd = templateTd.replace(new RegExp('\{\{link\}\}', 'g'), link);
	templateTd = templateTd.replace(new RegExp('\{\{options_elements_list\}\}', 'g'), options_elements_list);

	$(templateTd).insertBefore('#'+form_id+'-'+link+' table tbody tr:last');
	$('#'+form_id+'-'+link+'-select-element-'+index).SumoSelect();
}




