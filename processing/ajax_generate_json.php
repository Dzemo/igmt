<?php
    	session_start();
	
	require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."lib".DIRECTORY_SEPARATOR."classloader.php");
	require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."config.php");
        
        $modele = array();
        
        //Récupération des élément
        $modele['element'] = ElementDao::getAll();        
        //Récupération des categories
        $modele['category'] = CategoryDao::getAll();   
        //Récupération des cost scaling
        $modele['cost_scaling'] = CostScalingDao::getAll();
        
        //Génération du Json
        $json = json_encode($modele, JSON_PRETTY_PRINT);
        
         //Création du fichier JSON et écriture dans le fichier
        $fileName = "modele".date("_d_m_y__H_i_s").".json";
        file_put_contents($modelePath.DIRECTORY_SEPARATOR.$fileName, $json);
        
        //En réponse on envoie le chemin du fichier a partir de l'index
        echo $modelePathFromIndex."/".$fileName;
?>