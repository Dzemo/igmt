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
		<!-- JQUERY && JQUERY UI-->
		<script type="text/javascript" language="javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
		<link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
		<script type="text/javascript" language="javascript" src="js/jquery-ui.min.js"></script>
		<!-- CSS GENERAL -->
		<link href="css/styles.css" rel="stylesheet" type="text/css" media="all" />
		<!-- JS GENERAL -->		
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
</html>
