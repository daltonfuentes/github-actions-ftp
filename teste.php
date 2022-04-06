<?php

$_POST['new_session'] = true;

if (isset($_POST['new_session']) && $_POST['new_session'] == true) :
    require_once("./conexao/functions.php");
    $usuario = 1;

    $retorno = array();
 
    $outToken = accessToken($usuario);

    if(empty($outToken['accessToken'])):
        $retorno['erro'] = $outToken['erro'];
        $retorno['mensagem']  = $outToken['mensagem'];
        echo json_encode($retorno);
        exit();
    else:
        $accessToken = $outToken['accessToken'];
    endif;

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://merchant-api.ifood.com.br/merchant/v1.0/merchants',
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

    curl_close($curl);
    echo $response;

endif;
