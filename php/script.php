<?php

	$config = simplexml_load_file('../config.xml');
	
	mysql_connect($config->data_base->myHost, $config->data_base->myUser, $config->data_base->myPassword) or die(mysql_error());
	mysql_select_db($config->data_base->myDatabase_name) or die(mysql_error());

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
	
	$path = '../'.$config->myXML_directory_path;
	
	mkdir($path.'/');
	
	mkdir($path.'/'.$_POST['formName'].'/');
	
	$path = $path.'/'.$_POST['formName'].'/'.$_POST['keys']['key'].'.xml';
	
	$handler = fopen($path,'w');
	
	fwrite($handler,$xml->asXML());

	echo $xml->asXML();

?>