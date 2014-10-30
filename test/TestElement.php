<?php
/**
	 * @author Raphaël Bideau - 3iL
	 * @package Test
	 *
	 * Test Embarcation et EmbarcationDao
	 */

	require_once("../lib/classloader.php");

	/* Test getAll*/
	echo "<br/>ElementDao::getAll()<br/>";
	$array = ElementDao::getAll();
	foreach ($array as $elem) {
		echo "$elem<br/>";
	}
?>