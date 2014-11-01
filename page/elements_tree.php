<?php
	require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."lib".DIRECTORY_SEPARATOR."date_string_utils.php");
?>

<div id="elements-tree">
	<img src="images/generation/elements_tree.png"><br>
	
	<span class="generation-date">
		<?php
			echo "Generated on ".tmspToDateLong(filemtime("images/generation/elements_tree.png"));
		?>
	</span>
	<a href="processing/generate_image_tree.php">generate</a>
</div>