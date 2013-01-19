<?php
$json = file_get_contents("http://adr2370.firebaseio.com/songs.json"); 
$data = json_decode($json);
$arr = array();
foreach($data as $key=>$value) 
{
	array_push($arr, $key);
}
$firstKey = $arr[0];
//print_r($data);
$songName = $data->$firstKey->name;
$seconds = $data->$firstKey->length;
/*echo $songName."<br>";
echo $songDuration;*/
$length = floor($seconds/60) . " minutes and " . $seconds % 60 . " seconds long";

$message = $songName. " is " . $length;
echo $message;

//print_r($arr);
/*$songName = $data[0];
print_r($songName);*/
//$songDuration = $data[0]['length'];
/*echo $songName."<br>";
echo $songDuration;*/
?>