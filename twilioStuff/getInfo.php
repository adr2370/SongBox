<?php
$room=$_GET['room'];
$json = file_get_contents("http://adr2370.firebaseio.com/".$room."/current/.json"); 
$data = json_decode($json);
$songName = $data->name;
$seconds = $data->length;
$length = floor($seconds/60) . " mins " . $seconds % 60 . " secs long";

$message = $songName. " is " . $length;
echo $message;
?>