<?php
require_once 'firebase.php';
$room=$_GET['room'];
patchFirebase("rooms/".$room."/current/skip",1,"PUT");
?>