<?php
require_once 'firebase.php';
$number=$_GET['number'];
$room=$_GET['room'];
patchFirebase('numbers/'.$number.'/',$room,"PUT");
?>