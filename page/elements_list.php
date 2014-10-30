<div id="elements-list">
	<table class="elements-list-table">
		<thead>
			<th class="elements-list-th need-th">Need</th>
			<th class="elements-list-th element-th">Element</th>
			<th class="elements-list-th allow-th">Allow</th>
			<th class="elements-list-th edit-th">Edit</th>
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
											<a 	href="#element-.<?php echo $target->trimedName();?>"
												class="toolip"
												<?php echo $target->getCategory()->cssStyle();?>
												>
												<?php echo $target->getName();?>
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
								<span <?php echo $element->getCategory()->cssStyle();?>>
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
											<a href="#element-.<?php echo $target->trimedName();?>"
												class="toolip"												
												<?php echo $target->getCategory()->cssStyle();?>
												>
												<?php echo $target->getName();?>												
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

						<td class="elements-list-td edit-td">
							<!-- Edit -->
							<button type="button" onclick="$('#edit-element-<?php echo $element->trimedName();?>').bPopup();">Edit</button>
							<?php printModalEditElement($element, $categories);?>
						</td>
					</tr>
				<?php
				}
			?>
		<tbody>
	</table>
</div>
<script type="text/javascript">

</script>

<?php

	function printModalEditElement(Element $e, $categories){
		//$e is an element but can be null when this function is used to print the modal for creating a new Element
		?>
			<form 	id="edit-element<?php echo ($e ? "-".$e->trimedName() : "new");?>" 
					class="modal modal-edit-element"
					>

				<!-- Name -->
				<label for="name-<?php echo ($e ? "-".$e->trimedName() : "new");?>">
					Name
				</label>
				<input 
					type="text"
					id="name-<?php echo ($e ? "-".$e->trimedName() : "new");?>"
					name="element-name"
					value="<?php if($e) echo $e->getName();?>"
					/>
				<br>

				<!-- Description -->
				<label for="description-<?php echo ($e ? "-".$e->trimedName() : "new");?>">
					Description
				</label>
				<input 
					type="text"
					id="description-<?php echo ($e ? "-".$e->trimedName() : "new");?>"
					name="element-description"
					value="<?php if($e) echo $e->getDescription();?>"
					/>
				<br>

				<label for="category-<?php echo ($e ? "-".$e->trimedName() : "new");?>">
					Category
				</label>
				<select
					id="category-<?php echo ($e ? "-".$e->trimedName() : "new");?>"
					name="element-category"
					>
					<?php printOptionCategoryForElement($categories, $e);?>
				</select>

			</form>
		<?php
	}

	/**
	 * Print the option list for categories
	 * If an Element is specified, select his category
	 * @param  array  $categories 
	 * @param  Element $e       
	 */
	function printOptionCategoryForElement($categories, Element $e){
		foreach ($categories as $category) {
			echo "<option value=\"".$category->getName()."\"";
			if($e && $e->getCategory() == $category)
				echo "selected=\"selected\"";
			echo $category->getName()."</option>";
		}
	}
?>