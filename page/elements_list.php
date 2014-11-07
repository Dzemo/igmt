<div id="elements-list">
	<button type="button" onclick="$('#edit-element-new').bPopup()">Add element</button>
	<table class="elements-list-table">
		<thead>
			<th class="elements-list-th need-th">Need</th>
			<th class="elements-list-th element-th">Element</th>
			<th class="elements-list-th allow-th">Allow</th>
			<th class="elements-list-th buttons-th"></th>
		</thead>
		<tbody>
			<?php
				$allElements = ElementDao::getAll();
				$categories = CategoryDao::getAll();

				foreach ($allElements as $element_index => $element) {
				?>
					<tr class="element-tr" id="element-<?php echo $element->getId();?>">
						<td class="elements-list-td need-td">
							<!-- Need -->
							<ul>
								<?php
									foreach ($element->getNeed() as $need_index => $need) {
										$target = $need->getTarget();
									?>
										<li id="<?php echo "element-".$element->getId()."-need-".$target->getId();?>"											
											>
											<a 	href="#element-<?php echo $target->getId();?>"
												class="toolip"												
												>
												<span <?php echo $target->getCategory()->cssHTML();?>>
													<?php echo $target->getName();?>
												</span>
											</a>
											<?php
												if($need->hasConditions())
												{
													?>
														<br><span class="condition"><?php echo $need->getConditions();?></span>
													<?php
												}
											?>
										</li>
									<?php
									}
								?>
							</ul>
						</td>

						<td class="elements-list-td element-td">
							<!-- Element -->
							<?php 
								$has_predecessors = $element->isEvolveing() || $element->isExtending() ? "has-predecessors" : "";
								$has_successors = $element->hasEvolution() || $element->hasExtension() ? "has-successors" : "";
							?>

							<!-- Predecessors : evolve from (regress) and extend -->
							<div class="predecessors">
								<?php
									//Evole from (regress)
									if($element->isEvolveing()){
										?>
											<div class="regress">
												Evolve from : <a href="#element-<?php echo $element->getRegress()->getTarget()->getId();?>"											
													>
													<span <?php echo  $element->getRegress()->getTarget()->getCategory()->cssHTML();?>>
														<?php echo  $element->getRegress()->getTarget()->getName();?>
													</span>													
												</a>
												<?php
													if($element->getRegress()->hasConditions()){
														?>
															(<span class="condition"><?php echo $element->getRegress()->getConditions();?></span>)
														<?php
													}
												?>
											</div>
										<?php
									}
									//Extend
									if($element->isExtending()){
										?>
											<div class="extend">
												Extends : <a href="#element-<?php echo $element->getExtend()->getTarget()->getId();?>"											
													>
													<span <?php echo  $element->getExtend()->getTarget()->getCategory()->cssHTML();?>>
														<?php echo  $element->getExtend()->getTarget()->getName();?>
													</span>													
												</a>
												<?php
													if($element->getExtend()->hasConditions()){
														?>
															(<span class="condition"><?php echo $element->getExtend()->getConditions();?></span>)
														<?php
													}
												?>
											</div>
										<?php
									}
								?>
							</div>

							<!-- Infos : Name, description, tags -->
							<div class="infos <?php echo $has_predecessors." ".$has_successors;?>">
								<div class="name">
									Name: 
									<span <?php echo $element->getCategory()->cssHTML();?>>
										<?php echo $element->getName();?>
									</span>
								</div>
								<div class="tags">
									<?php 
										if($element->hasTags()){
											echo "[";
											$first = true;
											foreach($element->getTags() as $tag){
												if($first){
													echo "$tag",
													$first = false;
												}
												else
													echo ", $tag";
											}
											echo "]";
										}
									?>
								</div>
								<div class="description">
									Description: 
									<span><?php echo $element->getDescription();?></span>
								</div>
							</div>

							<!-- Successors : evolution and extension -->
							<div class="successors">
								<?php
									//Evolve into
									if($element->hasEvolution()){
										$stringEvolve = "[";
										foreach ($element->getEvolve() as $evolve) {
											if($stringEvolve != "[") $stringEvolve .= ", ";

											$stringEvolve .= " <a href=\"#element-".$evolve->getTarget()->getId()."\">";
											$stringEvolve .= "		<span ".$evolve->getTarget()->getCategory()->cssHTML().">";
											$stringEvolve .= $evolve->getTarget()->getName();
											$stringEvolve .= " 		</span>";
											$stringEvolve .= " 	</a>";

											if($evolve->hasConditions()){
												$stringEvolve .= "(<span class=\"condition\">".$evolve->getConditions()."</span>)";
											}
										}
										$stringEvolve .= "]";
										?>
											<div class="evolve">
												Evolve into : <?php echo $stringEvolve;?>
											</div>
										<?php
									}
									//Extended by
									if($element->hasExtension()){
										$stringExtendedBy = "[";
										foreach ($element->getExtendedBy() as $extendedBy) {
											if($stringExtendedBy != "[") $stringEvolve .= ", ";

											$stringExtendedBy .= "<a href=\"#element-".$extendedBy->getTarget()->getId()."\">";
											$stringExtendedBy .= "<span ".$extendedBy->getTarget()->getCategory()->cssHTML().">";
											$stringExtendedBy .= $extendedBy->getTarget()->getName();
											$stringExtendedBy .= "</span>";
											$stringExtendedBy .= "</a>";

											if($extendedBy->hasConditions()){
												$stringExtendedBy .= "(<span class=\"condition\">".$extendedBy->getConditions()."</span>)";
											}
										}
										$stringExtendedBy .= "]";
										?>
											<div class="extendedby">
												Extended by : <?php echo $stringExtendedBy;?>
											</div>
										<?php
									}
								?>
							</div>
						</td>

						<td class="elements-list-td allow-td">
							<!-- Allow -->
							<ul>
								<?php
									foreach ($element->getAllow() as $allow_index => $allow) {
										$target = $allow->getTarget();
									?>
										<li id="<?php echo "element-".$element->getId()."allow-".$allow->getTarget()->getId();?>"
											>
											<a href="#element-<?php echo $target->getId();?>"
												class="toolip"										
												>
												<span <?php echo $target->getCategory()->cssHTML();?>>
													<?php echo $target->getName();?>
												</span>									
											</a>
											<?php
												if($allow->hasConditions())
												{
													?>
														<br><span class="condition"><?php echo $allow->getConditions();?></span>
													<?php
												}
											?>
										</li>
									<?php
									}
								?>
							</ul>
						</td>

						<td class="elements-list-td buttons-td">
							<!-- buttons -->
							<button type="button" onclick="$('#edit-element-<?php echo $element->getId();?>').bPopup();">Edit</button><br>
							<button type="button" onclick="">Delete</button>
							<?php printModalEditElement($element, $categories, $allElements);?>
						</td>
					</tr>
				<?php
				}
			?>
		<tbody>
	</table>
	<?php
		//Modal for new Element
		printModalEditElement(null, $categories, $allElements);
	?>
</div>
<script type="text/javascript" language="javascript" src="js/elements_list.js"></script>
<script type="text/javascript">
	$(function(){
		elements_option_list = "<?php echo getOptionElements($allElements, null, null);?>";		

		$('.sumo-select').SumoSelect();
		$('.modal-edit-element .form-content').tabs({
			heightStyle: 'auto',
		});

		$('.button-save').jaxButton({
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

				console.log(data);
				return data;
			},
			before: function(dataset){
				noty({text: 'Saving element '+$('#'+dataset.formId+'-input-name').val(), type:'information'});
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
								//document.location.href=json.redirect;
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
		})
	});
</script>

<?php

	/**
	 * Print a form for editing a specified element or creating a new element
	 * @param  Element $e          
	 * @param  array $categories  Array of all categories
	 * @param  array $allElements    Array of all elements (for the links)
	 */
	function printModalEditElement(Element $e = null, $categories, $allElements){
		//$e is an element but can be null when this function is used to print the modal for creating a new Element
		
		//Id of the form, use for input identifier and tabs
		$form_id = "edit-element-".($e ? $e->getId() : "new");
		$inputs_id = ($e ? $e->getId() : "new");
		?>
			<form 	id="<?php echo $form_id;?>" 
					class="modal modal-edit-element"
					>

				<div class="form-title">
					<?php
						echo ($e ? "Edit element <span".$e->getCategory()->cssHTML().">".$e->getName()."</span>" : "Create a new element");
					?>
				</div>
				<div class="form-content">	
					<!-- Tabs list-->	 
					<ul class="">
						<!-- Info -->
						<li><a href="#<?php echo $form_id;?>-info">Infos</a></li>

						<!-- Need -->
						<li>
							<a href="#<?php echo $form_id;?>-need">
								Need (<span class="link-counter-many"><?php echo ($e ? count($e->getNeed()) : '0');?></span>)
							</a>
						</li>

						<!-- Allow -->
						<li>
							<a href="#<?php echo $form_id;?>-allow">
								Allow (<span class="link-counter-many"><?php echo ($e ? count($e->getAllow()) : '0');?></span>)
							</a>
						</li>

						<!-- Extend -->
						<li>
							<a href="#<?php echo $form_id;?>-extend">
								Extension (<span class="link-counter-one"><?php echo ($e && $e->isExtending() ? '1' : '0');?></span>&rarr;<span class="link-counter-many"><?php echo ($e ? count($e->getExtendedBy()) : '0');?></span>)
							</a>
						</li>

						<!-- Evolve -->
						<li>
							<a href="#<?php echo $form_id;?>-evolve">
								Evolution (<span class="link-counter-one"><?php echo ($e && $e->isEvolveing() ? '1' : '0');?></span>&rarr;<span class="link-counter-many"><?php echo ($e ? count($e->getEvolve()) : '0');?></span>)
							</a>
						</li>
					</ul>

					<!-- Infos -->
					<div id="<?php echo $form_id;?>-info">
						<table class="form-table">
							<tr>
								<!-- Name -->
								<td class="label-td">				
									<label for="<?php echo $form_id;?>-input-name">
									Name
									</label>
								</td>
								<td class="input-td">
									<input 
										type="text"
										id="<?php echo $form_id;?>-input-name"
										name="element-name"
										value="<?php if($e) echo $e->getName();?>"
										/>
									<input 	
										type="hidden"
										id="<?php echo $form_id;?>-input-id"
										name="<?php echo $form_id;?>-input-id"
										value="<?php if($e) echo $e->getId();?>"
										>
								</td>
							</tr>
							<tr>
								<!-- Description -->
								<td class="label-td">
									<label for="<?php echo $form_id;?>-textarea-description">
										Description
									</label>
								</td>
								<td class="input-td">
									<textarea 
										row="30" col="2"
										id="<?php echo $form_id;?>-textarea-description"
										name="element-description"
										><?php if($e) echo $e->getDescription();?></textarea>
								</td>
							</tr>
							<tr>
								<!-- Category -->
								<td class="label-td">
									<label for="<?php echo $form_id;?>-select-category">
										Category
									</label>						
								</td>
								<td class="input-td">
									<select
										id="<?php echo $form_id;?>-select-category"
										name="element-category-<?php echo ($e ? $e->getId() : "new");?>"
										class="sumo-select"
										>
										<?php echo getOptionCategoryForElement($categories, $e);?>
									</select>
								</td>
							</tr>
						</table>
					</div>

					<!-- Need -->
					<?php printDivLink(
										$form_id, 
										"need", 
										null, 
										($e ? $e->getNeed() : null), 
										array('many' => 'Need'), 
										$allElements, 
										$e
									);?>

					<!-- Allow -->
					<?php printDivLink(
										$form_id, 
										"allow", 
										null, 
										($e ? $e->getAllow() : null), 
										array('many' => 'Allow'), 
										$allElements, 
										$e
									);?>

					<!-- Extend -->
					<?php printDivLink(
										$form_id, 
										"extend", 
										($e ? $e->getExtend() : null), 
										($e ? $e->getExtendedBy() : null), 
										array('one' => 'Extend', 'many' => 'Extended by'), 
										$allElements, 
										$e
									);?>

					<!-- Evolve -->
					<?php printDivLink(
										$form_id, 
										"evolve", 
										($e ? $e->getRegress() : null), 
										($e ? $e->getEvolve() : null), 
										array('one' => 'Evolve from', 'many' => 'Evolve into'), 
										$allElements, 
										$e
									);?>


				</div>
				<div class="form-button">
					<button type="button" data-form-id="<?php echo $form_id;?>" class="button-save">Save</button>
					<button type="button" class="button-cancel" onclick="$('#edit-element-<?php echo ($e ? $e->getId() : "new");?>').bPopup().close()">Cancel</button>
				</div>
			</form>
		<?php
	}

	/**
	 * Print a div into the jquery-ui.tabs to edit the specified link simple (need or allow)
	 * @param  string $form_id           			Id of the form to insert this div
	 * @param  string $link_type         			Type of the link (allow|need|extension|evolution)
	 * @param  InnerLink $one_link_from_element  	Array of link of the specified type for the element when this link have one to many relation (extension and evolution have)
	 * @param  array $many_links_from_element 		Array of link of the specified type for the element when this link have one to many relation (allow, need, extension and evolution have)
	 * @param  array $labels						Array ('one', 'many') containing the label for relation one and many. If the corresponding label is not null, then the table for this relation will be print
	 * @param  array $allElements           		Array of all element for select options
	 * @param  Element $e                			optionnal current element for the form element
	 */
	function printDivLink($form_id, $link_type, $one_link_from_element, $many_links_from_element, $labels, $allElements, Element $e = null){
		$form_id_link = $form_id."-".$link_type;
		?>
			<div id="<?php echo $form_id_link;?>" class="form-content-link">

				<!-- One -->
				<?php  
					if(isset($labels['one'])){
						$links_from_element = $one_link_from_element ? array($one_link_from_element) : array();
						printFormForCardinal($form_id, $link_type, $links_from_element, $labels['one'], 'one', $allElements, $e);
					}
				?>

				<!-- Many -->
				<?php  
					if(isset($labels['many'])){
						printFormForCardinal($form_id, $link_type, $many_links_from_element, $labels['many'], 'many', $allElements, $e);
					}
				?>
			</div>
		<?php
	}

	/**
	 * Print a print the subtitle and the table form for a specified cardinality of a link
	 * @param  string $form_id           			Id of the form to insert this div
	 * @param  string $link_type         			Type of the link (allow|need|extension|evolution)
	 * @param  array $links_from_element 			Array of link of the specified type for the element. When this link have one to many relation (allow, need, extension and evolution have) the array may conain many element but for one to many relation the array contain the only element (or empty array if no element)
	 * @param  array $labels						Label for the cardinality
	 * @param  string $cardinal
	 * @param  array $allElements           		Array of all element for select options
	 * @param  Element $e                			optionnal current element for the form element
	 */
	function printFormForCardinal($form_id, $link_type, $links_from_element, $label, $cardinal, $allElements, Element $e = null){
		$form_id_link = $form_id."-".$link_type;
		?>
			<div class="form-subtitle">
				<?php echo $label;?>
			</div>			
			<table class="form-table form-table-link-<?php echo $cardinal;?>">
				<thead>
					<th>Element</th>
					<th>Condition</th>
					<th></th>
				</thead>
				<tbody>
					<?php
						if($links_from_element){
							foreach ($links_from_element as $index => $link) {
								?>
									<tr class="link-tr link-tr-<?php echo $cardinal;?>" data-index="<?php echo $index;?>">
										<td class="link-element">
											<input 	type="hidden"
													class="<?php echo $form_id_link;?>-input-linkid"
													name="<?php echo $form_id_link;?>-input-linkid-<?php echo $index;?>-<?php echo $cardinal;?>"
													value="<?php echo $link->getLinkId()?>"
													>
											<select
													id="<?php echo $form_id_link;?>-select-element-<?php echo $index;?>-<?php echo $cardinal;?>"
													name="<?php echo $form_id_link;?>-select-element-<?php echo $index;?>-<?php echo $cardinal;?>"
													class="sumo-select <?php echo $form_id_link;?>-select-element"
													>
													<?php echo getOptionElements($allElements, $link->getTarget(), $e);?>
												</select>
										</td>
										<td class="link-condition">
											<textarea 	
													
													class="<?php echo $form_id_link;?>-textarea-condition"
													name="<?php echo $form_id_link;?>-textarea-condition-<?php echo $index;?>-<?php echo $cardinal;?>"
													><?php echo $link->getConditions();?></textarea>
										</td>
										<td class="link-remove">
											<button type="button"
													onclick="removeTrLink('<?php echo $link_type;?>','<?php echo $form_id;?>', '<?php echo $cardinal;?>', <?php echo $index;?>);"
													>
											Remove</button>
										</td>
									</tr>
								<?php
							}
						}

					$style_button_tr = "";
					if($cardinal == "one" && count($links_from_element) >= 1){
						$style_button_tr = "style='display:none'";
					}
					?>
					<tr>
						<td class="button-td" colspan="3" <?php echo $style_button_tr;?>>
							<button type="button" onclick="addTrLink('<?php echo $link_type;?>','<?php echo $form_id;?>', '<?php echo $cardinal;?>',window.elements_option_list)" >Add <?php echo $label;?></button>
						</td>
					</tr>
				</tbody>
			</table>
		<?php
	}

	/**
	 * Return the option list for categories
	 * If an Element is specified, select his category
	 * @param  array  $categories 
	 * @param  Element $e       
	 */
	function getOptionCategoryForElement($categories, Element $e = null){
		$options = "";
		foreach ($categories as $category) {
			$option = "<option value='".$category->getName()."'";
			$option.= " data-sumo-class='".$category->trimedName()."'";

			if($e && $e->getCategory() == $category){
				$option .= " selected='selected'";
			}

			$option .= ">".$category->getName()."</option><br>";
			$options .= $option;
		}

		return $options;
	}

	/**
	 * Return the option list for all elements
	 * If a $target is specified, select this element
	 * If an $e is specified, don't output the option for this element
	 * @param  array  $allElements
	 * @param  Element $target     
	 * @param  Element $e       
	 */
	function getOptionElements($allElements, Element $target = null, Element $e = null){
		$options = "";
		foreach ($allElements as $element) {
			//If there is $e specified, don't output an option for him
			if(!$e || $e != $element){
				$option = "<option value='".$element->getId()."'";
				$option.= " data-sumo-class='".$element->getCategory()->trimedName()."'";

				if($target && $target == $element){
					$option .= " selected='selected'";
				}

				$option .= ">".$element->getName()."</option><br>";
				$options .= $option;
			}
		}
		if(!$e){
			//Add empty option if there is no $e specified
			$options .= "<option selected='selected' value=''></option>";
		}

		return $options;
	}
?>