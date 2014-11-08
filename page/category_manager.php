<?php
	$allCategories = CategoryDao::getAll();
	$allElements = ElementDao::getAll();

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
?>
<div id="categories-list">
	<div id="categories-list-menu">
		<button type="button"
				class="button button-add menu-item"
				onclick="openModalEditCategory('edit-category-new', null)"
				>
				Add category
		</button>
	</div>
	<table id="categories-list-table">
		<thead>
			<td class="categories-list-th">Name</td>
			<td class="categories-list-th">Description</td>
			<td class="categories-list-th">Color</td>
			<td class="categories-list-th"># Elements</td>
			<td class="categories-list-th"></td>
		</thead>
		<tbody>
			<?php
				foreach ($allCategories as $category) {
					?>
						<tr id="category-<?php echo $category->getId();?>" class="categories-tr">

							<td class="categories-list-td">
								<span <?php echo $category->cssHTML();?>><?php echo $category->getName();?></span>
							</td>

							<td class="categories-list-td">
								<p>
									<?php echo $category->getDescription();?>
								</p>
							</td>

							<td class="categories-list-td color-td">
								<span <?php echo $category->cssHTML();?>><?php echo $category->getColor();?></span>
							</td>

							<td class="categories-list-td">
								<?php 
									if(array_key_exists($category->getId(), $categoryCount))
										echo $categoryCount[$category->getId()];
									else
										echo "0";
								?>
								<a href="index.php?page=element_list&category_id=<?php echo $category->getId();?>">=> go</a>
							</td>

							<td class="categories-list-td buttons-td">
								<button class="button button-edit" type="button" onclick="openModalEditCategory('edit-category-<?php echo $category->getId();?>', '<?php echo $category->getId();?>')">Edit</button><br>
								<button class="button button-delete" type="button" onclick="$('#delete-category-<?php echo $category->getId();?>').bPopup();">Delete</button>

								<?php
									printModalEditCategory($allCategories, $category);
									printModalDeleteCategory($category, $categoryCount);
								?>
							</td>

						</tr>
					<?php
				}
			?>
		</tbody>
	</table>
	<?php
		//Modal for new Category
		printModalEditCategory($allCategories, null);
		echo "<div id='select-colorexist-option' style='display:none'>".getOptionColorHelper($allCategories)."</div>";
	?>
</div>
<script type="text/javascript" language="javascript" src="js/category_manager.js"></script>

<?php

	function printModalDeleteCategory(Category $c, $categoryCount){
		$form_id = "delete-category-".$c->getId();
		$number_elements = array_key_exists($c->getId(), $categoryCount) ? $categoryCount[$c->getId()] : 0;
		?>
			<form id="<?php echo $form_id;?>"
				 class="modal modal-delete-category"
				 >

				<div class="form-title">
					Delete category <?php echo "<span".$c->cssHTML().">".$c->getName()."</span>";?> ?
				</div>

				<div class="form-content">
					<input 	
						type="hidden"
						id="<?php echo $form_id;?>-input-id"
						name="<?php echo $form_id;?>-input-id"
						value="<?php echo $c->getId();?>"
						>
						<input 
							type="hidden"
							id="<?php echo $form_id;?>-input-name"
							name="<?php echo $form_id;?>-input-name"
							value="<?php echo $c->getName();?>"
							/>
					<table >
						<tr>
							<td>Description</td>
							<td><?php echo $c->getDescription();?></td>
						</tr>
						<tr>
							<td colspan="2">Delete this category will also delete <?php echo $number_elements;?> elements</td>
						</tr>
						<tr>
							<td>Confirm category name to delete</td>
							<td>
								<input 
									type="text"
									id="<?php echo $form_id;?>-input-confirm-name"
									name="<?php echo $form_id;?>-input-confirm-name"
									value=""
									/>
							</td>
						</tr>
					</table>
				</div>

				<div class="form-button">
					<button type="button" data-form-id="<?php echo $form_id;?>" class="button button-confirm button-delete-category">Delete</button>
					<button type="button" class="button button-cancel" onclick="$('#<?php echo $form_id;?>').bPopup().close(); $('#<?php echo $form_id;?>-input-confirm-name').val('');">Cancel</button>
				</div>
			</form>
		<?php
	}

	/**
	 * Print the modal to edit an existing category or create a new one
	 * @param  array $allCategories 
	 * @param  Category $c    
	 */
	function printModalEditCategory($allCategories, Category $c = null){
		$form_id = "edit-category-".($c ? $c->getId() : "new");
		?>
			<form 	id="<?php echo $form_id;?>" 
					class="modal modal-edit-category"
					>

				<div class="form-title">
					<?php
						echo ($c ? "Edit category <span".$c->cssHTML().">".$c->getName()."</span>" : "Create a new category");
					?>
				</div>
				<div class="form-content">
					<table class="form-table">
						<tr>
							<td class="label-td">				
								<label for="<?php echo $form_id;?>-input-name">
								Name
								</label>
							</td>
							<td class="input-td">
								<input 
									type="text"
									id="<?php echo $form_id;?>-input-name"
									name="category-name"
									value="<?php if($c) echo $c->getName();?>"
									/>
								<input 	
									type="hidden"
									id="<?php echo $form_id;?>-input-id"
									name="<?php echo $form_id;?>-input-id"
									value="<?php if($c) echo $c->getId();?>"
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
									name="category-description"
									><?php if($c) echo $c->getDescription();?></textarea>
							</td>
						</tr>
						<tr>
							<td class="label-td">
								<label for="<?php echo $form_id;?>_color-css">
									Current color
								</label>
							</td>
							<td  class="input-td">
								<input 
									type="text"
									id="<?php echo $form_id;?>_color-css"
									class="color-input-color-css"
									name="category-color-css"
									value=""
									/>
							</td>
						</tr>
						<tr>
							<td class="space-td" colspan="2"></td>
						</tr>
						<tr>
							<td class="label-td">
								<label for="<?php echo $form_id;?>_select-colorexist">
									Suggested color
								</label>
							</td>
							<td  class="input-td">
								<select
									id="<?php echo $form_id;?>_select-colorexist"
									name="<?php echo $form_id;?>-select-colorexist"
									onchange="onChangeSelect('<?php echo $form_id;?>')"
									class="color-select-colorexist"
									>
									
								</select>
							</td>
						</tr>
					</table>
					<div class="color-preview">

						<table>
							<tr>
								<td>
									<div id="<?php echo $form_id;?>_red"    class="colorpicker-red"></div></td>
								<td rowspan="3">
									<div id="<?php echo $form_id;?>_swatch" class="colorpicker-swatch ui-widget-content ui-corner-all"></div>
								</td>
							</tr>
							<tr>
								<td>
									<div id="<?php echo $form_id;?>_green"  class="colorpicker-green"></div>
								</td>
							</tr>
							<tr>
								<td>
									<div id="<?php echo $form_id;?>_blue"   class="colorpicker-blue"></div>
								</td>
							</tr>
						</table>

					</div>
				</div>

				<div class="form-button">
					<button type="button" data-form-id="<?php echo $form_id;?>" class="button button-confirm button-save-category">Save</button>
					<button type="button" class="button button-cancel" onclick="closeModalEditCategory('<?php echo $form_id;?>')">Cancel</button>
				</div>

			</form>
		<?php
	}

	/**
	 * Return the option list of color
	 * @param  Category $c      
	 */
	function getOptionColorHelper($allCategories){
		$options = "";

		//Add default selected option
		$option = "<option value='no-match'";
		$option.= " selected='selected'";
		$option.= "><span></span></option><br>";
		$options .= $option;

		//Add existing categories
		$arrayExistingColors = array();
		foreach ($allCategories as $category) {
			if(array_key_exists($category->getColor(), $arrayExistingColors)){
				$arrayExistingColors[$category->getColor()] .= ", ".$category->getName();
			}
			else{
				$arrayExistingColors[$category->getColor()] = $category->getName();
			}
		}

		//Add flatui color with notice for already used color
		
		//turquoise
		$option = "<option value='#1abc9c'";
		$option.= " data-sumo-class='turquoise'";	
		$option.= ">Turquoise";
		if(array_key_exists('#1abc9c',$arrayExistingColors)) $option.= " (".$arrayExistingColors['#1abc9c'].")";
		$option.= "</option><br>";
		$options .= $option;
		//emerald
		$option = "<option value='#2ecc71'";
		$option.= " data-sumo-class='emerald'";
		$option.= ">Emerald";
		if(array_key_exists('#2ecc71',$arrayExistingColors)) $option.= " (".$arrayExistingColors['#2ecc71'].")";
		$option.= "</option><br>";
		$options .= $option;
		//nephritis
		$option = "<option value='#27ae60'";
		$option.= " data-sumo-class='nephritis'";
		$option.= ">Nephritis";
		if(array_key_exists('#27ae60',$arrayExistingColors)) $option.= " (".$arrayExistingColors['#27ae60'].")";
		$option.= "</option><br>";
		$options .= $option;
		//green_sea
		$option = "<option value='#16a085'";
		$option.= " data-sumo-class='green_sea'";
		$option.= ">Green Sea";
		if(array_key_exists('#16a085',$arrayExistingColors)) $option.= " (".$arrayExistingColors['#16a085'].")";
		$option.= "</option><br>";
		$options .= $option;
		//peter_river
		$option = "<option value='#3498db'";
		$option.= " data-sumo-class='peter_river'";
		$option.= ">Peter River";
		if(array_key_exists('#3498db',$arrayExistingColors)) $option.= " (".$arrayExistingColors['#3498db'].")";
		$option.= "</option><br>";
		$options .= $option;
		//belize_hole
		$option = "<option value='#2980b9'";
		$option.= " data-sumo-class='belize_hole'";
		$option.= ">Belize Hole";
		if(array_key_exists('#2980b9',$arrayExistingColors)) $option.= " (".$arrayExistingColors['#2980b9'].")";
		$option.= "</option><br>";
		$options .= $option;
		//amethyst
		$option = "<option value='#9b59b6'";
		$option.= " data-sumo-class='amethyst'";
		$option.= ">Amethyst";
		if(array_key_exists('#9b59b6',$arrayExistingColors)) $option.= " (".$arrayExistingColors['#9b59b6'].")";
		$option.= "</option><br>";
		$options .= $option;
		//wisteria
		$option = "<option value='#8e44ad'";
		$option.= " data-sumo-class='wisteria'";
		$option.= ">Wisteria";
		if(array_key_exists('#8e44ad',$arrayExistingColors)) $option.= " (".$arrayExistingColors['#8e44ad'].")";
		$option.= "</option><br>";
		$options .= $option;
		//sun_flower
		$option = "<option value='#f1c40f'";
		$option.= " data-sumo-class='sun_flower'";
		$option.= ">Sun Flower";
		if(array_key_exists('#f1c40f',$arrayExistingColors)) $option.= " (".$arrayExistingColors['#f1c40f'].")";
		$option.= "</option><br>";
		$options .= $option;
		//orange
		$option = "<option value='#f39c12'";
		$option.= " data-sumo-class='orange'";
		$option.= ">Orange";
		if(array_key_exists('#f39c12',$arrayExistingColors)) $option.= " (".$arrayExistingColors['#f39c12'].")";
		$option.= "</option><br>";
		$options .= $option;
		//carrot
		$option = "<option value='#e67e22'";
		$option.= " data-sumo-class='carrot'";
		$option.= ">Carrot";
		if(array_key_exists('#e67e22',$arrayExistingColors)) $option.= " (".$arrayExistingColors['#e67e22'].")";
		$option.= "</option><br>";
		$options .= $option;
		//pumpkin
		$option = "<option value='#d35400'";
		$option.= " data-sumo-class='pumpkin'";
		$option.= ">Pumpkin";
		if(array_key_exists('#d35400',$arrayExistingColors)) $option.= " (".$arrayExistingColors['#d35400'].")";
		$option.= "</option><br>";
		$options .= $option;
		//Alizarin
		$option = "<option value='#e74c3c'";
		$option.= " data-sumo-class='Alizarin'";
		$option.= ">Alizarin";
		if(array_key_exists('#e74c3c',$arrayExistingColors)) $option.= " (".$arrayExistingColors['#e74c3c'].")";
		$option.= "</option><br>";
		$options .= $option;
		//pomgranate
		$option = "<option value='#c0392b'";
		$option.= " data-sumo-class='pomgranate'";
		$option.= ">Pomgranate";
		if(array_key_exists('#c0392b',$arrayExistingColors)) $option.= " (".$arrayExistingColors['#c0392b'].")";
		$option.= "</option><br>";
		$options .= $option;
		//wet_asphalt
		$option = "<option value='#34495e'";
		$option.= " data-sumo-class='wet_asphalt'";
		$option.= ">Wet Asphalt";
		if(array_key_exists('#34495e',$arrayExistingColors)) $option.= " (".$arrayExistingColors['#34495e'].")";
		$option.= "</option><br>";
		$options .= $option;
		//midnight_blue
		$option = "<option value='#2c3e50'";
		$option.= " data-sumo-class='midnight_blue'";
		$option.= ">Midnight Blue";
		if(array_key_exists('#2c3e50',$arrayExistingColors)) $option.= " (".$arrayExistingColors['#2c3e50'].")";
		$option.= "</option><br>";
		$options .= $option;
		//clouds
		$option = "<option value='#ecf0f1'";
		$option.= " data-sumo-class='clouds'";
		$option.= ">Clouds";
		if(array_key_exists('#ecf0f1',$arrayExistingColors)) $option.= " (".$arrayExistingColors['#ecf0f1'].")";
		$option.= "</option><br>";
		$options .= $option;
		//sliver
		$option = "<option value='#bdc3c7'";
		$option.= " data-sumo-class='sliver'";
		$option.= ">Silver";
		if(array_key_exists('#bdc3c7',$arrayExistingColors)) $option.= " (".$arrayExistingColors['#bdc3c7'].")";
		$option.= "</option><br>";
		$options .= $option;
		//concrete
		$option = "<option value='#95a5a6'";
		$option.= " data-sumo-class='concrete'";
		$option.= ">Concrete";
		if(array_key_exists('#95a5a6',$arrayExistingColors)) $option.= " (".$arrayExistingColors['#95a5a6'].")";
		$option.= "</option><br>";
		$options .= $option;
		//asbestos
		$option = "<option value='#7f8c8d'";
		$option.= " data-sumo-class='asbestos'";
		$option.= ">Asbestos";
		if(array_key_exists('#7f8c8d',$arrayExistingColors)) $option.= " (".$arrayExistingColors['#7f8c8d'].")";
		$option.= "</option><br>";
		$options .= $option;

		

		return $options;
	}
?>
