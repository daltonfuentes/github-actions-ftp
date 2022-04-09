<?php
require_once("./conexao/functions.php");
//$_POST['status_ifood'] = true;

if (isset($_POST['status_ifood']) && $_POST['status_ifood'] == true) :

    $retorno = array();
 
    $outToken = accessToken();

    if(empty($outToken['accessToken']) || $outToken['erro'] != 0):
        // envia log de erro.
        $retorno['erro'] = $outToken['erro'];
        $retorno['mensagem']  = $outToken['mensagem'];
        echo json_encode($retorno);
        exit();
    else:
        $accessToken = $outToken['accessToken'];
    endif;

    $outState = merchantStatus($accessToken);

    if(empty($outToken['state']) || $outToken['erro'] != 0):
        // envia log de erro.
        $retorno['erro'] = $outState['erro'];
        $retorno['mensagem']  = $outState['mensagem'];
        echo json_encode($retorno);
        exit();
    else:
        $state = $outState['state'];
    endif;

    if($state == 'CLOSED' || $state == 'ERROR'):
        $retorno['erro'] = $outState['erro'];
        $retorno['mensagem']  = $outState['title'].' - '.$outState['subtitle'];
        echo json_encode($retorno);
        exit();
    elseif($state == 'OK' || $state == 'WARNING'):
        $retorno['erro'] = $outState['erro'];
        $retorno['mensagem']  = $outState['title'];
        echo json_encode($retorno);
        exit();
    endif;
endif;


$_POST['polling'] = true;

if (isset($_POST['polling']) && $_POST['polling'] == true) :
 
    $merchantId = '86c364e5-aa30-499e-aeb1-a2d3ddfc2b3e';

    $retorno = polling($merchantId); //decode
    $polling = $retorno['polling'];

    if($retorno['erro'] == 0):

        $count = count($polling);

        if($count > 0):

            foreach($polling as $in){
                $send = array(
                    array("id" => $in['id'])
                );

                $curl = curl_init();

                curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://merchant-api.ifood.com.br/order/v1.0/events/acknowledgment',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $send,
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzUxMiJ9.eyJzdWIiOiIyYzk5ZWQ0OS00NzhiLTQ5NTktYjM5Mi00ODgyOGVkYTk5NTQiLCJhdWQiOlsic2hpcHBpbmciLCJjYXRhbG9nIiwiZmluYW5jaWFsIiwicmV2aWV3IiwibWVyY2hhbnQiLCJvcmRlciIsIm9hdXRoLXNlcnZlciJdLCJhcHBfbmFtZSI6ImFkbWluc3dlZXRjb25mZXR0eXRlc3RlYyIsIm93bmVyX25hbWUiOiJhZG1pbnN3ZWV0Y29uZmV0dHkiLCJzY29wZSI6WyJzaGlwcGluZyIsImNhdGFsb2ciLCJyZXZpZXciLCJtZXJjaGFudCIsIm9yZGVyIiwiY29uY2lsaWF0b3IiXSwiaXNzIjoiaUZvb2QiLCJtZXJjaGFudF9zY29wZSI6WyI4NmMzNjRlNS1hYTMwLTQ5OWUtYWViMS1hMmQzZGRmYzJiM2U6Y29uY2lsaWF0b3IiLCI4NmMzNjRlNS1hYTMwLTQ5OWUtYWViMS1hMmQzZGRmYzJiM2U6Y2F0YWxvZyIsIjg2YzM2NGU1LWFhMzAtNDk5ZS1hZWIxLWEyZDNkZGZjMmIzZTpyZXZpZXciLCI4NmMzNjRlNS1hYTMwLTQ5OWUtYWViMS1hMmQzZGRmYzJiM2U6c2hpcHBpbmciLCI4NmMzNjRlNS1hYTMwLTQ5OWUtYWViMS1hMmQzZGRmYzJiM2U6bWVyY2hhbnQiLCI4NmMzNjRlNS1hYTMwLTQ5OWUtYWViMS1hMmQzZGRmYzJiM2U6b3JkZXIiXSwiZXhwIjoxNjQ5NTUzOTcyLCJpYXQiOjE2NDk1NDMxNzIsImp0aSI6IjJjOTllZDQ5LTQ3OGItNDk1OS1iMzkyLTQ4ODI4ZWRhOTk1NCIsIm1lcmNoYW50X3Njb3BlZCI6dHJ1ZSwiY2xpZW50X2lkIjoiMmM5OWVkNDktNDc4Yi00OTU5LWIzOTItNDg4MjhlZGE5OTU0In0.rfZupPEH0jbEz28GLP41C26WFDyz0CpzwoJi94_IhsiEzJde6tH4KOaBpu1WFi5fZw35PeI3Xr5inCejTKirkqtehBAdY77LJRpP1sKKzYpyes8Bof9IJahxODe6f4oW2ZRLlZB4f_ConKiyG_7RBHwM3-VvKcv_lUXLO-1JMIo',
                    'Content-Type: application/json'
                ),
                ));

                $response = curl_exec($curl);
                $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                curl_close($curl);

                echo $httpcode;


                
                echo '<hr>';
            };
        endif;
    endif;
endif;



/*


$json = '{
    "id": "688eff25-1353-423a-aeb2-356b91472c69",
    "delivery": {
        "mode": "DEFAULT",
        "deliveredBy": "MERCHANT",
        "deliveryDateTime": "2022-04-07T00:30:07.378Z",
        "deliveryAddress": {
            "streetName": "PEDIDO DE TESTE - NÃO ENTREGAR - Ramal Bujari",
            "streetNumber": "122",
            "formattedAddress": "PEDIDO DE TESTE - NÃO ENTREGAR - Ramal Bujari, 122",
            "neighborhood": "Bujari",
            "postalCode": "00000000",
            "city": "Bujari",
            "state": "AC",
            "country": "BR",
            "coordinates": {
                "latitude": -9.822384,
                "longitude": -67.948589
            }
        }
    },
    "orderType": "DELIVERY",
    "orderTiming": "IMMEDIATE",
    "displayId": "8066",
    "createdAt": "2022-04-06T23:50:07.378Z",
    "preparationStartDateTime": "2022-04-06T23:50:07.378Z",
    "isTest": true,
    "merchant": {
        "id": "86c364e5-aa30-499e-aeb1-a2d3ddfc2b3e",
        "name": "Teste - Admin Sweet Confetty"
    },
    "customer": {
        "id": "f0b78ff5-d0f8-4726-99a3-b7b6a4287ee4",
        "name": "PEDIDO DE TESTE - Dalton Gonzalo Cornelio Fuentes",
        "documentNumber": "10134957997",
        "phone": {
            "number": "0800 242 8347",
            "localizer": "40815722",
            "localizerExpiration": "2022-04-07T02:50:07.378Z"
        },
        "ordersCountOnMerchant": 0
    },
    "items": [
        {
            "index": 1,
            "id": "c9c55bb4-494e-443c-bbe8-de58f08d92b8",
            "name": "PEDIDO DE TESTE - Duplo Brigadeiro",
            "externalCode": "5619",
            "unit": "GRAMS",
            "quantity": 1,
            "unitPrice": 0.00,
            "optionsPrice": 15.00,
            "totalPrice": 15.00,
            "options": [
                {
                    "index": 2,
                    "id": "6be65d3e-67ac-43d9-bf6b-517c29ca1304",
                    "name": "Copo G",
                    "externalCode": "2589",
                    "unit": "UN",
                    "quantity": 1,
                    "unitPrice": 15.00,
                    "addition": 0.00,
                    "price": 15.00
                }
            ],
            "imageUrl": "https://static-images.ifood.com.br/image/upload/t_high/pratos/86c364e5-aa30-499e-aeb1-a2d3ddfc2b3e/202109231002_CNYU_.jpeg",
            "price": 0.00
        },
        {
            "index": 3,
            "id": "1e71b649-8b72-43a4-89d7-3fd8b3e8c6f4",
            "name": "PEDIDO DE TESTE - Nome do Refrigerante 2 L",
            "unit": "GRAMS",
            "quantity": 1,
            "unitPrice": 10.00,
            "optionsPrice": 0,
            "totalPrice": 10.00,
            "observations": "Tira cebola",
            "price": 10.00
        }
    ],
    "salesChannel": "IFOOD",
    "total": {
        "subTotal": 25.00,
        "deliveryFee": 8.90,
        "benefits": 0,
        "orderAmount": 33.90,
        "additionalFees": 0.00
    },
    "payments": {
        "prepaid": 0,
        "pending": 33.90,
        "methods": [
            {
                "value": 33.90,
                "currency": "BRL",
                "method": "CASH",
                "type": "OFFLINE",
                "cash": {
                    "changeFor": 50.00
                },
                "prepaid": false
            }
        ]
    }
}';

$polling = '[
    {
        "id": "dab2b9c6-33f5-412c-ab02-052ea01eb55e",
        "code": "PLC",
        "fullCode": "PLACED",
        "orderId": "226865a8-2e24-48c9-8fa6-9316cd5947ec",
        "createdAt": "2022-04-08T22:46:52.234Z"
    },
    {
        "id": "baf15f0f-1683-410e-88cc-1b3f21fa1b57",
        "code": "CFM",
        "fullCode": "CONFIRMED",
        "orderId": "bfbbf867-31ba-475b-a89d-ca51e2e96fe6",
        "createdAt": "2022-04-08T22:48:18.478Z",
        "metadata": {
            "ORIGIN": "ORDER_API",
            "ownerName": "ifood",
            "CLIENT_ID": "ifood:iconnect_v3_homologation",
            "appName": "iconnect_v3_homologation"
        }
    },
    {
        "id": "09f6dfb1-1ca5-4408-8e5f-39a001c89dcb",
        "code": "CFM",
        "fullCode": "CONFIRMED",
        "orderId": "226865a8-2e24-48c9-8fa6-9316cd5947ec",
        "createdAt": "2022-04-08T22:49:40.654Z",
        "metadata": {
            "ORIGIN": "ORDER_API",
            "ownerName": "adminsweetconfetty",
            "CLIENT_ID": "adminsweetconfetty:adminsweetconfettytestec",
            "appName": "adminsweetconfettytestec"
        }
    }
]';
$retorno = array();

$retorno = json_decode($polling, true);

$merchantId = '86c364e5-aa30-499e-aeb1-a2d3ddfc2b3e';

foreach($retorno as $in){
    $id         = $in['id'];
    $orderId    = $in['orderId'];
    $orderType  = $in['code'];
    $createdAt  = $in['createdAt'];

    echo $id.'<br>';

    continue;

    $sql = "SELECT * FROM ifood_events WHERE orderId='$orderId' && id='$id' && createdAt='$createdAt'";
    $resultado = $conexao->prepare($sql);	
    $resultado->execute();
    $contar = $resultado->rowCount();

    if($contar != 0):
        // evento já foi processado
        // envia para /acknowledgment
        $send = '['.json_encode($in).']';
        $outAcknowledgment = acknowledgment($send);


        continue;
    endif;
    

    $sql = "SELECT orderType FROM ifood_orders WHERE orderId='$orderId' && merchantId='$merchantId'";
    $resultado = $conexao->prepare($sql);	
    $resultado->execute();
    $contar = $resultado->rowCount();

    if($contar == 0):
        // ORDER NAO FOI CADASTRADA AINDA
        if($orderType == 'PLC'):
            // APENAS ATUALIZA O STATUS DO PEDIDO


        else:
            // COD NÃO ESPERADO

        endif;
    else:

    endif;


}




date_default_timezone_set('America/Sao_Paulo');

$data = '2020-01-01T00:00:00.000Z';

$date = new DateTime('2022-04-09T03:07:12.310Z');
date_add($date, date_interval_create_from_date_string('10 minute'));
$expire = date_format($date, 'Y-m-d H:i:s.v');

echo $expire;


foreach($itens as $v){
    echo "id: ".$v['id'].'<br>';
}

*/


