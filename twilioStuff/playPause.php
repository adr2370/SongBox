<?php
require_once 'firebase.php';
$which=$_GET['which'];
$room=$_GET['room'];
if($which=="play") {
	patchFirebase($room.'/current/play',1,"PUT");
} else {
	patchFirebase($room.'/current/play',2,"PUT");
}
?>