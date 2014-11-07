<?php

	session_start();
	
	require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."lib".DIRECTORY_SEPARATOR."classloader.php");
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


	$element = new Element($id, $name, $category);

	//Description
	if(isset($_POST['description']) && strlen($_POST['description']) > 0 && $element){
		$description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
		$element->setDescription($description);
	}


	//we need all elements to create link and to check the name is not taken
	$allElements = ElementDao::getAll();

	if($element->getId() == null){
		foreach ($allElements as $knowElement) {
			if($knowElement->getName() == $element->getName()){
				$errors[] = 'Name '.$element->getName().' already used';
				echo json_encode(array('errors' => $errors));
				die();
			}
		}
	}

	//////////
	//Need //
	//////////

	if(isset($_POST['need']) && is_array($_POST['need'])){
		foreach ($_POST['need'] as $post_need) {
			//link_id
			$link_id = null;
			if(filter_var($post_need['link_id'], FILTER_VALIDATE_INT)){
				$link_id = intval($post_need['link_id']);
			}

			//Target name
			$need = null;
			if(isset($post_need['target_id']) && strlen($post_need['target_id']) > 0){
				$target_id = filter_var($post_need['target_id'], FILTER_SANITIZE_STRING);
				if($target_id == $element->getId()){
					$errors[] = 'Element can\'t needs himself';
				}
				else if(array_key_exists($target_id, $allElements)){
					$need = new Need($link_id, $allElements[$target_id]);
				}
				else{
					$errors[] = 'Element '.$target_id.' doesn\'t exists';
				}
			}

			//Conditions
			if(isset($post_need['conditions']) && strlen($post_need['conditions']) > 0 && $need){
				$conditions = filter_var($post_need['conditions'], FILTER_SANITIZE_STRING);
				$need->setConditions($conditions);
			}

			if($need)
				$element->addNeed($need);
		}
	}


	///////////
	//Allow //
	///////////
	
	if(isset($_POST['allow']) && is_array($_POST['allow'])){
		foreach ($_POST['allow'] as $post_allow) {
			//link_id
			$link_id = null;
			if(filter_var($post_allow['link_id'], FILTER_VALIDATE_INT)){
				$link_id = intval($post_allow['link_id']);
			}

			//Target name
			$allow = null;
			if(isset($post_allow['target_id']) && strlen($post_allow['target_id']) > 0){
				$target_id = filter_var($post_allow['target_id'], FILTER_SANITIZE_STRING);
				if($target_id == $element->getId()){
					$errors[] = 'Element can\'t allows himself';
				}
				else if(array_key_exists($target_id, $allElements)){
					$allow = new Allow($link_id, $allElements[$target_id]);
				}
				else{
					$errors[] = 'Element '.$target_id.' doesn\'t exists';
				}
			}

			//Conditions
			if(isset($post_allow['conditions']) && strlen($post_allow['conditions']) > 0 && $allow){
				$conditions = filter_var($post_allow['conditions'], FILTER_SANITIZE_STRING);
				$allow->setConditions($conditions);
			}

			if($allow)
				$element->addAllow($allow);
		}
	}


	////////////
	//Extend //
	////////////

	//Extend
	if(isset($_POST['extend']) && is_array($_POST['extend'])){
		$post_extend = $_POST['extend'];

		//link_id
		$link_id = null;
		if(filter_var($post_extend['link_id'], FILTER_VALIDATE_INT)){
			$link_id = intval($post_extend['link_id']);
		}

		//Target name
		$extend = null;
		if(isset($post_extend['target_id']) && strlen($post_extend['target_id']) > 0){
			$target_id = filter_var($post_extend['target_id'], FILTER_SANITIZE_STRING);
			if($target_id == $element->getId()){
				$errors[] = 'Element can\'t extends himself';
			}
			else if(array_key_exists($target_id, $allElements)){
				$extend = new Extend($link_id, $allElements[$target_id]);
			}
			else{
				$errors[] = 'Element '.$target_id.' doesn\'t exists';
			}
		}

		//Conditions
		if(isset($post_extend['conditions']) && strlen($post_extend['conditions']) > 0 && $extend){
			$conditions = filter_var($post_extend['conditions'], FILTER_SANITIZE_STRING);
			$extend->setConditions($conditions);
		}

		if($extend)
			$element->setExtend($extend);
	}

	//ExtendedBy
	if(isset($_POST['extendedby']) && is_array($_POST['extendedby'])){
		foreach ($_POST['extendedby'] as $post_extendedby) {
			//link_id
			$link_id = null;
			if(filter_var($post_extendedby['link_id'], FILTER_VALIDATE_INT)){
				$link_id = intval($post_extendedby['link_id']);
			}

			//Target name
			$extendedBy = null;
			if(isset($post_extendedby['target_id']) && strlen($post_extendedby['target_id']) > 0){
				$target_id = filter_var($post_extendedby['target_id'], FILTER_SANITIZE_STRING);
				if($target_id == $element->getId()){
					$errors[] = 'Element can\'t be extented by himself';
				}
				else if(array_key_exists($target_id, $allElements)){
					$extendedBy = new ExtendedBy($link_id, $allElements[$target_id]);
				}
				else{
					$errors[] = 'Element '.$target_id.' doesn\'t exists';
				}
			}

			//Conditions
			if(isset($post_extendedby['conditions']) && strlen($post_extendedby['conditions']) > 0 && $extendedBy){
				$conditions = filter_var($post_extendedby['conditions'], FILTER_SANITIZE_STRING);
				$extendedBy->setConditions($conditions);
			}

			if($extendedBy)
				$element->addExtension($extendedBy);
		}
	}


	////////////
	//Evolve //
	////////////

	//Regress
	if(isset($_POST['regress']) && is_array($_POST['regress'])){
		$post_regress = $_POST['regress'];

		//link_id
		$link_id = null;
		if(filter_var($post_regress['link_id'], FILTER_VALIDATE_INT)){
			$link_id = intval($post_regress['link_id']);
		}

		//Target name
		$regress = null;
		if(isset($post_regress['target_id']) && strlen($post_regress['target_id']) > 0){
			$target_id = filter_var($post_regress['target_id'], FILTER_SANITIZE_STRING);
			if($target_id == $element->getId()){
				$errors[] = 'Element can\'t evolves from himself';
			}
			else if(array_key_exists($target_id, $allElements)){
				$regress = new Regress($link_id, $allElements[$target_id]);
			}
			else{
				$errors[] = 'Element '.$target_id.' doesn\'t exists';
			}
		}

		//Conditions
		if(isset($post_regress['conditions']) && strlen($post_regress['conditions']) > 0 && $regress){
			$conditions = filter_var($post_regress['conditions'], FILTER_SANITIZE_STRING);
			$regress->setConditions($conditions);
		}

		if($regress)
			$element->setRegress($regress);
	}

	//Evolve into
	if(isset($_POST['evolve']) && is_array($_POST['evolve'])){
		foreach ($_POST['evolve'] as $post_evolve) {
			//link_id
			$link_id = null;
			if(filter_var($post_evolve['link_id'], FILTER_VALIDATE_INT)){
				$link_id = intval($post_evolve['link_id']);
			}

			//Target name
			$evolve = null;
			if(isset($post_evolve['target_id']) && strlen($post_evolve['target_id']) > 0){
				$target_id = filter_var($post_evolve['target_id'], FILTER_SANITIZE_STRING);
				if($target_id == $element->getId()){
					$errors[] = 'Element can\'t evolves into himself';
				}
				else if(array_key_exists($target_id, $allElements)){
					$evolve = new Evolve($link_id, $allElements[$target_id]);
				}
				else{
					$errors[] = 'Element '.$target_id.' doesn\'t exists';
				}
			}

			//Conditions
			if(isset($post_evolve['conditions']) && strlen($post_evolve['conditions']) > 0 && $evolve){
				$conditions = filter_var($post_evolve['conditions'], FILTER_SANITIZE_STRING);
				$evolve->setConditions($conditions);
			}

			if($evolve)
				$element->addEvolution($evolve);
		}
	}


	////////////////
	//Processing //
	////////////////


	if(count($errors) > 0){
		echo json_encode(array('errors' => $errors));
	}
	else{
		
		if($element->getId() != null){
			
			ElementDao::update($element);
			$message =  "Element ".$element->getName()." saved!";
		}
		else{
			ElementDao::insert($element);
			$message = "Element ".$element->getName()." created!";
		}

		echo json_encode(array('redirect' => $GLOBALS['dns']."?page=elements_list", 'message' => $message));
	}
?>
