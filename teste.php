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

    $outToken = accessToken();
    $accessToken = $outToken['accessToken'];

    $outPolling = polling($merchantId, $accessToken);
    $polling = $outPolling['polling']; //CHEGA COMO ARRAY

    if($outPolling['code'] == 200):

        $count = count($polling);

        if($count > 0):

            foreach($polling as $in){
                $in = (array) $in;

                $polCode        = $in['code'];
                $polOrderId     = $in['orderId'];
                $polId          = $in['id'];
                $polCreatedAt   = $in['createdAt'];

                //
                //
                //Consulta BD se este evento ja foi recebido e tratado, caso sim pula diretamente para acknowledgment
                //
                //

                if($polCode == 'PLC'):
                    $outDetails = orderDetails($polOrderId, $accessToken);
                    $orderDetails = $outDetails['details'];
                    $orderDetails = (array) $orderDetails;

                    $displayId = (isset($orderDetails['displayId'])) ? $orderDetails['displayId'] : '' ;
                    $orderType = (isset($orderDetails['orderType'])) ? $orderDetails['orderType'] : '' ;
                    $orderTiming = (isset($orderDetails['orderTiming'])) ? $orderDetails['orderTiming'] : '' ;
                    $salesChannel = (isset($orderDetails['salesChannel'])) ? $orderDetails['salesChannel'] : '' ;
                    $dateCreated = (isset($orderDetails['dateCreated'])) ? $orderDetails['dateCreated'] : '' ;
                    $preparationStartDateTime = (isset($orderDetails['preparationStartDateTime'])) ? $orderDetails['preparationStartDateTime'] : '' ;
                    $merchantId = (isset($orderDetails['merchantId'])) ? $orderDetails['merchantId'] : '' ;
                    $merchantName = (isset($orderDetails['merchantName'])) ? $orderDetails['merchantName'] : '' ;
                    $consumerId = (isset($orderDetails['consumerId'])) ? $orderDetails['consumerId'] : '' ;
                    $consumerName = (isset($orderDetails['consumerName'])) ? $orderDetails['consumerName'] : '' ;
                    $consumerDocument = (isset($orderDetails['consumerDocument'])) ? $orderDetails['consumerDocument'] : null ;
                    $consumerNumber = (isset($orderDetails['consumerNumber'])) ? $orderDetails['consumerNumber'] : null ;
                    $consumerLocalizer = (isset($orderDetails['consumerLocalizer'])) ? $orderDetails['consumerLocalizer'] : null ;
                    $consumerLocalizerExpiration = (isset($orderDetails['consumerLocalizerExpiration'])) ? $orderDetails['consumerLocalizerExpiration'] : null ;
                    $isTest = (isset($orderDetails['isTest'])) ? $orderDetails['isTest'] : '' ;
                    $extraInfo = (isset($orderDetails['extraInfo'])) ? $orderDetails['extraInfo'] : null ;
                    $statusCancellation = (isset($orderDetails['statusCancellation'])) ? $orderDetails['statusCancellation'] : null ;
                    $statusTekeout = (isset($orderDetails['statusTekeout'])) ? $orderDetails['statusTekeout'] : null ;
                    $statusDelivery = (isset($orderDetails['statusDelivery'])) ? $orderDetails['statusDelivery'] : null ;
                    $onDemandAvailable = (isset($orderDetails['onDemandAvailable'])) ? $orderDetails['onDemandAvailable'] : null ;
                    $onDemandValue = (isset($orderDetails['onDemandValue'])) ? $orderDetails['onDemandValue'] : null ;
                    $mode = (isset($orderDetails['mode'])) ? $orderDetails['mode'] : '' ;
                    $deliveredBy = (isset($orderDetails['deliveredBy'])) ? $orderDetails['deliveredBy'] : null ;
                    $deliveryDateTime = (isset($orderDetails['deliveryDateTime'])) ? $orderDetails['deliveryDateTime'] : null ;
                    $takeoutDateTime = (isset($orderDetails['takeoutDateTime'])) ? $orderDetails['takeoutDateTime'] : null ;
                    $tableIndoor = (isset($orderDetails['tableIndoor'])) ? $orderDetails['tableIndoor'] : null ;
                    $observations = (isset($orderDetails['observations'])) ? $orderDetails['observations'] : null ;
                    $deliveryDateTimeStart = (isset($orderDetails['deliveryDateTimeStart'])) ? $orderDetails['deliveryDateTimeStart'] : null ;
                    $deliveryDateTimeEnd = (isset($orderDetails['deliveryDateTimeEnd'])) ? $orderDetails['deliveryDateTimeEnd'] : null ;
                    $statusCod = (isset($orderDetails['statusCod'])) ? $orderDetails['statusCod'] : '' ;

                    echo $orderId.'<br>';
                    echo $displayId.'<br>';
                    echo $orderType.'<br>';
                    echo $orderTiming.'<br>';
                    echo $salesChannel.'<br>';
                    echo $dateCreated.'<br>';
                    echo $preparationStartDateTime.'<br>';
                    echo $merchantId.'<br>';
                    echo $merchantName.'<br>';
                    echo $consumerId.'<br>';
                    echo $consumerName.'<br>';
                    echo $consumerDocument.'<br>';
                    echo $consumerNumber.'<br>';
                    echo $consumerLocalizer.'<br>';
                    echo $consumerLocalizerExpiration.'<br>';
                    echo $isTest.'<br>';
                    echo $extraInfo.'<br>';
                    echo $statusCancellation.'<br>';
                    echo $statusTekeout.'<br>';
                    echo $statusDelivery.'<br>';
                    echo $onDemandAvailable.'<br>';
                    echo $onDemandValue.'<br>';
                    echo $mode.'<br>';
                    echo $deliveredBy.'<br>';
                    echo $deliveryDateTime.'<br>';
                    echo $takeoutDateTime.'<br>';
                    echo $tableIndoor.'<br>';
                    echo $observations.'<br>';
                    echo $deliveryDateTimeStart.'<br>';
                    echo $deliveryDateTimeEnd.'<br>';
                    echo $statusCod.'<hr>';



                endif;

                //
                //
                //Faz tudo que precisa ser feito no banco
                //
                //



                continue;
                
                //MANDA PARA acknowledgment
                $ack = json_encode($in);
                $send = "[ $ack ]";

                $outAck = acknowledgment($send, $accessToken);
                      
                if($outAck['code'] == 202):
                    echo 'Accepted. <br>';
                else:
                    echo $outAck['mensagem'].' / CodeHttp: '.$outAck['code'].'<br>';
                endif;
            };

        endif;
    elseif($outPolling['code'] == 204):
        echo 'Polling vazio!';
    else:
        echo $outPolling['mensagem'].' / CodeHttp: '.$outPolling['code'];
    endif;
endif;






/*
$test = '[
    {
        "id": "2b729bb3-9cda-4cda-824e-9f23617b9579",
        "code": "PLC",
        "fullCode": "PLACED",
        "orderId": "b3b21627-e1eb-4900-824d-b050ce301fc8",
        "createdAt": "2022-04-13T15:09:43.713Z"
    }
]';

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
    CURLOPT_POSTFIELDS => $test,
    CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzUxMiJ9.eyJzdWIiOiIyYzk5ZWQ0OS00NzhiLTQ5NTktYjM5Mi00ODgyOGVkYTk5NTQiLCJhdWQiOlsic2hpcHBpbmciLCJjYXRhbG9nIiwiZmluYW5jaWFsIiwicmV2aWV3IiwibWVyY2hhbnQiLCJvcmRlciIsIm9hdXRoLXNlcnZlciJdLCJhcHBfbmFtZSI6ImFkbWluc3dlZXRjb25mZXR0eXRlc3RlYyIsIm93bmVyX25hbWUiOiJhZG1pbnN3ZWV0Y29uZmV0dHkiLCJzY29wZSI6WyJtZXJjaGFudCIsInNoaXBwaW5nIiwiY2F0YWxvZyIsInJldmlldyIsIm9yZGVyIiwiY29uY2lsaWF0b3IiXSwiaXNzIjoiaUZvb2QiLCJtZXJjaGFudF9zY29wZSI6WyI4NmMzNjRlNS1hYTMwLTQ5OWUtYWViMS1hMmQzZGRmYzJiM2U6Y29uY2lsaWF0b3IiLCI4NmMzNjRlNS1hYTMwLTQ5OWUtYWViMS1hMmQzZGRmYzJiM2U6Y2F0YWxvZyIsIjg2YzM2NGU1LWFhMzAtNDk5ZS1hZWIxLWEyZDNkZGZjMmIzZTpyZXZpZXciLCI4NmMzNjRlNS1hYTMwLTQ5OWUtYWViMS1hMmQzZGRmYzJiM2U6c2hpcHBpbmciLCI4NmMzNjRlNS1hYTMwLTQ5OWUtYWViMS1hMmQzZGRmYzJiM2U6bWVyY2hhbnQiLCI4NmMzNjRlNS1hYTMwLTQ5OWUtYWViMS1hMmQzZGRmYzJiM2U6b3JkZXIiXSwiZXhwIjoxNjQ5ODczMzExLCJpYXQiOjE2NDk4NjI1MTEsImp0aSI6IjJjOTllZDQ5LTQ3OGItNDk1OS1iMzkyLTQ4ODI4ZWRhOTk1NCIsIm1lcmNoYW50X3Njb3BlZCI6dHJ1ZSwiY2xpZW50X2lkIjoiMmM5OWVkNDktNDc4Yi00OTU5LWIzOTItNDg4MjhlZGE5OTU0In0.tNgSrc8lQ9s8-hG3XSESSo1h59o5sqYcqqWsMJLR-atspI-revdRJyxtvb78TAluowY9nnzSBDyZp1h5dPgkASYPLvc4J9ZD43npFSsOg1BQ8kzPsE79DBCEK8okqbWA8KyBpL79v--dM0pp43mnGq0jfZEu78Vi87k-eZBUias',
        'Content-Type: application/json'
      ),
    ));
    
    $response = curl_exec($curl);
    
    curl_close($curl);
    echo $response;
  








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


