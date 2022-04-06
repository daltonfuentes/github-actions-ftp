<?php

$usuario = 1;

require_once("./conexao/conexao_hostgator.php");

$date = new DateTime();
$dateComparacao = date_format($date, 'YmdHis');


$sql = "SELECT accessToken FROM token_ifood WHERE usuario='$usuario' AND expire>'$dateComparacao'";
$resultado = $conexaoAdmin->prepare($sql);	
$resultado->execute();
$contar = $resultado->rowCount();

if($contar == 0):
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
        $token = $retorno['accessToken'];
        $expiresIn = $retorno['expiresIn'];

        $date = new DateTime();
        date_add($date, date_interval_create_from_date_string($expiresIn.' second'));
        $expire = date_format($date, 'YmdHis');

        $sql = 'INSERT INTO token_ifood (usuario, accessToken, expire) VALUES (:usuario, :accessToken, :expire)';
        $stmt = $conexaoAdmin->prepare($sql);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->bindParam(':accessToken', $token);
        $stmt->bindParam(':expire', $expire);
        $resposta = $stmt->execute();

        if(!$resposta):
            $resposta['er'] = 'Tivemos um erro interneo e nao foi possivel finalizar o cadastro!';
            $resposta['er_cod'] = 600;
            echo json_encode($resposta);
            exit();
        else:
            $accessToken = $retorno['accessToken'];
            echo 'Token criado: '.$accessToken;
        endif;
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
else:
    $exibe = $resultado->fetch(PDO::FETCH_OBJ);
    $accessToken = $exibe->accessToken;

    echo 'Token já existe: '.$accessToken;
endif;
