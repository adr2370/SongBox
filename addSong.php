<?php
$song=$_GET['song'];
echo $song;
try {
	$data = array($song => "1");
	$chlead = curl_init();
	curl_setopt($chlead, CURLOPT_URL, 'https://adr2370.firebaseio.com/songs/.json');
	curl_setopt($chlead, CURLOPT_USERAGENT, 'SugarConnector/1.4');
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
	    'Content-Type: application/json',                                                                                
	    'Content-Length: ' . strlen($data_string))                                                                       
	);
	curl_setopt($chlead, CURLOPT_VERBOSE, 1);
	curl_setopt($chlead, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($chlead, CURLOPT_CUSTOMREQUEST, "PATCH"); 
	curl_setopt($chlead, CURLOPT_POSTFIELDS,json_encode($data));
	curl_setopt($chlead, CURLOPT_SSL_VERIFYPEER, 0);
	$response = curl_exec($chlead);
	$chleadapierr = curl_errno($chlead);
	$chleaderrmsg = curl_error($chlead);
	curl_close($chlead);
echo $response;
} catch (Exception $e) {
    echo $e;
}
?>