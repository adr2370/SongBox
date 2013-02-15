<?php
require_once 'firebase.php';
$number=$_GET['number'];
$song=$_GET['song'];
$type=$_GET['type'];
$room=$_GET['room'];
try {
	$youtube=json_decode(file_get_contents("https://gdata.youtube.com/feeds/api/videos?q=".urlencode($song)."&alt=json"));
	$id=str_replace("http://gdata.youtube.com/feeds/api/videos/","",$youtube->{'feed'}->{'entry'}[0]->{'id'}->{'$t'});
	$isThere=file_get_contents("https://songbox.firebaseio.com/rooms/".$room."/songs/".$id."/.json");
	if($isThere=="null") {
		$highestPriority=intval(file_get_contents("https://songbox.firebaseio.com/rooms/".$room."/highestPriority/.json"));
		$priority=$highestPriority+1;
		$priorityData=array("highestPriority" => $priority);
		$length=$youtube->{'feed'}->{'entry'}[0]->{'media$group'}->{'yt$duration'}->{'seconds'};
		$name=$youtube->{'feed'}->{'entry'}[0]->{'title'}->{'$t'};
		$thumbnail=$youtube->{'feed'}->{'entry'}[0]->{'media$group'}->{'media$thumbnail'}[0]->{'url'};
		$numViews=$youtube->{'feed'}->{'entry'}[0]->{'yt$statistics'}->{'viewCount'};
		$videoData = array("length" => $length, "name" => $name, "thumbnail" => $thumbnail, "numViews" => $numViews, ".priority" => $priority, "type" => $type, $number => 1);
		patchFirebase('rooms/'.$room.'/',$priorityData,"PATCH");
		patchFirebase('rooms/'.$room.'/songs/'.$id,$videoData,"PUT");
	} else {
		if(file_get_contents("https://songbox.firebaseio.com/rooms/".$room."/songs/".$id."/".$number."/.json")=="null") {
			$currPriority=intval(file_get_contents("https://songbox.firebaseio.com/rooms/".$room."/songs/".$id."/.priority/.json"));
			$priority=$currPriority-5;
			$numberData = array($number => 1);
			patchFirebase('rooms/'.$room.'/songs/'.$id,$numberData,"PATCH");
			patchFirebase('rooms/'.$room.'/songs/'.$id.'/.priority',$priority,"PUT");
		}
	}
	$name=$youtube->{'feed'}->{'entry'}[0]->{'title'}->{'$t'};
	echo $name;
} catch (Exception $e) {
    echo $e;
}
?>