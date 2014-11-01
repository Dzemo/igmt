<?php
	require_once("../lib/classloader.php");
	require_once("../lib/image_generation_utils.php");
	require_once("../config.php");

	//Buil the tree from the elements
	$tree = buildTree($elements = ElementDao::getAll());

	printTree($tree);

	//Create the image
	$font = 5;
	$margin_x = 10;
	$margin_y = 20;
	$space_x = 15;
	$space_y = 30;
	$space_arrow = 2;

	$width = getImageWidth($tree, $font , $margin_x, $space_x);
	$heigth = getImageHeight($tree, $font, $margin_y, $space_y);
	$image = imagecreate($width, $heigth);
	imageantialias($image, true);

	//White background
	$blanc = imagecolorallocate($image, 255, 255, 255);
	$blanc_alpha = imagecolorallocatealpha($image, 255, 255, 255, 75);
	$noir = imagecolorallocate($image, 0, 0, 0);

	//Gather category color
	$categories = CategoryDao::getAll();
	$colors = array();
	$colors['categories'] = array();
	foreach ($categories as $category) {
		$hexColor = hex2rgb($category->getColor());
		$colors['categories'][$category->getName()] = imagecolorallocate($image, $hexColor[0], $hexColor[1], $hexColor[2]);
	}
	//default black color
	$colors['categories']['default'] = $noir;
	$colors['default'] = $noir;
	$colors['arrow'] = $noir;
	$colors['background'] = $blanc;
	$colors['background-alpha'] = $blanc_alpha;

	//Printing the Elements Tree
	$treePos = drawElementsTree($image, $tree, $font, $margin_x, $margin_y, $space_x, $space_y, $space_arrow, $colors);

	//Output image
	imagepng($image, "../images/generation/elements_tree.png");

	//header('Location: '.$GLOBALS['dns'].'index.php?page=elements_tree');
?>

