<?php
	require_once("lib/lessmake.php");
	require_once("lib/classloader.php");
	require_once("config.php");
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
		<link href="css/category.php" rel="stylesheet" type="text/css" media="all" />
	</head>
	<body>
	<div id="wrapper">
		<div id="header">
			<span class="main-title">Incrementals Game Modeling Tool</span>
			<ul class="menu">
				<li><a href="index.php?page=elements_list">Elements list</a></li>
				<li><a href="index.php?page=elements_tree">Elements tree</a></li>
				<li><a href="index.php?page=TestElement">TestElement</a></li>
				<li><a href="index.php?page=TestCategory">TestCategory</a></li>
			</ul>
		</div>
		<div id="content">
			<?php 
				$page = isset($_GET['page']) ? $_GET['page'] : null;
				switch($page){
					case 'elements_list':
						require_once("page/elements_list.php");
						break;
					case 'elements_tree':
						require_once("page/elements_tree.php");
						break;
					case 'TestElement':
						require_once("test/TestElement.php");
						break;
					case 'TestCategory':
						require_once("test/TestCategory.php");
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

		$(function(){
			<?php 
				if(isset($_SESSION['noty'])){
					$json_noty_array = json_decode($_SESSION['noty']);
					if(is_array($json_noty_array)){
						foreach ($json_noty_array as $json_noty) {
							echo "noty(".$json_noty.");\n";
						}
					}
					unset($_SESSION['noty']);
				}
			?>
		});
	</script>
</html>
