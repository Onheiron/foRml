<?php

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

	function SQLput($dbh,$table,$array){

		$stmt = $dbh->prepare("SELECT 1 FROM :table");
		$stmt->bindParam(':table', $table);
		$stmt->execute();

		$exists = $stmt->rowCount();

		$sqlDatas = array();

		$sqlDatas['PrimaryKey']['type'] = "VARCHAR(255)";

		$sqlDatas['PrimaryKey']['content'] = $array['key'];

		foreach($array['value'] as $value){

			$pair = explode(":",$value);

			$sqlDatas[$pair[0]]['type'] = "VARCHAR(255)";

			$sqlDatas[$pair[0]]['content'] = $pair[1];

		}

		foreach($array['count'] as $count){

			$pair = explode(":",$count);

			$sqlDatas[$pair[0]]['type'] = "INT";

			$sqlDatas[$pair[0]]['content'] = $pair[1];

		}

		if(!$exists){

			$columns = array();

			foreach($sqlDatas as $key=>$data){

				array_push($columns," ".$key." ".$data['type']);

			}

			$columns = implode(",",$columns);

			$create = $dbh->prepare("CREATE TABLE ".$table." (".$columns.")");
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

		$insert = $dbh->prepare("INSERT INTO ".$table." VALUES (".$insertData.")");
		$insert->execute();

	}

	function saveXML($xml,$path,$form_directory,$file_name){

		if(!file_exists($path.'/')) mkdir($path.'/');

		if(!file_exists($path.'/'.$form_directory.'/')) mkdir($path.'/'.$form_directory.'/');

		$path = $path.'/'.$form_directory.'/'.$file_name.'.xml';

		$handler = fopen($path,'w');

		fwrite($handler,$xml->asXML());

	}

?>