<?php

	require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."config.php");
	require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."less.inc.php");

	$lessc = new lessc;
	$css_file = dirname(__FILE__).DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."css".DIRECTORY_SEPARATOR."styles.css";
	$less_file = dirname(__FILE__).DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."css".DIRECTORY_SEPARATOR."styles.less";

	try{
		if(isset($force_css_compile) && $force_css_compile == true){
			$lessc->setPreserveComments(true);
			$lessc->compileFile($less_file,$css_file);
		}
		else{
			$lessc->checkedCompile($less_file,$css_file);	
		}
	}catch(exception $e){
		echo "An error occur while compiling less file : " . $e->getMessage();
	}
?>