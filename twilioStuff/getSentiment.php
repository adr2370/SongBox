<?php
require_once 'firebase.php';
require_once 'Lymbix.php';
$comment = $_GET['text'];
$room=$_GET['room'];
$lymbix = new Lymbix("e46e988ca39ccf4b22287a6927bca3846985cf4d");
$response = ($lymbix->Tonalize($comment));
$data = $response->article_sentiment->sentiment;
$rating=file_get_contents("https://songbox.firebaseio.com/".$room."/current/rating/.json");
if($rating!="null") {
	$rating=intval($rating);
	patchFirebase($room.'/comments',$comment,'POST');
	if($data=="Positive") {
		$rating++;
	} else if($data=="Negative") {
		$rating--;
	}
	patchFirebase($room.'/current/rating',$rating,"PUT");
	if($rating<-5) {
		patchFirebase($room."/current/skip","1","PUT");
	}
	echo $data;
}
?>