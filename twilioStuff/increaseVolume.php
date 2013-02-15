<?php
require_once 'firebase.php';
$number=$_GET['val'];
$room=$_GET['room'];
$oldVolume=file_get_contents("https://songbox.firebaseio.com/".$room."/playerdb/volume/.json");
$updateVal = 50;
if($number > 0)
{
	$updateVal = min(100,$number+$oldVolume);
}
else
{
	$updateVal = max(0,$number+$oldVolume);
}
patchFirebase('rooms/'.$room.'/playerdb/volume',$updateVal,"PUT");
?>