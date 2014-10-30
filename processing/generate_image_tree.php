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

	//Create colors
	$blanc = imagecolorallocate($image, 255, 255, 255);
	$noir = imagecolorallocate($image, 0, 0, 0);

	//Printing the Elements
	$treePos = drawTreeElements($image, $tree, $font, $margin_x, $margin_y, $space_x, $space_y, array('default' => $noir));

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

