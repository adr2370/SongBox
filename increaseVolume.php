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
$number=$_GET['val'];
$oldVolume=file_get_contents("https://adr2370.firebaseio.com/playerdb/volume/.json");
patchFirebase('playerdb/volume',$oldVolume+$number,"PUT");
?>