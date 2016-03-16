<?php

$post_data = array();
$post_data['topic'] = $_POST["topic"];
$post_data['key'] = $_POST["token"];
$post_data['content'] = $_POST["content"];
if(!empty($_POST["topic"]) && !empty($_POST["token"]) && !empty($_POST["content"])){
	$url='http://127.0.0.1:6000/msg/push/data';
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);	
	curl_exec($ch);
}
?>