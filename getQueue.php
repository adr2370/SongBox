<?php
$json = file_get_contents("http://adr2370.firebaseio.com/songs/.json?format=export"); 
$data = json_decode($json);
$queue = "";
$counter = 0;

$largestPriority = 0;
$largestName = "";

$arr = array();
foreach($data as $key=>$value) 
{	
	$arr[] = $data->$key->{'.priority'};	
}
sort($arr);

$arrLen = count($arr);

if($arrLen > 0)
{
	$firstP = $arr[0];
	foreach($data as $key=>$value) 
	{
		$name = $data->$key->name;	
		$priority = $data->$key->{'.priority'};	
		if($priority == $firstP)
		{
			echo "1. " .$name."<br>";	
			break;	
		}
	}
	if($arrLen > 1)
	{
		$secondP = $arr[1];
		foreach($data as $key=>$value) 
		{
			$name = $data->$key->name;	
			$priority = $data->$key->{'.priority'};	
			if($priority == $secondP)
			{
				echo "2. " .$name;
				break;
			}
		}
	}
}
?>