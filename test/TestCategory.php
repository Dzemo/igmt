<?php
	require_once("lib/classloader.php");
	require_once("lib/image_generation_utils.php");

	$categories = CategoryDao::getAll();

	foreach($categories as $category){
		echo $category."<br>";
		var_dump(hex2rgb($category->getColor()));
	}

?>