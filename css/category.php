<?php
	require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."lib".DIRECTORY_SEPARATOR."classloader.php");

	/*** set the content type header ***/
	header("Content-type: text/css");

	$categories = CategoryDao::getAll();
	foreach ($categories as $category) {
		echo $category->cssClass();
	}
?>