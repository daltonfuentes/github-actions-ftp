<?php

function accessToken() {
    require_once("conexao_hostgator.php");

    $out = array();

    $date = new DateTime();
    $dateComparacao = date_format($date, 'YmdHis');

    $sql = "SELECT accessToken FROM token_ifood WHERE expire>'$dateComparacao'";
    $resultado = $conexao->prepare($sql);	
    $resultado->execute();
    $contar = $resultado->rowCount();

    if($contar == 0):
        ////
        // PEGA 'clientId' . 'clientSecret'
        ////

        $clientId = '2c99ed49-478b-4959-b392-48828eda9954';
        $clientSecret = 'oqy93k6snxjmgruuabkt82hz7fw5djuorlcxcaaapdtzo0zsa0neyz3ztqv452ein8d3j0jibusrejcox7mzkzoakzmokg93rc7';

        ////
        // SOLICITA TOKEN DE ACESSO
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
            CURLOPT_POSTFIELDS => 'grantType=client_credentials&clientId='.$clientId.'&clientSecret='.$clientSecret,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if($httpcode == 200):
            $retorno = json_decode($response, true);
            $token = $retorno['accessToken'];
            $expiresIn = $retorno['expiresIn'];
            $expiresIn = $expiresIn-60;

            $date = new DateTime();
            date_add($date, date_interval_create_from_date_string($expiresIn.' second'));
            $expire = date_format($date, 'YmdHis');

            $sql = 'INSERT INTO token_ifood (accessToken, expire) VALUES (:accessToken, :expire)';
            $stmt = $conexao->prepare($sql);
            $stmt->bindParam(':accessToken', $token);
            $stmt->bindParam(':expire', $expire);
            $resposta = $stmt->execute();

            if(!$resposta):
                $out['mensagem'] = 'Erro inesperado.';
                $out['erro'] = 600;
                $out['accessToken'] = 0;
                return $out;
            else:
                $out['erro'] = 0;
                $out['accessToken'] = $retorno['accessToken'];
                return $out;
            endif;
        else:
            $out['mensagem'] = 'Erro inesperado.';
            $out['erro'] = 1;
            $out['code'] = $httpcode;
            return $out;
        endif;
    else:
        $exibe = $resultado->fetch(PDO::FETCH_OBJ);
        $out['erro'] = 0;
        $out['accessToken'] = $exibe->accessToken;
        return $out;
    endif;
};

function merchantStatus($accessToken) {
    require_once("conexao_hostgator.php");

    $out = array();

    ////
    // CONSULTA STATUS DO RESTAURANTE
    ////

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
            'Authorization: Bearer '.$accessToken
        ),
    ));

    $response = curl_exec($curl);
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if($httpcode == 200):
        $retorno = json_decode($response, true);
        $out['erro'] = 0;
        $out['state'] = $retorno[0]['state'];
        $out['title'] = $retorno[0]['message']['title'];
        $out['subtitle'] = $retorno[0]['message']['subtitle'];
        return $out;
    else:
        $out['mensagem'] = 'Erro inesperado.';
        $out['erro'] = 1;
        $out['code'] = $httpcode;
        return $out;
    endif;
};

$merchantId = '86c364e5-aa30-499e-aeb1-a2d3ddfc2b3e';
$teste = polling($merchantId);
var_dump($teste);

function polling($merchantId){
    $merchantApiHost = 'https://merchant-api.ifood.com.br';

    $out = array();

    $outToken = accessToken();
    $accessToken = $outToken['accessToken'];

    ////
    // FAZ POLLING
    ////

    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => $merchantApiHost.'/order/v1.0/events:polling',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
        'x-polling-merchants: '.$merchantId,
        'Authorization: Bearer '.$accessToken
    ),
    ));

    $response = curl_exec($curl);
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if($httpcode == 200 || $httpcode == 204):
        $retorno = json_decode($response, true);
        $out['polling'] = $retorno;
        $out['erro'] = 0;
        return $out;
    else:
        $out['mensagem'] = 'Erro inesperado.';
        $out['erro'] = 1;
        $out['code'] = $httpcode;
        return $out;
    endif;
};

function acknowledgment($send){
    require_once("conexao_hostgator.php");

    $out = array();

    $outToken = accessToken();
    $accessToken = $outToken['accessToken'];

    $curl = curl_init();
    
    curl_setopt_array($curl, array(
      CURLOPT_URL => $merchantApiHost.'/order/v1.0/events/acknowledgment',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => $send,
      CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer '.$accessToken,
        'Content-Type: application/json'
      ),
    ));
    
    $response = curl_exec($curl);
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    
    if($httpcode == 202):
        $out['erro'] = 0;
        return $out;
    else:
        $out['mensagem'] = 'Erro inesperado.';
        $out['erro'] = 1;
        $out['code'] = $httpcode;
        return $out;
    endif;
};

/*

$orderId = '688eff25-1353-423a-aeb2-356b91472c69';

$merchantApiHost = 'https://merchant-api.ifood.com.br';

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => $merchantApiHost.'/order/v1.0/orders/'.$orderId,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzUxMiJ9.eyJzdWIiOiIyYzk5ZWQ0OS00NzhiLTQ5NTktYjM5Mi00ODgyOGVkYTk5NTQiLCJhdWQiOlsic2hpcHBpbmciLCJjYXRhbG9nIiwiZmluYW5jaWFsIiwicmV2aWV3IiwibWVyY2hhbnQiLCJvcmRlciIsIm9hdXRoLXNlcnZlciJdLCJhcHBfbmFtZSI6ImFkbWluc3dlZXRjb25mZXR0eXRlc3RlYyIsIm93bmVyX25hbWUiOiJhZG1pbnN3ZWV0Y29uZmV0dHkiLCJzY29wZSI6WyJzaGlwcGluZyIsImNhdGFsb2ciLCJyZXZpZXciLCJtZXJjaGFudCIsIm9yZGVyIiwiY29uY2lsaWF0b3IiXSwiaXNzIjoiaUZvb2QiLCJtZXJjaGFudF9zY29wZSI6WyI4NmMzNjRlNS1hYTMwLTQ5OWUtYWViMS1hMmQzZGRmYzJiM2U6Y29uY2lsaWF0b3IiLCI4NmMzNjRlNS1hYTMwLTQ5OWUtYWViMS1hMmQzZGRmYzJiM2U6Y2F0YWxvZyIsIjg2YzM2NGU1LWFhMzAtNDk5ZS1hZWIxLWEyZDNkZGZjMmIzZTpyZXZpZXciLCI4NmMzNjRlNS1hYTMwLTQ5OWUtYWViMS1hMmQzZGRmYzJiM2U6c2hpcHBpbmciLCI4NmMzNjRlNS1hYTMwLTQ5OWUtYWViMS1hMmQzZGRmYzJiM2U6bWVyY2hhbnQiLCI4NmMzNjRlNS1hYTMwLTQ5OWUtYWViMS1hMmQzZGRmYzJiM2U6b3JkZXIiXSwiZXhwIjoxNjQ5Mjk5ODM0LCJpYXQiOjE2NDkyODkwMzQsImp0aSI6IjJjOTllZDQ5LTQ3OGItNDk1OS1iMzkyLTQ4ODI4ZWRhOTk1NCIsIm1lcmNoYW50X3Njb3BlZCI6dHJ1ZSwiY2xpZW50X2lkIjoiMmM5OWVkNDktNDc4Yi00OTU5LWIzOTItNDg4MjhlZGE5OTU0In0.O8qFgN0gNsS-UElWY5fMBKOtcb7usheuYDKkbcPMSyvEGosPsDWUNB8tjs4tdqZQ7V6OmHTE5jahqF1Z1SPvN6vCKKBaouJ-Ev5PCBKlqXWn3XrxX65U6DSGAuKK5DlGV_U74noJRwzdZefR9KABrB2NKubyWg3A56oGl6PUvUg'
    ),
));

$response = curl_exec($curl);
$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);

if($httpcode == 200):
    $retorno = json_decode($response, true);
    $items = $retorno['items'];

    var_dump($items);
    echo '<hr>';
    var_dump($retorno);
    echo '<hr>';
    var_dump($response);
endif;

*/