<?php
$song=$_POST['song'];
$ch = curl_init('https://adr2370.firebaseio.com/songs.json');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
curl_setopt($ch, CURLOPT_POSTFIELDS, $song);
curl_setopt($ch, CURLINFO_HEADER_OUT, true);
$results = json_decode(curl_exec($ch));
?>