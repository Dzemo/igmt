<?php
	/**
	 * @package igmt.utils
	 * @author Raphaël BIDEAU
	 * @version 1.0
	 *
	 * Offer some method to generate the element tree image
	 * string = name of an element of the tree, so name of an Element
	 * arrow  = arrow between to string representing a need link between them
	 */

	require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."line_intersect_check.php");
	
	/**
	 * Shuffle the $tree and return an hash representing this shuffle
	 * @param  array $tree 
	 * @return array Array contaigin the hash representing this tree and the tree
	 */
	function shuffleTree($original_tree){
		$hash = "";
		$shuffle_tree = array();

		foreach ($original_tree as $level => $original_tree_level) {
			$hash .= "#";
			
			$shuffle_tree_level = array();
			$key_set = array_keys($original_tree_level);
			shuffle($key_set);
			foreach ($key_set as $index) {
				$hash .= "@".$original_tree_level[$index]->getName();
				$shuffle_tree_level[] = $original_tree_level[$index];
			}

			$shuffle_tree[$level] = $shuffle_tree_level;
		}
		//echo "<br>Hash: $hash";
		return array('hash' => md5($hash), 'tree' => $shuffle_tree);
	}

	/**
	 * Count the number of intersection bewteen arrow with different start and end and the total potential number of intersection
	 * @param  array $arrowsElements 
	 * @return array                 ['count_intersect', 'count_potential_intersect']
	 */
	function countArrowsIntersection($arrowsElements){
		$count_intersect = 0;
		$count_potential_intersect = 0;

		for($i = 0; $i < count($arrowsElements); $i++){
			$arrowA = $arrowsElements[$i];

			$sa = array('start' => array('x' => $arrowA['start_x'], 'y' => $arrowA['start_y']),'end' => array('x' => $arrowA['end_x'], 'y' => $arrowA['end_y']));

			for($j = $i+1; $j < count($arrowsElements); $j++){
				$arrowB = $arrowsElements[$j];

				//Check only arrow with different start and end
				if($arrowA['element_start'] != $arrowB['element_start'] && $arrowA['element_end'] != $arrowB['element_end']){
					$count_potential_intersect++;

					$sb = array('start' => array('x' => $arrowB['start_x'], 'y' => $arrowB['start_y']),'end' => array('x' => $arrowB['end_x'], 'y' => $arrowB['end_y']));

					if(LineIntersectionChecker::doLinesIntersect($sa, $sb)){
						$count_intersect++;
					}
				}
			}
		}

		return array('count_intersect' => $count_intersect, 'count_potential_intersect' => $count_potential_intersect);	
	}

	/**
	 * Draw on the image the two array of string and arrows
	 * @param  resource $image           
	 * @param  array $stringsElements 
	 * @param  array $arrowsElements  
	 * @param  int $font_size
	 * @param  string $font_file
	 * @param  int $length_arrow
	 * @param  int $width_arrow
	 * @param  int $space_arrow
	 * @param  array $colors
	 */
	function drawElements($image, $stringsElements, $arrowsElements, $font_size, $font_file, $length_arrow, $width_arrow, $space_arrow, $colors){
		echo "<br>===== Start Drawning =====<br>";

		//Draw each arrows
		foreach ($arrowsElements as $arrow) {
			echo "Drawing ".$arrow['element_start']->getName()." -> ".$arrow['element_end']->getName()."<br>";
			arrow($image, $arrow['start_x'], $arrow['start_y'], $arrow['end_x'], $arrow['end_y'], $length_arrow, $width_arrow, $arrow['color']);
		}

		//Draw each string
		foreach ($stringsElements as $string) {
			echo "Drawning ".$string['element']->getName()." [".$string['x']." ; ".$string['y']."]<br>";
			imagefilledrectangle ($image, $string['x'], $string['y'], $string['x']+$string['width'], $string['y']+$string['height']+$space_arrow, $colors['background-alpha']);
			imagettftext($image, $font_size, 0, $string['x'], $string['y']+$string['height'], $string['color'], $font_file, $string['element']->getName());
		}
	}

	/**
	 * Build an array containing the position for all arrow
	 * @param  resource 	$image           
	 * @param  array 		$strings	Elements Array of all string to be print
	 * @param  int 			$space_arrow Space between text and start/end of arrows
	 * @param  int          $width_arrow Width of arrows
	 * @param  boolean		$space_between_end If there should be a space between arrow end
	 * @param  resource 	$color           
	 * @return array              		 Array with all the informations of the arrows ['start_x', 'start_y', 'end_x', 'end_y', 'color', 'element_start', 'element_end'] indexed by name of elements        
	 */
	function buildArrowsElements($image, $stringsElements, $space_arrow, $width_arrow, $space_between_end, $color){
		$arrowsElements = array();

		foreach ($stringsElements as $name => $treeElem) {
			$element = $treeElem['element'];

			$innerLinks = array_merge($element->getNeed(), array($element->getRegress(), $element->getExtend()));
			$nbr_links = count($innerLinks);

			$arrow_width = $width_arrow * 3;
			$link_index = 1;

			if($space_between_end){
				//If there is space between arrow end, sort the link in order to avoid intersection
				
				usort($innerLinks, function($a, $b) use ($stringsElements) {
								if($a == null && $b == null) return 0;
								else if($a == null) return -1;
								else if($b == null) return 1;
						        
						        $start_a = ($stringsElements[$a->getTarget()->getName()]['x']+($stringsElements[$a->getTarget()->getName()]['width'])/2 );
								$start_b = ($stringsElements[$b->getTarget()->getName()]['x']+($stringsElements[$b->getTarget()->getName()]['width'])/2 );
								return $start_a - $start_b;
						    });
			}

			foreach ($innerLinks as $link) {
				if($link != null){
					$target = $link->getTarget();					

					$start_x = ($stringsElements[$target->getName()]['x']+($stringsElements[$target->getName()]['width'])/2 );
					$start_y = $stringsElements[$target->getName()]['y'] + $stringsElements[$target->getName()]['height'] + $space_arrow;

					$end_x = ($treeElem['x']+($treeElem['width'])/2);
					if($space_between_end)
					  $end_x = $end_x- (count($innerLinks)*$arrow_width/2) + ($link_index * $arrow_width);
					$end_y = $treeElem['y'] - $space_arrow;

					$arrowsElements[] =  array(	
							'start_x' 	=> $start_x,
							'start_y'	=> $start_y,
							'end_x'		=> $end_x,
							'end_y'		=> $end_y,
							'color'		=> $color,
							'element_start' => $element,
							'element_end'	=> $target
						);

					$link_index++;
					//echo "Arrow ".$element->getName()." -> ".$target->getName()."<br>";
				}
			}
		}

		return $arrowsElements;
	}

	/**
	 * Build the array containing all the string position and their colors
	 * @param  ressource $image
	 * @param  array $tree     Array containing the tree of the element
	 * @param  int $font_size
	 * @param  string $font_file
	 * @param  int $margin_x   Margin from the border
	 * @param  int $margin_y   Margin from the border
	 * @param  int $space_x    Space between Element
	 * @param  int $space_y    Space between Element
	 * @param  array $colors
	 * @return array           Array with all the informations of the element ['x', 'y', 'element', 'height', 'width', 'color'] indexed by name of elements
	 */
	function buildStringsElements($image, $tree, $font_size, $font_file, $margin_x, $margin_y, $space_x, $space_y, $colors){
		$treePrintedElements = array();
		$offset_y = $margin_y-1;

		//For each level of the tree
		foreach($tree as $deep => $tree_level){

			//Computing real x spacing because $space_x is only the minimum (when there is less element on a level)
			$available_width = imagesx($image) - 2*$margin_x;
			foreach ($tree_level as $index => $element){
				$available_width -= getTextSize($element->getName(), $font_size, $font_file)['width'];
			}
			$true_space_x = $available_width / count($tree_level);
			$true_offset_x = $margin_x-1 + $true_space_x/2;

			//For each elements on this level
			foreach ($tree_level as $index => $element) {
				$height = getTextSize($element->getName(), $font_size, $font_file)['height'];
				$width = getTextSize($element->getName(), $font_size, $font_file)['width'];

				$treePrintedElements[$element->getName()] = array(
						'x' => $true_offset_x,
						'y' => $offset_y,
						'element' => $element,
						'height' => $height,
						'width' => $width,
						'color' => getColorForCategory($element->getCategory(), $colors)
					);
				
				//echo "String: ".$element->getName()." [".$treePrintedElements[$element->getName()]['x']." ; ".$treePrintedElements[$element->getName()]['y']."]<br>";
				$true_offset_x += $true_space_x + $width;
			}
			
			$offset_y += $space_y + $height;
		}

		return $treePrintedElements;
	}

	/**
	 * Draw an arrow line form [x1, y1] to [x2, y1] on the image $im of the color $color.
	 * The arrow will have a length of $alength and width $awidth
	 * @param  resource $im      
	 * @param  int $x1      
	 * @param  int $y1      
	 * @param  int $x2      
	 * @param  int $y2      
	 * @param  int $alength 
	 * @param  int $awidth  
	 * @param  array $colors
	 */
	function arrow($im, $x1, $y1, $x2, $y2, $alength, $awidth, $color) {

	    $distance = sqrt(pow($x1 - $x2, 2) + pow($y1 - $y2, 2));

	    $dx = $x2 + ($x1 - $x2) * $alength / $distance;
	    $dy = $y2 + ($y1 - $y2) * $alength / $distance;

	    $k = $awidth / $alength;

	    $x2o = $x2 - $dx;
	    $y2o = $dy - $y2;

	    $x3 = $y2o * $k + $dx;
	    $y3 = $x2o * $k + $dy;

	    $x4 = $dx - $y2o * $k;
	    $y4 = $dy - $x2o * $k;

	    imageline($im, $x1, $y1, $dx, $dy, $color);
	    imagefilledpolygon($im, array($x2, $y2, $x3, $y3, $x4, $y4), 3, $color);
	}

	/**
	 * Build the array containing the tree from a array of Elements
	 * @param  array $elements 
	 * @return array           
	 */
	function buildTree($elements){
		$tree = array();

		//Building the root (Element withou need)
		$elements_left_in_array = count($elements);
		/*foreach ($elements as $index => $element) {
			if($element != null && isLinkMatch($element, $tree)){
				$tree[0][] = $element;
				$elements[$index] = null;
				$elements_left_in_array--;
			}
		}*/

		//Building each level
		//$tree[0] = array();
		$deep = 0;
		while($elements_left_in_array > 0){
			$tree[$deep] = array();

			foreach ($elements as $index => $element) {

				if($element != null && isLinkMatch($element, $tree)){
					$tree[$deep][] = $element;
					$elements[$index] = null;
					$elements_left_in_array--;
				}
			}

			//If no Elements has been add, there is a problem in the tree : some Elements can't have their need fulfill so we have to stop
			if(count($tree[$deep]) == 0){
				throw new Exception("Error while building the three : some element can't have their need fulfill");
			}

			$deep++;
		}
		
		return $tree;
	}

	/**
	 * Find the corresponding colors in the array $colors, indexed by category name
	 * @param  Category $category
	 * @param  array $colors  
	 * @return ressource          
	 */
	function getColorForCategory(Category $category, $colors){
		if(array_key_exists($category->getName(), $colors))
			return $colors[$category->getName()];
		else if(array_key_exists('default', $colors))
			return $colors['default'];
		else
			return null;
	}

	/**
	 * Check wether an element has is need fulfill with the element already in the tree
	 * @param  Element  $element 
	 * @param  array  $tree    
	 * @return boolean          
	 */
	function isLinkMatch($element, $tree){
		//Array of Need, Extend and EvolveFrom as InnerLink
		$arrayLink = array_merge($element->getNeed(), array($element->getExtend(), $element->getRegress()));
		$stringLinks = "";
		foreach ($arrayLink as $link) {
			if($link != null){
				$stringLinks .= " ".$link->getTarget()->getName();
				$find = false;
				
				//Looking only higher in the tree
				$i = count($tree)-2;
				while(!$find && $i >= 0){
					foreach ($tree[$i] as $tree_elem) {
						if($tree_elem->getName() == $link->getTarget()->getName()){
							$i = null;
							$find = true;
							break;
						}
					}
					$i--;
				}

				if(!$find){
					return false;
				}
			}
		}

		echo "All link match for ".$element->getName().":".(strlen($stringLinks) > 0 ? $stringLinks : " no link required")."<br>";

		return true;
	}

	/**
	 * Compute de height of the image from the size of the font and the elements in the tree
	 * @param  array $tree   
	 * @param  int $font_size
	 * @param  string $font_file
	 * @param  int $margin_y        
	 * @return int               
	 */
	function getImageHeight($tree, $font_size, $font_file, $margin_y, $space_y){
		$count = count($tree);
		return 2*$margin_y + $count*$font_size + ($count-1)*$space_y;
	}

	/**
	 * Compute de width of the image from the size of the font and the elements in the tree
	 * For debug use
	 * @param  array $tree     
	 * @param  int $font_size
	 * @param  string $font_file     
	 * @param  int $margin_x 
	 * @return int           
	 */
	function getImageWidth($tree, $font_size, $font_file, $margin_x, $space_x){

		//Search for the deep with the most elements
		$max_elements = 0;
		$max_size = 0;
		foreach ($tree as $deep => $tree_level){

			$current_size = 0;
			foreach($tree_level as $tree_elem){
				$current_size += strlen($tree_elem->getName());
			}

			if($current_size > $max_size){
				$max_size = $current_size;
				$max_deep = $deep;
			}

		}

		//Compute the width
		$width = 2*$margin_x;
		$first = true;
		foreach ($tree[$max_deep] as $element) {
			if($first){
				$first = false;
				$width += getTextSize($element->getName(), $font_size, $font_file)['width'];
			}
			else{
				$width += $space_x + getTextSize($element->getName(), $font_size, $font_file)['width'];
			}
		}

		return $width;
	}

	/**
	 * Calculate the width / height of the text with the specified font and size
	 * @param  string $text      
	 * @param  int $font_size 
	 * @param  string $font_file Path to font file
	 * @return arra            ['width', 'height']
	 */
	function getTextSize($text, $font_size, $font_file){
		$type_space = imagettfbbox($font_size, 0, $font_file, $text);
		$image_width = abs($type_space[4] - $type_space[0]);
		$image_height = abs($type_space[5] - $type_space[1]);

		return array('width' => $image_width, 'height' => $image_height);
	}

	/**
	 * Convert a css color code (as "#000000" or "#000") to an array containing rgb values 
	 * @param  string $hex 
	 * @return array
	 */
	function hex2rgb($hex) {
		$hex = str_replace("#", "", $hex);

		if(strlen($hex) == 3) {
			$r = hexdec(substr($hex,0,1).substr($hex,0,1));
			$g = hexdec(substr($hex,1,1).substr($hex,1,1));
			$b = hexdec(substr($hex,2,1).substr($hex,2,1));
		} else {
			$r = hexdec(substr($hex,0,2));
			$g = hexdec(substr($hex,2,2));
			$b = hexdec(substr($hex,4,2));
		}
		$rgb = array($r, $g, $b);
		//return implode(",", $rgb); // returns the rgb values separated by commas
		return $rgb; // returns an array with the rgb values
	}

	/**
	 * Print the specified deep of the tree
	 * @param  array $tree 
	 * @param  int $deep 
	 */
	function printTreeLevel($tree, $deep){
		echo "Level ".$deep.":";
		foreach ($tree[$deep] as $tree_level) {
			echo " ".$tree_level->getName();
		}
		echo "<br>";		
	}
	
	/**
	 * Print all the tree (use printTreeLevel)
	 * For debug use
	 * @param  array $tree
	 */
	function printTree($tree){
		foreach ($tree as $deep => $tree_level) {
			printTreeLevel($tree, $deep);
		}
	}	
?>