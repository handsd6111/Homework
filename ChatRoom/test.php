<?php
$url = "http://localhost/Action/ChatRoomProfile.php";
// echo $url;
$ch = curl_init();
$header[] = "Cache-Control: no-cache";
$header[] = "Pragma: no-cache";
$header[] = "Authorization: Bearer " . "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2NDU0MzkwNzcsIm5iZiI6MTY0NTQzOTA3NywiZXhwIjoxNjQ1NDQwODc3LCJhY2NvdW50IjoidGVzdGJvdDEifQ.xSkak2JX2Q6-_i8yVaK4UtJPaIScofkMjkv7g7E_XkY";
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
// curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_URL, $url);
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// curl_setopt($ch, CURLOPT_POST, true);
// curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array("abc" => "123", "def" => "456")));
$output = curl_exec($ch);
curl_close($ch);

var_dump($output);
