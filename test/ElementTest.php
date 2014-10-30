<?php
	require_once("../lib/classloader.php");

	$element2 = new Element("My Test Element 2", "test");
	$element2->setDescription("Element de teste central");
	echo $element2."<br>";

	echo "<br>Ajout de tags<br>";
	$element2->addTag("num2");
	$element2->addTag("test");
	echo $element2."<br>";

	echo "<br>Ajout d'un need et allow<br>";
	$element1 = new Element("My Test Element 1", "test");
	$element1->setDescription("Element de teste qui autorise element2");
	$link1 = new Link(1, $element1, $element2, Link::typeRequire);
	$arrayLink1 = $link1->toInnerLink();
	$element1->addAllow($arrayLink1['allow']);
	$element2->addNeed($arrayLink1['need']);

	
	$element3 = new Element("My Test Element 3", "test");
	$element3->setDescription("Element de teste qui necessite element2");
	$link2 = new Link(2, $element2, $element3, Link::typeRequire);
	$arrayLink2 = $link2->toInnerLink();
	$element2->addAllow($arrayLink2['allow']);
	$element3->addNeed($arrayLink2['need']);

	echo $element1."<br>";
	echo $element2."<br>";
	echo $element3."<br>";
	echo $link1."<br>";
	echo $link2."<br>";

	echo "<br>\$element1->trimedName()=".$element1->trimedName();
?>