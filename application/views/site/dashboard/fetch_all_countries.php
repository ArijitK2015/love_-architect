<?php

	ini_set('max_execution_time', 0); //0=NOLIMIT

	$current_location 	= __DIR__.'/';
	$connect_string 	= 'localhost';
	$connect_username 	= '00903957_0000028';
	$connect_password 	= 'loyaltycamp%freewilder12345678';
	$connect_db 		= '00903957_0000028';
	$link 			= mysqli_connect($connect_string, $connect_username, $connect_password, $connect_db) or die(mysqli_connect_error());
	
	// Turn off all error reporting
	error_reporting(0);
	
	// Report simple running errors
	error_reporting(E_ERROR | E_WARNING | E_PARSE);
	
	// Reporting E_NOTICE can be good too (to report uninitialized
	// variables or catch variable name misspellings ...)
	error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
	
	// Report all errors except E_NOTICE
	error_reporting(E_ALL & ~E_NOTICE);
	
	// Report all PHP errors (see changelog)
	error_reporting(E_ALL);
	
	// Report all PHP errors
	error_reporting(-1);
	
	// Same as error_reporting(E_ALL);
	ini_set('error_reporting', E_ALL);

	$countries = array();
	$i=0;
	//$sql = mysqli_query($link, 'SELECT * FROM country WHERE status = 1');
	$sql = 'SELECT * FROM country';
	$result = mysqli_query($link, $sql);
	
	// output data of each row
	while($row = mysqli_fetch_assoc($result))
	{
		$countries[$i]['name']			= ucwords($row['country_name']);
		$countries[$i]['iso']			= ucwords($row['iso']);
		$countries[$i]['iso3']			= ucwords($row['iso3']);
		$countries[$i]['language_code']	= ucwords($row['language_code']);
		
		$i++;
	}

	echo json_encode($countries);
?>