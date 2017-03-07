<?php
	// Report all PHP errors
	error_reporting(-1);
		
	// Same as error_reporting(E_ALL);
	ini_set('error_reporting', E_ALL);
		
	// connect to mongodb
	require 'vendor/autoload.php'; // include Composer's autoloader

	$client 		= new MongoDB\Client("mongodb://localhost:27017");
	$collection 	= $client->lovearchitectdev->test;
	
	$result 		= $collection->find( [], ['limit' => 2, 'skip' => 1]);
	//$result 		= $collection->insertOne( [ 'name' => 'Hinterland', 'brewery' => 'BrewDog' ] );
	echo '<pre>'; print_r($result); echo '</pre>';
	echo "Inserted with Object ID '{".$result->getInsertedId()."}'";
	
	//$result = $collection->find( [ 'name' => 'Hinterland', 'brewery' => 'BrewDog' ] );
	//
	//foreach ($result as $entry) {
	//    echo $entry['_id'], ': ', $entry['name'], "\n";
	//}
		
	echo '<pre>'; print_r(get_loaded_extensions()); echo '</pre>';
	
	if (class_exists('Mongo')) {
		echo 'MongoDB is installed';
	}
	else {
		echo 'MongoDB is not installed';
	}

?>
