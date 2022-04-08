<?php

$_POST['status_ifood'] = true;

if (isset($_POST['status_ifood']) && $_POST['status_ifood'] == true) :
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

    $outState = merchantStatus($accessToken);

    if(!empty($outState['erro'])):
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

$retorno = json_decode($json, true);

$itens = $retorno["items"];

foreach($itens as $v){
    echo "id: ".$v['id'].'<br>';
}




