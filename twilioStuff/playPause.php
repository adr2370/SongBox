<?php
require_once 'firebase.php';
$which=$_GET['which'];
if($which=="play") {
	patchFirebase('current/play',1,"PUT");
} else {
	patchFirebase('current/play',2,"PUT");
}
?>