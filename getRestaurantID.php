<?php
$zip = $_GET['zip'];

function getRestID($resZip)
{
	$url = "https://r.ordr.in/dl/ASAP/".$resZip."/College%20Station/1%20Main%20Street";
	echo $url;
	echo "<br><br>";
	$obj = file_get_contents($url);
	$json = json_decode($obj);
	//print_r($json);
	for($i = 0; $i < 10; $i++)
	{
		$restName = $json[$i]->na;
		$restID = $json[$i]->id;
		if (strpos(strtolower($restName),'pizza') !== false) 
		{
	    	echo $restName.", ".$restID;
	    	echo "<br>";
	    	return $restID;
		}
	}
}

function orderPizza($restID)
{
	echo $restID;
}

$rid = getRestID($zip);
orderPizza($rid);
?>