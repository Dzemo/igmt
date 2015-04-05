<?php	
	$allElements = ElementDao::getAll();
        $allCostScaling = CostScalingDao::getAll();
	$categories = CategoryDao::getAll();

	if(isset($_GET['category_id']) && filter_var($_GET['category_id'], FILTER_VALIDATE_INT)){
		$selectedCategory = intval($_GET['category_id']);
	}
	else{
		$selectedCategory = -1;
	}
?>

<div id="elements-list">

	<div id="elements-list-menu">
		<div class="menu-item">
			<label for="category-filter">Show</label>
			<select
				id="category-filter"
				name="category-filter"
				class="sumo-select"
				>
				<option value="all" <?php if($selectedCategory == -1) echo "selected='selected'";?>>All (<?php echo count($allElements);?>)</option>
				<?php 
					//count the number of element for each category
					$categoryCount = array();
					foreach ($allElements as $element) {
						$categoryId = $element->getCategory()->getId();
						if(array_key_exists($categoryId, $categoryCount)){
							$categoryCount[$categoryId]++;
						}
						else{
							$categoryCount[$categoryId]=1;
						}
					}

					//echo option for each category

					foreach ($categories as $category) {
						$countCategory = 0;
						if(array_key_exists($category->getId(), $categoryCount)){
							$countCategory = $categoryCount[$category->getId()];
						}

						$option = "<option value='".$category->cssClassName()."'";
						$option.= " data-sumo-class='".$category->cssClassName()."'";
						if($selectedCategory == $category->getId()) 
							$option.= "selected='selected'";
						$option .= ">".$category->getName()." (".$countCategory.")</option><br>";

						echo $option;
					}
				?>
			</select>
		</div>
		<div class="menu-item">
			<button type="button" class="button button-add" onclick="$('#edit-element-new').bPopup()">Add element</button>
		</div>
	</div>

	<table id="elements-list-table">
		<thead>
			<th class="elements-list-th need-th"></th>
			<th class="elements-list-th element-th">Element</th>
			<th class="elements-list-th allow-th"></th>
			<th class="elements-list-th buttons-th"></th>
		</thead>
		<tbody>
			<?php
				foreach ($allElements as $element_index => $element) {
                                    //Hack pour ne pas afficher le temps
                                    if($element->getName() == "Time")
                                        continue;
				?>
					<tr class="element-tr category-<?php echo $element->getCategory()->cssClassName();?>" id="element-<?php echo $element->getId();?>">
						
						<td class="elements-list-td from-td">
							<ul>
								<?php
									printInnerLinks($element, $element->getNeed(), "Need");
									printInnerLinks($element, array($element->getExtend()), "Extend");
									printInnerLinks($element, array($element->getRegress()), "Evolve from");	
								?>
							</ul>
						</td>

						<td class="elements-list-td element-td">
							<!-- Element -->
                                                        
                                                        <!-- Costs -->
                                                        <?php if($element->hasCost()){ ?>
                                                            <div class="costs">Cost: 
                                                                <?php
                                                                    $first = true;
                                                                    foreach($element->getCosts() as $cost){
                                                                        if(!$first) echo ", ";

                                                                        printBaliseAForElement($cost->getElementToPay());
                                                                        echo " (".$cost->getBaseQuantity().")";
                                                                    }
                                                                    ?>
                                                            </div>
                                                        <?php } ?>
                                                        
							<!-- Infos : Name, description, tags -->
							<div class="infos">
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
						</td>

						<td class="elements-list-td to-td">
							<ul>
								<?php
									printInnerLinks($element, $element->getAllow(), "Allow");
									printInnerLinks($element, $element->getExtendedBy(), "Extended by");
									printInnerLinks($element, $element->getEvolve(), "Evolve into");
								?>
							</ul>
						</td>

						<td class="elements-list-td buttons-td">
							<!-- buttons -->
							<button class="button button-edit" type="button" onclick="$('#edit-element-<?php echo $element->getId();?>').bPopup();">Edit</button><br>
							<button class="button button-delete" type="button" onclick="$('#delete-element-<?php echo $element->getId();?>').bPopup();">Delete</button>
							<?php 
								printModalEditElement($element, $categories, $allElements, $allCostScaling);
								printModalDeleteElement($element);
							?>
						</td>
					</tr>
				<?php
				}
			?>
		<tbody>
	</table>
	<?php
		//Modal for new Element
		printModalEditElement(null, $categories, $allElements, $allCostScaling);
	?>
</div>
<div id="all-option-elements" style="display:none">
	<?php echo getOptionElements($allElements, null, null);?>
</div>
<div id="all-option-cost-scaling" style="display:none">
	<?php echo getOptionCostScalling($allCostScaling, null);?>
</div>
<script type="text/javascript" language="javascript" src="js/elements_list.js"></script>

<?php
	/**
	 * Print specified link with label for an element
	 * @param  Element $element    
	 * @param  array  $innerLinks Array of Innerlink
	 * @param  string  $label 
	 */
	function printInnerLinks(Element $element, $innerLinks, $label){
		foreach ($innerLinks as $innerLink) {
			if($innerLink){
				$target = $innerLink->getTarget();
				?>
					<li id="<?php echo "element-".$element->getId()."allow-".$target->getId();?>"
						><?php 
                                                        echo "$label: ";
                                                        
                                                        printBaliseAForElement($target);
						
							if($innerLink->hasConditions())
							{
								?>
									<br><span class="condition"><?php echo $innerLink->getConditions();?></span>
								<?php
							}
						?>
					</li>
				<?php
			}
		}
	}
        
        /**
         * Print a link a for an element with his name for content and his catogery css class
         * @param Element $element
         */
        function printBaliseAForElement(Element $element){
            ?>
                <a href="#element-<?php echo $element->getId();?>"
		   class="toolip">
                        <span <?php echo $element->getCategory()->cssHTML();?>>
                                <?php echo $element->getName();?>
                        </span>									
                </a>
            <?php
        }

	/**
	 * Print a modal to confirm the deletion of an Elemement
	 * @param  Element $e 
	 */
	function printModalDeleteElement(Element $e){
		$form_id = "delete-element-".$e->getId();
		?>
			<form id="<?php echo $form_id;?>"
				 class="modal modal-delete-element"
				 >

				<div class="form-title">
					Delete element <?php echo "<span".$e->getCategory()->cssHTML().">".$e->getName()."</span>";?> ?
				</div>

				<div class="form-content">
					<input 	
						type="hidden"
						id="<?php echo $form_id;?>-input-id"
						name="<?php echo $form_id;?>-input-id"
						value="<?php echo $e->getId();?>"
						>
						<input 
							type="hidden"
							id="<?php echo $form_id;?>-input-name"
							name="element-name"
							value="<?php echo $e->getName();?>"
							/>
					<table >
						<tr>
							<td>Category</td>
							<td><?php echo $e->getCategory()->getName();?></td>
						</tr>
						<tr>
							<td>Description</td>
							<td><?php echo $e->getDescription();?></td>
						</tr>
						<?php 
							if($e->hasNeed()){
								$stringNeed = "[";
								foreach ($e->getNeed() as $link) {
									if($stringNeed != "[")
										$stringNeed.= ", ";
									$stringNeed.= "<span ".$link->getTarget()->getCategory()->cssHTML().">".$link->getTarget()->getName()."</span>";
								}
								$stringNeed .= "]";
								?>
									<tr>
										<td>Need</td>
										<td><?php echo $stringNeed;?></td>
									</tr>
								<?php
							}
						?>
						<?php 
							if($e->hasAllowing()){
								$stringAllow = "[";
								foreach ($e->getAllow() as $link) {
									if($stringAllow != "[")
										$stringAllow.= ", ";
									$stringAllow.= "<span ".$link->getTarget()->getCategory()->cssHTML().">".$link->getTarget()->getName()."</span>";
								}
								$stringAllow .= "]";
								?>
									<tr>
										<td>Allow</td>
										<td><?php echo $stringAllow;?></td>
									</tr>
								<?php
							}
						?>
						<?php 
							if($e->isExtending()){						
								$stringExtend = "[<span ".$e->getExtend()->getTarget()->getCategory()->cssHTML().">".$e->getExtend()->getTarget()->getName()."</span>]";
								?>
									<tr>
										<td>Extend</td>
										<td><?php echo $stringExtend;?></td>
									</tr>
								<?php
							}
						?>
						<?php 
							if($e->hasExtension()){
								$stringExtendedBy = "[";
								foreach ($e->getExtendedBy() as $link) {
									if($stringExtendedBy != "[")
										$stringExtendedBy.= ", ";
									$stringExtendedBy.= "<span ".$link->getTarget()->getCategory()->cssHTML().">".$link->getTarget()->getName()."</span>";
								}
								$stringExtendedBy .= "]";
								?>
									<tr>
										<td>Extended by</td>
										<td><?php echo $stringExtendedBy;?></td>
									</tr>
								<?php
							}
						?>
						<?php 
							if($e->isEvolveing()){				
								$stringRegress = "[<span ".$e->getRegress()->getTarget()->getCategory()->cssHTML().">".$e->getRegress()->getTarget()->getName()."</span>]";
								?>
									<tr>
										<td>Evolve from</td>
										<td><?php echo $stringRegress;?></td>
									</tr>
								<?php
							}
						?>
						<?php 
							if($e->hasEvolution()){
								$stringEvolve = "[";
								foreach ($e->getEvolve() as $link) {
									if($stringEvolve != "[")
										$stringEvolve.= ", ";
									$stringEvolve.= "<span ".$link->getTarget()->getCategory()->cssHTML().">".$link->getTarget()->getName()."</span>";
								}
								$stringEvolve .= "]";
								?>
									<tr>
										<td>Evolve into</td>
										<td><?php echo $stringEvolve;?></td>
									</tr>
								<?php
							}
						?>
					</table>
				</div>

				<div class="form-button">
					<button type="button" data-form-id="<?php echo $form_id;?>" class="button button-confirm button-delete-element">Delete</button>
					<button type="button" class="button button-cancel" onclick="$('#<?php echo $form_id;?>').bPopup().close()">Cancel</button>
				</div>
			</form>
		<?php
	}

	/**
	 * Print a form for editing a specified element or creating a new element
	 * @param  Element $e          
	 * @param  array $categories  Array of all categories
	 * @param  array $allElements    Array of all elements (for the links)
	 * @param  array $allCostScaling
	 */
	function printModalEditElement(Element $e = null, $categories, $allElements, $allCostScaling){
		//$e is an element but can be null when this function is used to print the modal for creating a new Element
		
		//Id of the form, use for input identifier and tabs
		$form_id = "edit-element-".($e ? $e->getId() : "new");
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
                                                
                                                <!-- Costs -->
						<li>
                                                        <a href="#<?php echo $form_id;?>-costs">
                                                            Costs (<span class="link-counter-costs"><?php echo ($e ? count($e->getCosts()) : '0');?></span>)                                                        
                                                        </a>
                                                </li>

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

                                        <!-- Cost -->
                                        <?php $form_id_link = $form_id."-costs"; ?>
                                        <div id="<?php echo $form_id_link;?>" class="form-content-link">
                                            <div class="form-subtitle">
                                                    Costs
                                            </div>			
                                            <table class="form-table form-table-link-costs">
                                                <thead>
                                                    <th>Element</th>
                                                    <th>Scaling</th>
                                                    <th>Base quantity</th>
                                                    <th></th>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        if($e != null && $e->getCosts()){
                                                            foreach ($e->getCosts() as $index => $cost) {
                                                                ?>
                                                                    <tr class="link-tr link-tr-costs" data-index="<?php echo $index;?>">
                                                                        <td class="costs-element">
                                                                            <input
                                                                                type="hidden"
                                                                                class="<?php echo $form_id_link;?>-input-costid"
                                                                                name="<?php echo $form_id_link;?>-input-linkid-<?php echo $index;?>-costs"
                                                                                value="<?php echo $cost->getId();?>"
                                                                                />
                                                                            <select
                                                                                id="<?php echo $form_id_link;?>-select-element-<?php echo $index;?>-costs"
                                                                                name="<?php echo $form_id_link;?>-select-element-<?php echo $index;?>-costs"
                                                                                class="sumo-select <?php echo $form_id_link;?>-select-element"
                                                                                >
                                                                                <?php echo getOptionElements($allElements, $cost->getElementToPay(), $e); ?>
                                                                            </select>
                                                                        </td>
                                                                        <td class="costs-scaling">
                                                                                <select
                                                                                    id="<?php echo $form_id_link;?>-select-scaling-<?php echo $index;?>-costs"
                                                                                    name="<?php echo $form_id_link;?>-select-scaling-<?php echo $index;?>-costs"
                                                                                    class="sumo-select <?php echo $form_id_link;?>-select-scaling"
                                                                                >
                                                                                <?php echo getOptionCostScalling($allCostScaling, $cost->getScaling()); ?>
                                                                            </select>
                                                                        </td>
                                                                        <td class="costs-base-quantity">
                                                                                <input 	
                                                                                        type="text"
                                                                                        class="<?php echo $form_id_link;?>-input-base-quantity"
                                                                                        name="<?php echo $form_id_link;?>-input-base-quantity-<?php echo $index;?>-costs"
                                                                                        value="<?php echo $cost->getBaseQuantity();?>"
                                                                                                />
                                                                        </td>
                                                                        <td class="costs-remove">
                                                                                <button type="button"
                                                                                                class="button button-remove"
                                                                                                onclick="removeTrLink('costs','<?php echo $form_id;?>', 'costs', <?php echo $index;?>);"
                                                                                                >
                                                                                Remove</button>
                                                                        </td>
                                                                    </tr>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                                        
                                                        <tr>
                                                            <td class="button-td" colspan="3" >
                                                                <button type="button" class="button button-add" onclick="addTrLink('costs','<?php echo $form_id;?>', 'costs')" >Add cost</button>
                                                            </td>
                                                        </tr>
                                                </tbody>
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
					<button type="button" data-form-id="<?php echo $form_id;?>" class="button button-confirm button-save-element">Save</button>
					<button type="button" class="button button-cancel" onclick="closeModalEditElement('<?php echo $form_id;?>')">Cancel</button>
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
													class="button button-remove"
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
							<button type="button" class="button button-add" onclick="addTrLink('<?php echo $link_type;?>','<?php echo $form_id;?>', '<?php echo $cardinal;?>')" >Add <?php echo $label;?></button>
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
			$option.= " data-sumo-class='".$category->cssClassName()."'";

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
				$option.= " data-sumo-class='".$element->getCategory()->cssClassName()."'";

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

	/**
	 * Return the option list for all CostScaling
	 * If a $currentScaling is specified, select this CostScaling
	 * @param  array  $allCostScalling
	 * @param  CostScaling $currentScaling     
	 */
	function getOptionCostScalling($allCostScalling, CostScaling $currentScaling = null){
		$options = "";
		foreach ($allCostScalling as $costScalling) {			
                        $option = "<option value='".$costScalling->getId()."'";

                        if($currentScaling && $currentScaling->getId() == $costScalling->getId()){
                                $option .= " selected='selected'";
                        }

                        $option .= ">".$costScalling->getName()."</option><br>";
                        $options .= $option;			
		}
		if(!$currentScaling){
			//Add empty option if there is no $e specified
			$options .= "<option selected='selected' value=''></option>";
		}

		return $options;
	}
?>