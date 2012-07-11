<?php

	$config = simplexml_load_file('../config.xml');
	
	$dbh = new PDO('mysql:host='.$config->data_base->myHost.';dbname='.$config->data_base->myDatabase_name, $config->data_base->myUser, $config->data_base->myPassword);

	function arrayToXML($array,$parent,$xml=null){
	
		$index = 0;

		if($xml == null){

			$xml = new SimpleXMLElement("<".$parent."/>");

			$child = $xml;

		}else{

			$child = $xml->addChild($parent);

		}

		if(gettype($array) == 'array'){

			reset($array);

			if(gettype(key($array)) == 'integer') unset($xml->$parent);

			foreach($array as $key=>$element){

				if(gettype($key) == 'integer'){

					$xml = arrayToXML($element,$parent,$xml);

				}else{

					$child = arrayToXML($element,$key,$child);

				}

			}

		}else{
		
			$index = ($xml->$parent->count())-1;

			$xml->$parent->$index = $array;

		}

		return $xml;

	}

	$xml = arrayToXML($_POST['datas'],$_POST['formName']);

	$stmt = $dbh->prepare("SELECT 1 FROM :table");

	$stmt->bindParam(':table', $_POST['formName']);
	
	$stmt->execute();

	$count = $stmt->rowCount();

	$sqlDatas = array();

	$sqlDatas['PrimaryKey']['type'] = "VARCHAR(255)";

	$sqlDatas['PrimaryKey']['content'] = $_POST['keys']['key'];

	foreach($_POST['keys']['value'] as $value){

		$pair = explode(":",$value);

		$sqlDatas[$pair[0]]['type'] = "VARCHAR(255)";

		$sqlDatas[$pair[0]]['content'] = $pair[1];

	}

	foreach($_POST['keys']['count'] as $count){

		$pair = explode(":",$count);

		$sqlDatas[$pair[0]]['type'] = "INT";

		$sqlDatas[$pair[0]]['content'] = $pair[1];

	}

	if($count>0){

		$columns = array();

		foreach($sqlDatas as $key=>$data){

			array_push($columns," ".$key." ".$data['type']);

		}

		$columns = implode(",",$columns);

		$create = $dbh->prepare("CREATE TABLE :table(:columns)");
		$create->bindParam(':table', $_POST['formName']);
		$create->bindParam(':columns', $columns);
		$create->execute();
		

	}

	$insertData = array();

	foreach($sqlDatas as $data){

		if($data['type'] == 'INT'){

			array_push($insertData, " ".$data['content']);

		}else{

			array_push($insertData, " '".$data['content']."'");
	
		}

	}

	$insertData = implode(",",$insertData);

	echo $insertData;

	$insert = $dbh->prepare("INSERT INTO :table VALUES (:columns)");
	$insert->bindParam(':table', $_POST['formName']);
	$insert->bindParam(':columns', $insertData);
	echo $insert->debugDumpParams();
	$insert->execute();

	
	$path = '../'.$config->myXML_directory_path;
	
	if(!file_exists($path.'/')) mkdir($path.'/');
	
	if(!file_exists($path.'/'.$_POST['formName'].'/')) mkdir($path.'/'.$_POST['formName'].'/');
	
	$path = $path.'/'.$_POST['formName'].'/'.$_POST['keys']['key'].'.xml';
	
	$handler = fopen($path,'w');
	
	fwrite($handler,$xml->asXML());

	echo $xml->asXML();

?>