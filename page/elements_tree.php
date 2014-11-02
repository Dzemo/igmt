<?php
	require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."lib".DIRECTORY_SEPARATOR."date_string_utils.php");
	require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."config.php");

	$image_path = $tree_image_relative_path;

?>

<div id="elements-tree">

	<img 	id="image-tree"
			src="<?php echo $tree_image_relative_path;?>" 
			alt="No image has been generated yet"
			>
	<br>	

	<span id="generation-date">
		<?php
			if(file_exists($tree_image_absolute_path))
				echo "Generated on ".tmspToDateLong(filemtime($tree_image_absolute_path));
		?>
	</span>
	<br>
	<br>

	<button id="button-generate-element-tree"
			type="button"
			onclick="generateTree()"
			>
			<span id="button-generate-element-tree-generate">Generate</span>
			<span id="button-generate-element-tree-generating"><img src="images/ajax-loader.gif" alt="Generating"/></span>
	</button>
	<button id="button-toggle-generation-log"
			type="button" 
			onclick="toggleGenerationLog()"
			>
			Show generation log
	</button>
	<br>
	<br>

	<div id="generation-log" style="display:none">
		<?php
			if(file_exists($tree_generation_log_path)){
				echo file_get_contents($tree_generation_log_path);
			}
			else{
				echo 'No log yet';
			}
		?>
	</div>

</div>