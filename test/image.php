<html><body>
<?php
	require_once("../lib/classloader.php");

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

?>
	
	<div style="width: 100%; height: 100%; background-color: black;">
		<img src="tree.png" />
	</div>
</body></html>

<?php
	///////////////
	//FONCTIONS //
	///////////////
	
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

	    if( $alength > 1 )
	        arrow( $im, $x1, $y1, $x2, $y2, $alength - 1, $awidth - 1, $color );

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
	    imageline($im, $x3, $y3, $x4, $y4, $color);
	    imageline($im, $x3, $y3, $x2, $y2, $color);
	    imageline($im, $x2, $y2, $x4, $y4, $color);
	}

	/**
	 * Print all the name of the element in the tree with the specified font 
	 * @param  ressource $image
	 * @param  array $tree     Array containing the tree of the element
	 * @param  int $font     
	 * @param  int $margin_x   Margin from the border
	 * @param  int $margin_y   Margin from the border
	 * @param  int $space_x    Space between Element
	 * @param  int $space_y    Space between Element
	 * @param  array $colors
	 * @return array           Array with all the informations of the printed element ['x', 'y', 'element', 'height', 'width', 'color']
	 *                         indexed by name of elements
	 */
	function drawTreeElements($image, $tree, $font,  $margin_x, $margin_y, $space_x, $space_y, $colors){
		$treePrintedElements = array();
		$offset_y = $margin_y-1;

		//For each level of the tree
		foreach($tree as $deep => $tree_level){
			$offset_x = $margin_x-1;

			//For each elements on this level
			foreach ($tree_level as $index => $element) {
				$height = imagefontheight($font);
				$width = strlen($element->getName()) * imagefontwidth($font);

				$treePrintedElements[$element->getName()] = array(
						'x' => $offset_x,
						'y' => $offset_y,
						'element' => $element,
						'height' => $height,
						'width' => $width,
						'color' => getColorForCategory($element->getCategory(), $colors)
					);
				
				//echo "$name: x=".$treeElem['x']." y=".$treeElem['y']."<br>";
				//print the string
				imagestring($image, $font, $treePrintedElements[$element->getName()]['x'], $treePrintedElements[$element->getName()]['y'], $element->getName(), $treePrintedElements[$element->getName()]['color']);

				$offset_x += $space_x + $width;
				$offset_y += $space_y + $height;
			}
		}

		return $treePrintedElements;
	}

	/**
	 * Build the array containing the tree from a array of Elements
	 * @param  array $elements 
	 * @return array           
	 */
	function buildTree($elements){
		$tree = array();

		//Building the root (Element withou need)
		$tree[0] = array();
		$elements_left_in_array = count($elements);
		foreach ($elements as $index => $element) {
			if($element != null && count($element->getNeed()) == 0){
				$tree[0][] = $element;
				$elements[$index] = null;
				$elements_left_in_array--;
			}
		}

		//Building each level
		$deep = 1;
		while($elements_left_in_array > 0){
			$tree[$deep] = array();

			for($index = 0; $index < count($elements); $index++) {
				$element = $elements[$index];

				if($element != null && isNeedMatch($element, $tree)){
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
	 * @param  string $category
	 * @param  array $colors  
	 * @return ressource          
	 */
	function getColorForCategory($category, $colors){
		if(array_key_exists($category, $colors))
			return $colors[$category];
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
	function isNeedMatch($element, $tree){
		foreach ($element->getNeed() as $need) {
			$find = false;
			
			//Looking only higher in the tree
			$i = count($tree)-2;
			while(!$find && $i >= 0){
				foreach ($tree[$i] as $tree_elem) {
					if($tree_elem->getName() == $need->getTarget()->getName()){
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

		return true;
	}

	/**
	 * Print the specified deep of the tree
	 * @param  array $tree 
	 * @param  int $deep 
	 */
	function printTreeLevel($tree, $deep){
		echo "Deep ".$deep.":";
		foreach ($tree[$deep] as $tree_level) {
			echo " ".$tree_level->getName();
		}
		echo "<br>";		
	}

	/**
	 * Compute de height of the image from the size of the font and the elements in the tree
	 * @param  array $tree         
	 * @param  int $font 
	 * @param  int $margin_y        
	 * @return int               
	 */
	function getImageHeight($tree, $font, $margin_y, $space_y){
		$count = count($tree);
		return 2*$margin_y + $count*imagefontheight($font) + ($count-1)*$space_y;
	}

	/**
	 * Compute de width of the image from the size of the font and the elements in the tree
	 * For debug use
	 * @param  array $tree     
	 * @param  int $font     
	 * @param  int $margin_x 
	 * @return int           
	 */
	function getImageWidth($tree, $font, $margin_x, $space_x){

		//Search for the deep with the most elements
		$max_elements = 0;
		$max_deep = 0;
		foreach ($tree as $deep => $tree_level){
			if(count($tree_level) > $max_elements){
				$max_elements = count($tree_level);
				$max_deep = $deep;
			}
		}

		//Compute the width
		$width = 2*$margin_x;
		$first = true;
		foreach ($tree[$max_deep] as $element) {
			if($first){
				$first = false;
				$width += strlen($element->getName()) * imagefontwidth($font);
			}
			else{
				$width += $space_x + strlen($element->getName()) * imagefontwidth($font);
			}
		}

		return $width;
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