<?php
	require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."lib".DIRECTORY_SEPARATOR."classloader.php");

	/**
	 * Generate the css file for all category
	 * @param  array $allCategories Optionnal array of all categories. If the array is not provided, then all categories are retrived from the database
	 * @param  string $output_path  Optionnal output path for css file. Default = dirname(__FILE__)."..".DIRECTORY_SEPARATOR."css".DIRECTORY_SEPARATOR."category.css";	
	 */
	function generateCssCategory($allCategories = null, $output_path = null){
		
		if($allCategories == null || !is_array($allCategories))
			$allCategories = CategoryDao::getAll();

		if($output_path == null)
			$output_path = dirname(__FILE__).DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."css".DIRECTORY_SEPARATOR."category.css";
		
 		unlink($output_path);
		
		$css = "";
		foreach ($allCategories as $category) {
			$css .= $category->cssClass()."\n";
		}

		file_put_contents($output_path, $css);
	}
?>