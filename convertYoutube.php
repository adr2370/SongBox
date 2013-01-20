<?php
$vid=$_GET['vid'];
$command = "youtube2mp3 http://www.youtube.com/watch?v=".$vid." ".$vid.".mp3";
echo $command;
echo exec($command);
?>
