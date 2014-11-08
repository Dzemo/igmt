<?php

	session_start();
	
	require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."lib".DIRECTORY_SEPARATOR."classloader.php");
	require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."lib".DIRECTORY_SEPARATOR."generate_css_category.php");
	require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."config.php");

	//Array of errors
	$errors = array();

	///////////
	//Infos //
	///////////

	//Name
	if(isset($_POST['name']) && strlen($_POST['name']) > 0){
		$name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
	}
	else{
		$name = "undefined";
	}

	//Id
	if(isset($_POST['id']) && filter_var($_POST['id'], FILTER_VALIDATE_INT)){
		$id = intval($_POST['id']);
	}
	else{
		$errors[] = 'Unspecified id';
		echo json_encode(array('errors' => $errors));
		die();
	}

	////////////////
	//Processing //
	////////////////
		

	CategoryDao::delete($id);
	$message =  "Category ".$name." deleted!";

	//regenerate css
	generateCssCategory();
	
	echo json_encode(array('redirect' => $GLOBALS['dns']."?page=category_manager", 'message' => $message));
	
?>
