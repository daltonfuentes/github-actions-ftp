<?php

accessToken(1);

function accessToken($usuario) {
    require_once("conexao_hostgator.php");

    $out = array();

    $date = new DateTime();
    $dateComparacao = date_format($date, 'YmdHis');

    $sql = "SELECT accessToken FROM token_ifood WHERE usuario='$usuario' AND expire>'$dateComparacao'";
    $resultado = $conexao->prepare($sql);	
    $resultado->execute();
    $contar = $resultado->rowCount();

    if($contar == 0):
        ////
        // PEGA 'merchantApiHost' . 'clientId' . 'clientSecret'
        ////
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
            $stmt = $conexao->prepare($sql);
            $stmt->bindParam(':usuario', $usuario);
            $stmt->bindParam(':accessToken', $token);
            $stmt->bindParam(':expire', $expire);
            $resposta = $stmt->execute();

            if(!$resposta):
                $out['mensagem'] = 'Erro inesperado.';
                $out['erro'] = 600;
                $out['accessToken'] = 0;
                return $out;
            else:
                $out['mensagem'] = '';
                $out['erro'] = 0;
                $out['accessToken'] = $retorno['accessToken'];
                return $out;
            endif;
        elseif($httpcode == 401):
            $out['mensagem'] = 'Não autorizado.';
            $out['erro'] = 401;
            $out['accessToken'] = 0;
            return $out;
        else:
            $out['mensagem'] = 'Erro inesperado.';
            $out['erro'] = 500;
            $out['accessToken'] = 0;
            return $out;
        endif;
    else:
        $exibe = $resultado->fetch(PDO::FETCH_OBJ);
        $out['mensagem'] = '';
        $out['erro'] = 0;
        $out['accessToken'] = $exibe->accessToken;
        return $out;
    endif;
}