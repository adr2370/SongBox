<?php
//example http://.../getSentiment.php()?text=<insert comment here>
require_once 'Lymbix.php';
$comment = $_GET['text'];
$lymbix = new Lymbix("e46e988ca39ccf4b22287a6927bca3846985cf4d");
$response = ($lymbix->Tonalize($comment));
$data = $response->article_sentiment->sentiment;
echo $data;
?>