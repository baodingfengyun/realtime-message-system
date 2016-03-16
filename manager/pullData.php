<?php

$topic = $_GET['topic'];
$token = $_GET['token'];
$fromNum = $_GET['fromNum'];
$num = $_GET['num'];
if(empty($fromNum)){
	$fromNum =1;
}
if(empty($num)){
	$num = 10;
}
if(!empty($topic) && !empty($token)){
	$getUrl = "http://127.0.0.1:6000/msg/data/".$topic."/".$token."/$fromNum/$num";
	$timeOut = 5;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $getUrl);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER,array('Accept-Encoding: gzip, deflate', 'Connection: Keep-Alive'));
	curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
	curl_setopt($ch, CURLOPT_TIMEOUT, $timeOut);
	$result = curl_exec($ch);
	curl_close($ch);
	
	echo trim($result);
}

?>