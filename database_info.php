
<?php

$isDev = strpos($_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'],'development');

if($isDev === FALSE ){
    // Nous sommes en Prod
    $dbhost="localhost";
    $dbuser="root";
    $dbpass="842w[AGUM~QWrR";
    $dbname="igmt";
    $dbparams = array();
}
else {
    // Nous sommes en Dev
    $dbhost="localhost";
    $dbuser="root";
    $dbpass="";
    $dbname="igmt";
    $dbparams = array(); 
}


?>