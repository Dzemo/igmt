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

	//Id
	if(isset($_POST['id']) && filter_var($_POST['id'], FILTER_VALIDATE_INT)){
		$id = intval($_POST['id']);
	}
	else{
		$id = null;
	}

	//Name
	if(isset($_POST['name']) && strlen($_POST['name']) > 0){
		$name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
	}
	else{
		$errors[] = 'Missing name';
	}

	//Name
	if(isset($_POST['color']) && strlen($_POST['color']) > 0){
		$color = filter_var($_POST['color'], FILTER_SANITIZE_STRING);
	}
	else{
		$errors[] = 'Missing color';
	}

	//If there is some error, output them now and stop
	if(count($errors) > 0){
		echo json_encode(array('errors' => $errors));
		die();
	}


	$category = new Category($id, $name, $color);

	//Description
	if(isset($_POST['description']) && strlen($_POST['description']) > 0 && $category){
		$description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
		$category->setDescription($description);
	}


	//Check existence
	if($category->getId() == null && CategoryDao::getByName($category->getName()) != null){
		$errors[] = 'Category '.$category->getName()." already exists";
	}

	////////////////
	//Processing //
	////////////////


	if(count($errors) > 0){
		echo json_encode(array('errors' => $errors));
	}
	else{
		
		if($category->getId() != null){
			
			CategoryDao::update($category);
			$message =  "Category ".$category->getName()." saved!";
		}
		else{
			CategoryDao::insert($category);
			$message = "Category ".$category->getName()." created!";
		}

		//Regenerate category css
		generateCssCategory();

		echo json_encode(array('redirect' => $GLOBALS['dns']."?page=category_manager", 'message' => $message));
	}
?>
