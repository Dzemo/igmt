<?php
	//DNS for redirecting
	if(strcmp($_SERVER['DOCUMENT_ROOT'],"C:/Program Files (x86)/EasyPHP-DevServer-14.1VC9/data/localweb") == 0){
		//Local Raphaël
		$GLOBALS['dns'] = "http://127.0.0.1/igmt/";
	}
	else{
		//Serveur de test
		$GLOBALS['dns'] = "http://cluster015.ovh.net/~searchan/igmt/";
	}

	//Force less compilation
	$force_css_compile= true;
	
	//File path for image and log generation of elements tree
	$tree_image_relative_path = "images/generation/elements_tree.png"; //From index
	$tree_image_absolute_path = dirname(__FILE__).DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR."generation".DIRECTORY_SEPARATOR."elements_tree.png";
	$tree_generation_log_path = dirname(__FILE__).DIRECTORY_SEPARATOR."log".DIRECTORY_SEPARATOR."tree_generation.log";

        $modelePath = dirname(__FILE__).DIRECTORY_SEPARATOR."modele";
        $modelePathFromIndex = "modele";

	error_reporting(E_ALL);
	ini_set('display_errors','1'); 
	ini_set("log_errors", '1');
	ini_set("error_log", $_SERVER['DOCUMENT_ROOT']."/igmt/log/error.txt");
?>