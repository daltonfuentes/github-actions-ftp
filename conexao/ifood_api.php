<?php
ob_start();
session_start();

$usuario = 1;

if(isset($_POST['new_session']) && $_POST['new_session'] == true):
    require_once("conexao_hostgator.php");


    $sql = "SELECT accessToken FROM token_ifood WHERE usuario='$usuario'";
    $resultado = $conexaoAdmin->prepare($sql);	
    $resultado->execute();
    $contar = $resultado->rowCount();

    if($contar == 0):
        $retorno['erro']     = 0;
        $retorno['cod'] = $cod;
        echo json_encode($retorno);
        break;
    endif;







    $merchantApiHost = 'https://merchant-api.ifood.com.br';

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
        $accessToken = $retorno['accessToken'];
    elseif($httpcode == 401):
        $resposta['er'] = 'Não autorizado.';
        $resposta['er_cod'] = 401;
        echo json_encode($resposta);
        exit();
    else:
        $resposta['er'] = 'Erro inesperado.';
        $resposta['er_cod'] = 500;
        echo json_encode($resposta);
        exit();
    endif;

    ////
    // CONSULTA STATUS RESTAURANTE
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
        $accessToken = $retorno['accessToken'];
    elseif($httpcode == 401):
        $resposta['er'] = 'Não autorizado.';
        $resposta['er_cod'] = 401;
        echo json_encode($resposta);
        exit();
    else:
        $resposta['er'] = 'Erro inesperado.';
        $resposta['er_cod'] = 500;
        echo json_encode($resposta);
        exit();
    endif;



endif;