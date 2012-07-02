<?php

	function arrayToXML($array,$parent,$xml=null){

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

			$xml->$parent = $array;

		}

		return $xml;

	}

	//$xml = arrayToXML($_POST['datas'],$_POST['formName']);

	echo json_encode($_POST['datas']);

?>