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
				$elements = ElementDao::getAll();
				$categories = CategoryDao::getAll();

				foreach ($elements as $element_index => $element) {
				?>
					<tr>
						<td class="elements-list-td need-td">
							<!-- Need -->
							<ul>
								<?php
									foreach ($element->getNeed() as $need_index => $need) {
										$target = $need->getTarget();
									?>
										<li id="<?php echo "element-".$element->trimedName()."-need-".$target->trimedName();?>"											
											>
											<a 	href="#element-<?php echo $target->trimedName();?>"
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
							<div class="name">
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
								<?php echo $element->getDescription();?>
							</div>
						</td>

						<td class="elements-list-td allow-td">
							<!-- Allow -->
							<ul>
								<?php
									foreach ($element->getAllow() as $allow_index => $allow) {
										$target = $allow->getTarget();
									?>
										<li id="<?php echo "element-".$element->trimedName()."allow-".$allow->getTarget()->trimedName();?>"
											>
											<a href="#element-<?php echo $target->trimedName();?>"
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
							<button type="button" onclick="$('#edit-element-<?php echo $element->trimedName();?>').bPopup();">Edit</button><br>
							<button type="button" onclick="">Delete</button>
							<?php printModalEditElement($element, $categories, $elements);?>
						</td>
					</tr>
				<?php
				}
			?>
		<tbody>
	</table>
	<?php
		//Modal for new Element
		printModalEditElement(null, $categories, $elements);
	?>
</div>
<script type="text/javascript">
	$(function(){
		elements_option_list = "<?php echo getOptionElements($elements, null, null);?>";		
		$('.sumo-select').SumoSelect();
		$('.modal-edit-element .form-content').tabs({
			heightStyle: 'auto',
		});
	});
</script>

<?php

	/**
	 * Print a form for editing a specified element or creating a new element
	 * @param  Element $e          
	 * @param  array $categories  Array of all categories
	 * @param  array $elements    Array of all elements (for the links)
	 */
	function printModalEditElement(Element $e = null, $categories, $elements){
		//$e is an element but can be null when this function is used to print the modal for creating a new Element
		
		//Id of the form, use for input identifier and tabs
		$form_id = "edit-element-".($e ? $e->trimedName() : "new");
		$inputs_id = ($e ? $e->trimedName() : "new");
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
					<ul class="">
						<li><a href="#<?php echo $form_id;?>-info">Infos</a></li>
						<li><a href="#<?php echo $form_id;?>-need">Need</a></li>
						<li><a href="#<?php echo $form_id;?>-allow">Allow</a></li>
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
										name="element-category-<?php echo ($e ? $e->trimedName() : "new");?>"
										class="sumo-select"
										>
										<?php echo getOptionCategoryForElement($categories, $e);?>
									</select>
								</td>
							</tr>
						</table>
					</div>

					<!-- Need -->
					<?php printDivLink($form_id, "need", ($e ? $e->getNeed() : null), $elements, $e);?>

					<!-- Allow -->
					<?php printDivLink($form_id, "allow", ($e ? $e->getAllow() : null), $elements, $e);?>

				</div>
				<div class="form-button">
					<button type="button" onclick="saveElement('<?php echo $form_id;?>')">Save</button>
					<button type="button" onclick="$('#edit-element-<?php echo ($e ? $e->trimedName() : "new");?>').bPopup().close()">Cancel</button>
				</div>
			</form>
		<?php
	}

	/**
	 * Print a div int the jquery-ui.tabs to edit the specified link
	 * @param  string $form_id           Id of the form to insert this div
	 * @param  string $link_type         Type of the link (allow|need|extends|extendedby)
	 * @param  array $link_from_element  Array of link of the specified type for the element
	 * @param  array $elements           Array of all element
	 * @param  Element $e                optionnal element
	 */
	function printDivLink($form_id, $link_type, $link_from_element, $elements,Element $e = null){
		$form_id_link = $form_id."-".$link_type;
		?>
			<div id="<?php echo $form_id_link;?>">
				<table class="form-table from-table-link">
					<thead>
						<th>Element</th>
						<th>Condition</th>
						<th></th>
					</thead>
					<tbody>
						<?php
							if($link_from_element){
								foreach ($link_from_element as $index => $link) {
									?>
										<tr class="link-tr" data-index="<?php echo $index;?>">
											<td class="link-element">
												<input 	type="hidden"
														class="<?php echo $form_id_link;?>-input-linkid"
														name="<?php echo $form_id_link;?>-input-linkid-<?php echo $index;?>"
														value="<?php echo $link->getLinkId()?>"
														>
												<select
														id="<?php echo $form_id_link;?>-select-element-<?php echo $index;?>"
														name="<?php echo $form_id_link;?>-select-element-<?php echo $index;?>"
														class="sumo-select <?php echo $form_id_link;?>-select-element"
														>
														<?php echo getOptionElements($elements, $link->getTarget(), $e);?>
													</select>
											</td>
											<td class="link-condition">
												<textarea 	
														row="30" col="2"
														class="<?php echo $form_id_link;?>-textarea-condition"
														name="<?php echo $form_id_link;?>-textarea-condition-<?php echo $index;?>"
														><?php echo $link->getConditions();?></textarea>
											</td>
											<td class="link-remove">
												<button type="button"
														onclick="$(this).parent().parent().remove();"
														>
												Remove</button>
											</td>
										</tr>
									<?php
								}
							}
						?>

						<tr>
							<td class="button-td" colspan="3">
								<button type="button" onclick="addLink('<?php echo $link_type;?>','<?php echo $form_id;?>',window.elements_option_list)" >Add <?php echo $link_type;?></button>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
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
	 * @param  array  $elements
	 * @param  Element $target     
	 * @param  Element $e       
	 */
	function getOptionElements($elements, Element $target = null, Element $e = null){
		$options = "";
		foreach ($elements as $element) {
			//If there is $e specified, don't output an option for him
			if(!$e || $e != $element){
				$option = "<option value='".$element->getName()."'";
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