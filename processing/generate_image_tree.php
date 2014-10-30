<?php
	require_once("../lib/classloader.php");
	require_once("../lib/image_generation_utils.php");
	require_once("../config.php");

	//Buil the tree from the elements
	$tree = buildTree($elements = ElementDao::getAll());

	//Create the image
	$font = 5;
	$margin_x = 10;
	$margin_y = 20;
	$space_x = 15;
	$space_y = 30;
	$space_arrow_up = 2;
	$space_arrow_down = 2;
	$width = getImageWidth($tree, $font , $margin_x, $space_x);
	$heigth = getImageHeight($tree, $font, $margin_y, $space_y);
	$image = imagecreate($width, $heigth);

	//White background
	$blanc = imagecolorallocate($image, 255, 255, 255);

	//Gather category color
	$categories = CategoryDao::getAll();
	$colorsForCategory = array();
	foreach ($categories as $category) {
		$hexColor = hex2rgb($category->getColor());
		$colorsForCategory[$category->getName()] = imagecolorallocate($image, $hexColor[0], $hexColor[1], $hexColor[2]);
	}
	//default black color
	$colorsForCategory['default'] = imagecolorallocate($image, 0, 0, 0);

	//Printing the Elements
	$treePos = drawTreeElements($image, $tree, $font, $margin_x, $margin_y, $space_x, $space_y, $colorsForCategory);

	//Drawing arrows
	foreach ($treePos as $name => $treeElem) {
		$element = $treeElem['element'];
		foreach ($element->getNeed() as $need) {
			$target = $need->getTarget();

			$start_x = ($treePos[$target->getName()]['x']+$treePos[$target->getName()]['width'])/2;
			$start_y = $treePos[$target->getName()]['y'] + $treePos[$target->getName()]['height'] + $space_arrow_up;
			$end_x = ($treeElem['x']+$treeElem['width'])/2;
			$end_y = $treeElem['y']-$space_arrow_down;

			arrow($image, $start_x, $start_y, $end_x, $end_y, 3, 3, $noir);
		}
	}

	//Output image
	imagepng($image, "../images/generation/elements_tree.png");

	header('Location: '.$GLOBALS['dns'].'index.php?page=elements_tree');
?>

