<?php

	session_start();
	
	require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."lib".DIRECTORY_SEPARATOR."classloader.php");
	require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."config.php");

	//Array of errors
	$errors = array();

	//Name
	if(isset($_POST['name']) && strlen($_POST['name']) > 0){
		$name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
	}
	else{
		$errors[] = 'Missing name';
	}

	//Category
	if(isset($_POST['category']) && strlen($_POST['category']) > 0){
		$category_name = filter_var($_POST['category'], FILTER_SANITIZE_STRING);

		$category = CategoryDao::getByName($category_name);

		if($category == null){
			$errors[] = 'Category '.$category_name.' does\'t exists';
		}

	}
	else{
		$errors[] = 'Missing category';
	}

	//If there is some error, output them now and stop
	if(count($errors) > 0){
		echo json_encode(array('errors' => $errors));
		die();
	}


	$element = new Element($name, $category);

	//we need all elements to create link
	$allElements = ElementDao::getAll();

	//Description
	if(isset($_POST['description']) && strlen($_POST['description']) > 0){
		$description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
		$element->setDescription($description);
	}

	//Need
	if(isset($_POST['need']) && is_array($_POST['need'])){
		foreach ($_POST['need'] as $post_need) {
			//link_id
			$link_id = null;
			if(filter_var($post_need['link_id'], FILTER_VALIDATE_INT)){
				$link_id = intval($post_need['link_id']);
			}

			//Target name
			$need = null;
			if(isset($post_need['target_name']) && strlen($post_need['target_name']) > 0){
				$target_name = filter_var($post_need['target_name'], FILTER_SANITIZE_STRING);
				if(array_key_exists($target_name, $allElements)){
					$need = new Need($link_id, $allElements[$target_name]);
				}
				else{
					$errors[] = 'Element '.$target_name.' doesn\'t exists';
				}
			}

			//Conditions
			if(isset($post_need['conditions']) && strlen($post_need['conditions']) > 0 && $need){
				$conditions = filter_var($post_need['conditions'], FILTER_SANITIZE_STRING);
				$need->setConditions($conditions);
			}

			$element->addNeed($need);
		}
	}


	//Allow
	if(isset($_POST['allow']) && is_array($_POST['allow'])){
		foreach ($_POST['allow'] as $post_allow) {
			//link_id
			$link_id = null;
			if(filter_var($post_allow['link_id'], FILTER_VALIDATE_INT)){
				$link_id = intval($post_allow['link_id']);
			}

			//Target name
			$allow = null;
			if(isset($post_allow['target_name']) && strlen($post_allow['target_name']) > 0){
				$target_name = filter_var($post_allow['target_name'], FILTER_SANITIZE_STRING);
				if(array_key_exists($target_name, $allElements)){
					$allow = new Allow($link_id, $allElements[$target_name]);
				}
				else{
					$errors[] = 'Element '.$target_name.' doesn\'t exists';
				}
			}

			//Conditions
			if(isset($post_allow['conditions']) && strlen($post_allow['conditions']) > 0 && $allow){
				$conditions = filter_var($post_allow['conditions'], FILTER_SANITIZE_STRING);
				$allow->setConditions($conditions);
			}

			$element->addAllow($allow);
		}
	}

	if(count($errors) > 0){
		echo json_encode(array('errors' => $errors));
	}
	else{
		if(!isset($_SESSION['noty'])) $_SESSION['noty'] = array();

		if(array_key_exists($element->getName(), $allElements)){
			//ElementDao::update($element);
			$message =  "Element ".$element->getName()." saved!";
		}
		else{
			//ElementDao::insert($element);
			$message = "Element ".$element->getName()." created!";
		}

		echo json_encode(array('redirect' => $GLOBALS['dns']."?page=elements_list", 'message' => $message));
	}
?>