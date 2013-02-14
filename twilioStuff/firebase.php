<?php
function patchFirebase($location,$data,$type)
{
	set_time_limit(10000);
	$chlead = curl_init();
	curl_setopt($chlead, CURLOPT_URL, 'https://songbox.firebaseio.com/'.$location.'/.json');
	curl_setopt($chlead, CURLOPT_USERAGENT, 'SugarConnector/1.4');
	curl_setopt($chlead, CURLOPT_HTTPHEADER, array(
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
?>