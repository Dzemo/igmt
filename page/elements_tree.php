<div id="elements-tree">
	<img src="images/generation/elements_tree.png"><br>
	
	<span class="generation-date">
		<?php
			require_once("lib/date_string_utils.php");
			echo "Generated on ".tmspToDateLong(filemtime("images/generation/elements_tree.png"));
		?>
	</span>
	<a href="processing/generate_image_tree.php">generate</a>
</div>