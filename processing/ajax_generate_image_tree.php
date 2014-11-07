<?php
	require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."lib".DIRECTORY_SEPARATOR."classloader.php");
	require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."lib".DIRECTORY_SEPARATOR."date_string_utils.php");
	require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."lib".DIRECTORY_SEPARATOR."image_generation_utils.php");
	require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."config.php");

	//All echo are catch to be output in a log file
	ob_start();

	$elements = array();
	if(isset($_POST['category']) && strlen($_POST['category']) > 0){
		$category = filter_var($_POST['category'], FILTER_SANITIZE_STRING);
		$elements =  ElementDao::getByCategory();
	}

	if(count($elements) == 0){
		$elements =  ElementDao::getAll();
	}

	// Customize generation here 
	$font_size = 20;
	$font_file = dirname(__FILE__).DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."lib".DIRECTORY_SEPARATOR."Calibri.ttf";
	$margin_x = 20;		//Horizontal space between Element and border
	$margin_y = 30;		//Vertical space between Element and border
	$space_x = 30;		//Horizontal space between Element
	$space_y = 60;		//Vertical space between Element
	$space_arrow = 3;	//Space between Element and start/end of arrows
	$length_arrow = 3;	//Length of arrows
	$width_arrow = 3;	//Width of arrows
	$limit_time = 15; 		//Limit time for generation
	$prefered_width = 1000;	//Prefered width of the image

	// Start generating the image 
	$start = time();

	echo "===== Start Generation =====<br>";
	echo tmspToDateLong(time())."<br>";

	//Buil the tree from the elements
	$original_tree = buildTree($elements);


	echo "<br>===== Tree =====<br>";
	echo "Building tree of ".count($elements)." elements<br>";
	printTree($original_tree);

	//Create the image	
	$width = max(getImageWidth($original_tree, $font_size, $font_file, $margin_x, $space_x), $prefered_width);
	$heigth = getImageHeight($original_tree, $font_size, $font_file, $margin_y, $space_y);
	$image = imagecreatetruecolor($width, $heigth);
	imageantialias($image, true);


	echo "<br>===== Image parameters =====<br>";
	echo "width= ".$width."px<br>";
	echo "heigth= ".$heigth."px<br>";
	echo "image_path= $tree_image_absolute_path<br>";
	echo "font_size= ".$font_size."px<br>";
	echo "font_file= $font_file<br>";
	echo "margin_x= ".$margin_x."px<br>";
	echo "margin_y= ".$margin_y."px<br>";
	echo "space_x= ".$space_x."px<br>";
	echo "space_y= ".$space_y."px<br>";
	echo "space_arrow= ".$space_arrow."px<br>";
	echo "length_arrow= ".$length_arrow."px<br>";
	echo "width_arrow= ".$width_arrow."px<br>";

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
	$attempt_number = 0;
	do{
		//Shuffle the tree to try a new adjustement of the tree
		$arrayShuffle = shuffleTree($original_tree);
		$hash = $arrayShuffle['hash'];
		if(!array_key_exists($hash, $hash_pool)){
			$hash_pool[$hash] = true;
			$current_tree = $arrayShuffle['tree'];

			//Calculating position of string and arrow for adjustement
			$stringsElements = buildStringsElements($image, $arrayShuffle['tree'], $font_size, $font_file, $margin_x, $margin_y, $space_x, $space_y, $colors['categories']);
			$arrowsElements = buildArrowsElements($image, $stringsElements, $space_arrow, $width_arrow, false, $colors['arrow']);

			//Count the number of intersection in the adjustement
			$array_intersection = countArrowsIntersection($arrowsElements);
			$count_intersect = $array_intersection['count_intersect'];
			$count_potential_intersect = $array_intersection['count_potential_intersect'];
			
			echo "$hash: $count_intersect intersection of $count_potential_intersect (".($count_potential_intersect > 0 ? $count_intersect*100/$count_potential_intersect : 0)." % intersect) : ";

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

		$attempt_number++;
	}while($min_intersect > 0 && time() - $start < $limit_time);

	//Finallay, draw the chosen tree
	$print_candidate['arrows'] = $arrowsElements = buildArrowsElements($image, $print_candidate['strings'], $space_arrow, $width_arrow, true, $colors['arrow']);
	drawElements($image, $print_candidate['strings'], $print_candidate['arrows'], $font_size, $font_file, $length_arrow, $width_arrow, $space_arrow, $colors);

	echo "<br>===== End of generation =====<br>";

	echo "Image generate in ".(time() - $start)." second after $attempt_number attempts: ".count($print_candidate['strings'])." elements, ".count($print_candidate['arrows'])." arrows and ".$count_intersect."/".$count_potential_intersect." intersection (".($count_potential_intersect > 0 ? $count_intersect*100/$count_potential_intersect : 0)." %)<br>";
	
	//Output image
	imagepng($image, $tree_image_absolute_path);

	$output = ob_get_contents();
	ob_clean();

	file_put_contents($tree_generation_log_path, $output);

	echo json_encode(array('time' => time() - $start, 'date' => tmspToDateLong(time()), 'attempt_number' => $attempt_number, 'output' => $output ));
?>

