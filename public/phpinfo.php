<?php
$url = "http://152.111.25.65:4400/Published/2021/Volksblad/2021/02/24/Stories/VB/QFenstof_4_0_1597015675.xml";

$login = 'system';
$password = 'T5eCBaoUtZ8XzNhk';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
$result = curl_exec($ch);
curl_close($ch);  
echo($result);