<?php
	/**
	 * Fichier contenant des fonctions pour transformer les dates en chaines de caractere
	 * @package Utils
	 */
	/**
	 * Transforme un timestamps en chaine de caractère suivant un format (par défaut "d/m/Y")
	 * et une timezone (par défaut "Europe/Paris") représentant une date
	 * @example 07/10/2014
	 * @param  int $timestamp
	 * @param  string $format Optionnal
	 * @param  string $timezone Optionnal
	 * @return string
	 */
	function tmspToDate($timestamp, $format = "d/m/Y", $timezone="Europe/Paris"){
		$date = new DateTime();
		$date->setTimestamp($timestamp);
		$date->setTimezone(new DateTimeZone($timezone));
		return $date->format($format);
	}
	/**
	 * Transforme un timestamps en chaine de caractère suivant le format "l j M G:i"
	 * et une timezone (par défaut "Europe/Paris") représentant une date et heure
	 * Exemple Mardi 7 Oct 19:25
	 * @param  int $timestamp
	 * @param  string $timezone Optionnal
	 * @return string
	 */
	function tmspToDateLong($timestamp, $timezone="Europe/Paris"){
		$date = new DateTime();
		$date->setTimestamp($timestamp);
		$date->setTimezone(new DateTimeZone($timezone));
		return $date->format("l j M G:i");
	}
	
	/**
	 * Transforme un timestamps en chaine de caractère suivant un format (par défaut "H:i")
	 * et une timezone (par défaut "Europe/Paris") représentant une heure
	 * Exemple 19:25
	 * @param  int $timestamp
	 * @param  string $format Optionnal
	 * @param  string $timezone Optionnal
	 * @return string
	 */
	function tmspToTime($timestamp, $format = "H:i", $timezone="Europe/Paris"){
		$date = new DateTime();
		$date->setTimestamp($timestamp);
		$date->setTimezone(new DateTimeZone($timezone));
		return $date->format($format);
	}
	
	/**
	 * Convertie un temps en minute en minute'heure''
	 * @param  int $time   Le temps en minute
	 * @param  string $format format de sorti, par défaut '%d\'%d\'\''
	 * @return strong         le temps formaté
	 */
	function convertToMinSec($time, $format = '%d\'%d\'\'') {
		    settype($time, 'integer');
		    if ($time < 1) {
		        return;
	    }
	    $minutes = floor($time / 60);
	    $seconds = ($time % 60);
	    return sprintf($format, $minutes, $seconds);
	}
?>