<?php
	function classLoader($classe)
	{
          $isDev = strpos($_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'],'development');
          if($isDev === FALSE ){
            require $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'igmt'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.$classe.'.class.php'; // On inclut la classe correspondante au paramètre passé.
	} else {
            require $_SERVER['DOCUMENT_ROOT'].'/development/igmt/class/'.$classe.'.class.php'; // On inclut la classe correspondante au paramètre passé.
        }
            
          }
	spl_autoload_register('classLoader'); // On enregistre la fonction en autoload pour qu'elle soit appelée dès qu'on instanciera une classe non déclarée.
?>