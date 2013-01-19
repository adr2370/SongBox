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
$length = floor($seconds/60) . " mins " . $seconds % 60 . " secs long";

$message = $songName. " is " . $length;
echo $message;
?>