<?php
ob_start();
session_start();

if(isset($_POST['new_session']) && $_POST['new_session'] == true):

    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://merchant-api.ifood.com.br/authentication/v1.0/oauth/token',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => 'grantType=client_credentials&clientId=2c99ed49-478b-4959-b392-48828eda9954&clientSecret=oqy93k6snxjmgruuabkt82hz7fw5djuorlcxcaaapdtzo0zsa0neyz3ztqv452ein8d3j0jibusrejcox7mzkzoakzmokg93rc7',
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/x-www-form-urlencoded'
    ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);

    $retorno = json_decode($response, true);

    $teste = $retorno['salesChannel'];
    echo $teste;
    exit();


endif;