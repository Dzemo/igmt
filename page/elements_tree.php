<?php
	require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."lib".DIRECTORY_SEPARATOR."date_string_utils.php");
	require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."config.php");

	$image_path = $tree_image_relative_path;

?>

<div id="elements-tree">

	<img 	id="image-tree"
			src="<?php echo $tree_image_relative_path."#".time();?>" 
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

	<button type="button" id="button-generate">Generate</button>
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
<script type="text/javascript" language="javascript" src="js/elements_tree.js"></script>