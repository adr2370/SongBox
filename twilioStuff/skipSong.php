<?php
require_once 'firebase.php';
$room=$_GET['room'];
patchFirebase($room."/current/skip",1,"PUT");
?>