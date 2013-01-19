<?php
function patchFirebase($location,$data,$type)
{
	$chlead = curl_init();
	curl_setopt($chlead, CURLOPT_URL, 'https://adr2370.firebaseio.com/'.$location.'/.json');
	curl_setopt($chlead, CURLOPT_USERAGENT, 'SugarConnector/1.4');
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	    'Content-Type: application/json'
	));
	curl_setopt($chlead, CURLOPT_VERBOSE, 1);
	curl_setopt($chlead, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($chlead, CURLOPT_CUSTOMREQUEST, $type); 
	curl_setopt($chlead, CURLOPT_POSTFIELDS,json_encode($data));
	curl_setopt($chlead, CURLOPT_SSL_VERIFYPEER, 0);
	$response = curl_exec($chlead);
	$chleadapierr = curl_errno($chlead);
	$chleaderrmsg = curl_error($chlead);
	curl_close($chlead);
}
$song=$_GET['song'];
try {
	$highestPriority=intval(file_get_contents("https://adr2370.firebaseio.com/highestPriority/.json"));
	$priority=$highestPriority+1;
	$priorityData=array("highestPriority" => $priority);
	$youtube=json_decode(file_get_contents("https://gdata.youtube.com/feeds/api/videos?q=".urlencode($song)."&alt=json"));
	$id=str_replace("http://gdata.youtube.com/feeds/api/videos/","",$youtube->{'feed'}->{'entry'}[0]->{'id'}->{'$t'});
	$length=$youtube->{'feed'}->{'entry'}[0]->{'media$group'}->{'yt$duration'}->{'seconds'};
	$name=$youtube->{'feed'}->{'entry'}[0]->{'title'}->{'$t'};
	$thumbnail=$youtube->{'feed'}->{'entry'}[0]->{'media$group'}->{'media$thumbnail'}[0]->{'url'};
	$numViews=$youtube->{'feed'}->{'entry'}[0]->{'yt$statistics'}->{'viewCount'};
	$videoData = array("length" => $length, "name" => $name, "thumbnail" => $thumbnail, "numViews" => $numViews, ".priority" => $priority);
	patchFirebase('',$priorityData,"PATCH");
	patchFirebase('songs/'.$id,$videoData,"PUT");
	echo $name;
} catch (Exception $e) {
    echo $e;
}
?>