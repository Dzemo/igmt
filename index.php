<?php
	
	require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."lib".DIRECTORY_SEPARATOR."lessmake.php");
	require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."lib".DIRECTORY_SEPARATOR."classloader.php");
	require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."config.php");

	$page = isset($_GET['page']) ? $_GET['page'] : 'elements_list';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--
-->
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Incremental Game Modeling Tool</title>
		<meta name="keywords" content="" />
		<meta name="description" content="" />
		<!-- JQUERY & JQUERY UI-->
		<script type="text/javascript" language="javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
		<link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
		<script type="text/javascript" language="javascript" src="js/jquery-ui.min.js"></script>
		<!-- SumoSelect -->
		<link rel="stylesheet" type="text/css" href="css/sumoselect.css">
		<script type="text/javascript" language="javascript" src="js/jquery.sumoselect.js"></script>	
		<!-- bPopup -->		
		<script type="text/javascript" language="javascript" src="js/jquery.bpopup.min.js"></script>	
		<!-- Noty -->
		<script type="text/javascript" language="javascript" src="js/jquery.noty.packaged.min.js"></script>
		<!-- jaxButton -->
		<script type="text/javascript" language="javascript" src="js/jquery.jaxbutton.js"></script>
		<!-- CSS  -->
		<link href="css/styles.css" rel="stylesheet" type="text/css" media="all" />
		<link href="css/category.css" rel="stylesheet" type="text/css" media="all" />
	</head>
	<body>
	<div id="wrapper">
		<div id="header">
			<div class="main-title"><h1><span>Incrementals Game Modeling Tool</span></h1></div>
			<ul class="menu">
                            <li>
                                <a href="index.php?page=elements_list" class="<?php echo ($page == 'elements_list' ? 'current' : ''); ?>">
                                    Elements list
                                </a>
                            </li>
                            <li>
                                <a href="index.php?page=elements_tree" class="<?php echo ($page == 'elements_tree' ? 'current' : ''); ?>">
                                    Elements tree
                                </a>
                            </li>
                            <li>
                                <a href="index.php?page=category_manager" class="<?php echo ($page == 'category_manager' ? 'current' : ''); ?>">
                                    Category manager
                                </a>
                            </li>
                                <button id="button-download-modele" class="button">
                                    Télécharger le json
                                </button>
                            <li>
			</ul>
		</div>
		<div id="content">
			<?php 
				switch($page){
					case 'elements_list':
						require_once("page/elements_list.php");
						break;
					case 'elements_tree':
						require_once("page/elements_tree.php");
						break;
					case 'category_manager':
						require_once("page/category_manager.php");
						break;		
					default:
						require_once("page/elements_list.php");
						break;				
				}
			?>			
		</div>
		<div id="push"></div>
	</div>
	<div id="footer">
		<span>IGMT | Flavio DEROO | Raphaël BIDEAU | <a href="LICENSE.md">Licence MIT</a></span>
	</div>
	</body>
	<script type="text/javascript">
                $(function(){
                        $('#button-download-modele').jaxButton({
                                url:"processing/ajax_generate_json.php",
                                before: function(dataset){
                                        return true;
                                },
                                done: function(dataset, sUrl, textStatus, jqXHR){

                                           //Creating new link node.
                                           var link = document.createElement('a');
                                           link.href = sUrl;

                                           if (link.download !== undefined){
                                               //Set HTML5 download attribute. This will prevent file from opening if supported.
                                               var fileName = sUrl.substring(sUrl.lastIndexOf('/') + 1, sUrl.length);
                                               link.download = fileName;
                                           }

                                           //Dispatching click event.
                                           if (document.createEvent) {
                                               var e = document.createEvent('MouseEvents');
                                               e.initEvent('click' ,true ,true);
                                               link.dispatchEvent(e);
                                               return true;
                                           }
                                    }
                            });
                });
                
               
 

		//see http://ned.im/noty/#options
		$.noty.defaults = {
		    layout: 'bottomRight',
		    theme: 'defaultTheme',
		    type: 'alert',
		    text: '', // can be html or string
		    dismissQueue: true, // If you want to use queue feature set this true
		    template: '<div class="noty_message"><span class="noty_text"></span><div class="noty_close"></div></div>',
		    animation: {
		        open: {height: 'toggle'},
		        close: {height: 'toggle'},
		        easing: 'swing',
		        speed: 500 // opening & closing animation speed
		    },
		    timeout: 5000, // delay for closing event. Set false for sticky notifications
		    force: false, // adds notification to the beginning of queue when set to true
		    modal: false,
		    maxVisible: 5, // you can set max visible notification for dismissQueue true option,
		    killer: false, // for close all notifications before show
		    closeWith: ['click'], // ['click', 'button', 'hover', 'backdrop'] // backdrop click will close all open notifications
		    callback: {
		        onShow: function() {},
		        afterShow: function() {},
		        onClose: function() {},
		        afterClose: function() {}
		    },
		    buttons: false // an array of buttons
		};
	</script>
</html>
