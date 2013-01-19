<?php
$json = file_get_contents("http://adr2370.firebaseio.com/songs.json"); 
$data = json_decode($json);
$queue = "";
$counter = 0;
foreach($data as $key=>$value) 
{
	if($counter > 1)
	{
		break;
	}
	else
	{
		echo ($counter+1). ". " .$data->$key->name."<br>";
		$counter++;		
	}	
}
?>