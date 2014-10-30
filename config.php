<?php

	if(strcmp($_SERVER['DOCUMENT_ROOT'],"C:/Program Files (x86)/EasyPHP-DevServer-14.1VC9/data/localweb") == 0){
		//Local Raphaël
		$GLOBALS['dns'] = "http://127.0.0.1/igmt/";
	}
	else{
		//Serveur de test
		$GLOBALS['dns'] = "http://cluster015.ovh.net/~searchan/igmt/";
	}

	$force_css_compile= true;
	

	error_reporting(E_ALL);
	ini_set('display_errors','1'); 
	ini_set("log_errors", '1');
	ini_set("error_log", $_SERVER['DOCUMENT_ROOT']."/igmt/log/error.txt");
?>