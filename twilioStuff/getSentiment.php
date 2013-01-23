<?php
require_once 'firebase.php';
require_once 'Lymbix.php';
$comment = $_GET['text'];
$lymbix = new Lymbix("e46e988ca39ccf4b22287a6927bca3846985cf4d");
$response = ($lymbix->Tonalize($comment));
$data = $response->article_sentiment->sentiment;
patchFirebase('comments',$comment,'POST');
$rating=intval(file_get_contents("https://adr2370.firebaseio.com/current/rating/.json"));
if($data=="Positive") {
	$rating++;
} else if($data=="Negative") {
	$rating--;
}
patchFirebase('current/rating',$rating,"PUT");
if($rating<-5) {
	patchFirebase("current/skip","1","PUT");
}
echo $data;
?>