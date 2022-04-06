<?php

$merchantApiHost = 'https://merchant-api.ifood.com.br';


$clientId = '2c99ed49-478b-4959-b392-48828eda9954';
$clientSecret = 'oqy93k6snxjmgruuabkt82hz7fw5djuorlcxcaaapdtzo0zsa0neyz3ztqv452ein8d3j0jibusrejcox7mzkzoakzmokg93rc7';

////
//SOLICITANDO TOKEN DE ACESSO
////

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $merchantApiHost.'/authentication/v1.0/oauth/token',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => 'grantType=refresh_token&clientId='.$clientId.'&clientSecret='.$clientSecret,
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/x-www-form-urlencoded'
  ),
));

$response = curl_exec($curl);
$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);

$retorno = json_decode($response, true);

$accessToken = $retorno['accessToken'];


echo $accessToken.'<br>'.$httpcode ;

exit();





$merchantId = '86c364e5-aa30-499e-aeb1-a2d3ddfc2b3e';



$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $merchantApiHost.'/merchant/v1.0/merchants/'.$merchantId.'/status',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzUxMiJ9.eyJzdWIiOiIyYzk5ZWQ0OS00NzhiLTQ5NTktYjM5Mi00ODgyOGVkYTk5NTQiLCJhdWQiOlsic2hpcHBpbmciLCJjYXRhbG9nIiwiZmluYW5jaWFsIiwicmV2aWV3IiwibWVyY2hhbnQiLCJvcmRlciIsIm9hdXRoLXNlcnZlciJdLCJhcHBfbmFtZSI6ImFkbWluc3dlZXRjb25mZXR0eXRlc3RlYyIsIm93bmVyX25hbWUiOiJhZG1pbnN3ZWV0Y29uZmV0dHkiLCJzY29wZSI6WyJzaGlwcGluZyIsImNhdGFsb2ciLCJyZXZpZXciLCJtZXJjaGFudCIsIm9yZGVyIiwiY29uY2lsaWF0b3IiXSwiaXNzIjoiaUZvb2QiLCJtZXJjaGFudF9zY29wZSI6WyI4NmMzNjRlNS1hYTMwLTQ5OWUtYWViMS1hMmQzZGRmYzJiM2U6Y29uY2lsaWF0b3IiLCI4NmMzNjRlNS1hYTMwLTQ5OWUtYWViMS1hMmQzZGRmYzJiM2U6Y2F0YWxvZyIsIjg2YzM2NGU1LWFhMzAtNDk5ZS1hZWIxLWEyZDNkZGZjMmIzZTpyZXZpZXciLCI4NmMzNjRlNS1hYTMwLTQ5OWUtYWViMS1hMmQzZGRmYzJiM2U6c2hpcHBpbmciLCI4NmMzNjRlNS1hYTMwLTQ5OWUtYWViMS1hMmQzZGRmYzJiM2U6bWVyY2hhbnQiLCI4NmMzNjRlNS1hYTMwLTQ5OWUtYWViMS1hMmQzZGRmYzJiM2U6b3JkZXIiXSwiZXhwIjoxNjQ5MjM5OTQ2LCJpYXQiOjE2NDkyMjkxNDYsImp0aSI6IjJjOTllZDQ5LTQ3OGItNDk1OS1iMzkyLTQ4ODI4ZWRhOTk1NCIsIm1lcmNoYW50X3Njb3BlZCI6dHJ1ZSwiY2xpZW50X2lkIjoiMmM5OWVkNDktNDc4Yi00OTU5LWIzOTItNDg4MjhlZGE5OTU0In0.RCqTUCQkOBijUqQ9zSTodp1VQTqdXX6vwmbmusqqZc5kkFXpwRePxrO2N9p9e7zTJkuCmBRpqrijOV4eM6X9LknxgCO7OHM6aeTOZ1yofFGr8HMBmbdm5YDRoxKx_uFIIdqIz660WIgTCB3W_WLBcIOgt37tttrtLFlPmywIUXs'
  ),
));

$response = curl_exec($curl);

curl_close($curl);


$retorno = json_decode($response, true);


print_r($response);

echo '<hr>';

$array =  $retorno[0];

$app = $array['salesChannel'];
$state = $array['state'];

echo $app.' - '.$state;

echo '<hr>';


exit();
