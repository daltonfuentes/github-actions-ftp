<?php

function accessToken(){
    require("conexao_hostgator.php");

    $out = array();

    $date = new DateTime();
    $dateComparacao = date_format($date, 'YmdHis');

    $sql = "SELECT accessToken FROM token_ifood WHERE expire>:dateComparacao";
    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':dateComparacao', $dateComparacao);	
    $stmt->execute();
    $contar = $stmt->rowCount();

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
        $exibe = $stmt->fetch(PDO::FETCH_OBJ);
        $out['erro'] = 0;
        $out['accessToken'] = $exibe->accessToken;
        return $out;
    endif;
};

function merchantStatus($accessToken){
    require("conexao_hostgator.php");

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
            "Authorization: Bearer $accessToken"
        ),
    ));

    $response = curl_exec($curl);
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if($httpcode == 200):
        $retorno = json_decode($response, true);
        $out['code'] = $httpcode;
        $out['state'] = $retorno[0]['state'];
        $out['title'] = $retorno[0]['message']['title'];
        $out['subtitle'] = $retorno[0]['message']['subtitle'];
        $out['validations'] = array();

        $outValidations = $retorno[0]['validations'];

        foreach($outValidations as $inVali){
            $inVali = (array) $inVali;

            $out['validations'][$inVali['id']] = array(
                "state" => $inVali['state'],
                "message" => array(
                    "title" => $inVali['message']['title'],
                    "subtitle" => $inVali['message']['subtitle'],
                    "description" => $inVali['message']['description']
                )
            );

        };
        return $out;
    else:
        $out['mensagem'] = 'Erro inesperado.';
        $out['code'] = $httpcode;
        return $out;
    endif;
};

function polling($merchantId, $accessToken){
    $merchantApiHost = 'https://merchant-api.ifood.com.br';

    $out = array();

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
        "x-polling-merchants: $merchantId",
        "Authorization: Bearer $accessToken"
    ),
    ));

    $response = curl_exec($curl);
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if($httpcode == 200 || $httpcode == 204):
        $retorno = json_decode($response);
        $out['polling'] = $retorno;
        $out['code'] = $httpcode;
        return $out;
    else:
        $out['mensagem'] = 'Erro inesperado.';
        $out['code'] = $httpcode;
        return $out;
    endif;
};

function acknowledgment($send, $accessToken){
    $merchantApiHost = 'https://merchant-api.ifood.com.br';

    $out = array();

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
        "Authorization: Bearer $accessToken",
        'Content-Type: application/json'
    ),
    ));
    
    $response = curl_exec($curl);
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    
    if($httpcode == 202):
        $out['code'] = $httpcode;
        return $out;
    else:
        $out['mensagem'] = 'Erro inesperado.';
        $out['code'] = $httpcode;
        return $out;
    endif;
};

function orderDetails($orderId, $accessToken){
    $merchantApiHost = 'https://merchant-api.ifood.com.br';

    $out = array();

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
            "Authorization: Bearer $accessToken"
        ),
    ));
    
    $response = curl_exec($curl);
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if($httpcode == 200):
        $retorno = json_decode($response);
        $out['details'] = $retorno;
        $out['code'] = $httpcode;
        return $out;
    else:
        $out['mensagem'] = 'Erro inesperado.';
        $out['code'] = $httpcode;
        return $out;
    endif;
};

function orderConfirm($orderId, $accessToken){
    $merchantApiHost = 'https://merchant-api.ifood.com.br';

    $out = array();

    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => $merchantApiHost.'/order/v1.0/orders/'.$orderId.'/confirm',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => 'grantType=client_credentials',
    CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer $accessToken"
    ),
    ));

    $response = curl_exec($curl);
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if($httpcode == 202):
        $out['code'] = $httpcode;
        return $out;
    else:
        $out['mensagem'] = 'Erro inesperado.';
        $out['code'] = $httpcode;
        return $out;
    endif;
};

function orderDespatched($orderId, $accessToken){
    $merchantApiHost = 'https://merchant-api.ifood.com.br';

    $out = array();

    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => $merchantApiHost.'/order/v1.0/orders/'.$orderId.'/dispatch',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => 'grantType=client_credentials',
    CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer $accessToken",
    ),
    ));

    $response = curl_exec($curl);
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if($httpcode == 202):
        $out['code'] = $httpcode;
        return $out;
    else:
        $out['mensagem'] = 'Erro inesperado.';
        $out['code'] = $httpcode;
        return $out;
    endif;
};

function orderCancel($orderId, $accessToken, $cancellationCode, $reason){
    $merchantApiHost = 'https://merchant-api.ifood.com.br';

    $out = array();

    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => $merchantApiHost.'/order/v1.0/orders/'.$orderId.'/requestCancellation',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>'{
        "cancellationCode": "'.$cancellationCode.'",
        "reason": "'.$reason.'"
    }',
    CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer $accessToken",
        "Content-Type: application/json"
    ),
    ));

    $response = curl_exec($curl);
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if($httpcode == 202):
        $out['code'] = $httpcode;
        return $out;
    else:
        $out['mensagem'] = 'Erro inesperado.';
        $out['code'] = $httpcode;
        return $out;
    endif;
};

function orderRequestDriver($orderId, $accessToken){
    $merchantApiHost = 'https://merchant-api.ifood.com.br';

    $out = array();

    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => $merchantApiHost.'/order/v1.0/orders/'.$orderId.'/requestDriver',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => 'grantType=client_credentials',
    CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer $accessToken",
    ),
    ));

    $response = curl_exec($curl);
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if($httpcode == 202):
        $out['code'] = $httpcode;
        return $out;
    else:
        $out['mensagem'] = 'Erro inesperado.';
        $out['code'] = $httpcode;
        return $out;
    endif;
};






function errorLog($message){
    error_log($message . PHP_EOL, 3, 'myLogError.log');
    error_log($message, 1,"daltonfuentes2020@gmail.com","From: webmaster@sweetconfetty.com.br");
};

function convertFuso($data){
    //require("conexao_hostgator.php");
    // CONSULTA FUSO DA LOJA

    $fuso = '3';

    $data = date_create($data);
    date_sub($data,date_interval_create_from_date_string("$fuso hours"));
    $data = date_format($data, 'YmdHis');
    return $data;
};

function dateDisplay($in){
    $fuso = 3;
    $diff = 15-$fuso;
    $data = date_create($in);
    date_add($data,date_interval_create_from_date_string("$diff hours"));
    $data = date_format($data,"YmdHis");
    return $data;
};

function abreviaNomeDisplay($name) {
    $name = preg_replace('/\s(d[A-z]{1,2}|a(.){1,2}?|e(.){1,2}?|le{1}|[A-z.]{1,2}\s)/i',' ',$name);
    $name = preg_replace('/\s\s+/i',' ', $name);
    $name = preg_replace('/[^\p{L}\p{N}\s]/', '', $name);

    if (mb_substr($name, -1) === ' ') {
        $name = mb_substr($name, 0, mb_strlen($name)-1);
    }

    $name = ucwords(strtolower($name));
    $primeira = strtok($name, " ");
    $separada = explode(' ', $name);
    $letra = substr($separada[count($separada) - 1], 0, 1);
    $countNames = str_word_count($name);
    if($countNames > 1): 
        $abrev = $primeira.' '.$letra.'.';
    else:
        $abrev = $primeira;
    endif;
    return $abrev;
};

function diffMinutos($data1, $data2) {
    $dateTimeObject1 = date_create($data1);
    $dateTimeObject2 = date_create($data2);

    $difference = date_diff($dateTimeObject1, $dateTimeObject2); 

    $minutes = $difference->days * 24 * 60;
    $minutes += $difference->h * 60;
    $minutes += $difference->i;

    return $minutes;
};