<?php
	require_once(dirname(__FILE__)."/../lib/classloader.php");
	require_once(dirname(__FILE__)."/../lib/date_string_utils.php");
	require_once(dirname(__FILE__)."/../lib/image_generation_utils.php");
	require_once(dirname(__FILE__)."/../config.php");

	// Customize generation here 
	$font_size = 20;
	$font_file = dirname(__FILE__).DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."lib".DIRECTORY_SEPARATOR."Calibri.ttf";
	$margin_x = 20;		//Horizontal space between Element and border
	$margin_y = 30;		//Vertical space between Element and border
	$space_x = 30;		//Horizontal space between Element
	$space_y = 60;		//Vertical space between Element
	$space_arrow = 3;	//Space between Element and start/end of arrows
	$image_path = dirname(__FILE__).DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR.".generation.".DIRECTORY_SEPARATOR."elements_tree.png";
	$limit_time = 15; 		//Limit time for generation
	$prefered_width = 1000;	//Prefered width of the image

	// Start generating the image 
	$start = time();

	echo tmspToDateLong(time());
	echo "<br>===== Start Generation =====<br>";

	//Buil the tree from the elements
	$original_tree = buildTree($elements = ElementDao::getAll());


	echo "<br>===== Tree =====<br>";
	printTree($original_tree);


	//Create the image
	$font_size = 20;
	$font_file = dirname(__FILE__).DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."lib".DIRECTORY_SEPARATOR."Calibri.ttf";
	$margin_x = 30;
	$margin_y = 30;
	$space_x = 40;
	$space_y = 40;
	$space_arrow = 2;
	$image_path = "../images/generation/elements_tree.png";
	$width = max(getImageWidth($original_tree, $font_size, $font_file, $margin_x, $space_x), $prefered_width);
	$heigth = getImageHeight($original_tree, $font_size, $font_file, $margin_y, $space_y);
	$image = imagecreatetruecolor($width, $heigth);
	imageantialias($image, true);


	echo "<br>===== Image parameters =====<br>";
	echo "width= ".$width."px<br>";
	echo "heigth= ".$heigth."px<br>";
	echo "image_path= $image_path<br>";
	echo "font_size= ".$font_size."px<br>";
	echo "font_file= $font_file<br>";
	echo "margin_x= ".$margin_x."px<br>";
	echo "margin_y= ".$margin_y."px<br>";
	echo "space_x= ".$space_x."px<br>";
	echo "space_y= ".$space_y."px<br>";
	echo "space_arrow= ".$space_arrow."px<br>";

	//White background
	$blanc = imagecolorallocate($image, 255, 255, 255);
	imagefill($image, 0, 0, $blanc);
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

	//Starting generate optimal position for Element name and arrows
	echo "<br>===== Shuffeling =====<br>";
	//check arrows intersection
	$hash_pool = array();//Pool of trees resulting of a shuffle of the original_tree
	$min_intersect = 10000000;
	$print_candidate = array("strings" => array(), "arrows" => array());
	do{
		//Shuffle the tree to try a new adjustement of the tree
		$arrayShuffle = shuffleTree($original_tree);
		$hash = $arrayShuffle['hash'];
		if(!array_key_exists($hash, $hash_pool)){
			echo " => testing<br>";
			$hash_pool[$hash] = true;
			$current_tree = $arrayShuffle['tree'];

			//Calculating position of string and arrow for adjustement
			$stringsElements = buildStringsElements($image, $arrayShuffle['tree'], $font_size, $font_file, $margin_x, $margin_y, $space_x, $space_y, $colors['categories']);
			$arrowsElements = buildArrowsElements($image, $stringsElements, $space_arrow, $colors['arrow']);

			//Count the number of intersection in the adjustement
			$array_intersection = countArrowsIntersection($arrowsElements);
			$count_intersect = $array_intersection['count_intersect'];
			$count_potential_intersect = $array_intersection['count_potential_intersect'];
			
			echo "$hash: $count_intersect intersection of $count_potential_intersect (".($count_potential_intersect > 0 ? $count_intersect*100/$count_potential_intersect : 0)." % intersect)<br>";

			if($count_intersect < $min_intersect){

				$min_intersect = $count_intersect;				
				$print_candidate['arrows'] = $arrowsElements;
				$print_candidate['strings'] = $stringsElements;

				if($min_intersect == 0)
					echo "<strong>Perfect tree found !</strong><br>";
				else
					echo "Better tree found!<br>";
			}
			else{
				echo "This tree is not the tree you are looking for<br>";
			}
		}
		else{
			echo " => already tested<br>";
		}

	}while($min_intersect > 0 && time() - $start < $limit_time);

	//Finallay, draw the chosen tree
	drawElements($image, $print_candidate['strings'], $print_candidate['arrows'], $font_size, $font_file, $colors);

	echo "<br>===== End of generation =====<br>";

	echo "Image generate in ".(time() - $start)." second: ".count($print_candidate['strings'])." elements, ".count($print_candidate['arrows'])." arrows and ".$count_intersect."/".$count_potential_intersect." intersection (".($count_potential_intersect > 0 ? $count_intersect*100/$count_potential_intersect : 0)." %)<br>";
	
	//Output image
	imagepng($image, $image_path);

	echo "<br><a href=\"".$GLOBALS['dns']."index.php?page=elements_tree\">Back</a>";
?>

