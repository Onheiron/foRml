<?php

	// Read the config.xml file for DB and folder configuration
	$config = simplexml_load_file('../config.xml');

	// Connect to your DB with PDO
	$dbh = new PDO('mysql:host='.$config->data_base->myHost.';dbname='.$config->data_base->myDatabase_name, $config->data_base->myUser, $config->data_base->myPassword);

	// include foRml library functions
	include 'foRmlLB.php';

	//create your XML file form input datas
	$xml = arrayToXML($_POST['datas'],$_POST['formName']);

	// save primary datas in mySQL database
	SQLput($dbh,$_POST['formName'],$_POST['keys']);

	// store the generated XML in myXMLFolder/myFormName/myPrimaryKey.xml
	$path = '../'.$config->myXML_directory_path;
	saveXML($xml,$path,$_POST['formName'],$_POST['keys']['key']);

	echo $xml->asXML();

?>