<?php
require_once 'firebase.php';
$which=$_GET['which'];
patchFirebase('current/'.$which,1,"PUT");
?>