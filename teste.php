<?php
require("./conexao/functions.php");
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
    require("./conexao/conexao_hostgator.php");

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
                    $orderDetails = (array) $outDetails['details'];
                    $orderDetailsMerchant = (array) $orderDetails['merchant'];
                    $orderDetailsCustomer = (array) $orderDetails['customer'];
                    $orderDetailsCustomerPhone = (array) $orderDetailsCustomer['phone'];

                    $displayId = (isset($orderDetails['displayId'])) ? $orderDetails['displayId'] : '' ;
                    $orderType = (isset($orderDetails['orderType'])) ? $orderDetails['orderType'] : '' ;
                    $orderTiming = (isset($orderDetails['orderTiming'])) ? $orderDetails['orderTiming'] : '' ;
                    $salesChannel = (isset($orderDetails['salesChannel'])) ? $orderDetails['salesChannel'] : '' ;
                    $dateCreated = (isset($orderDetails['createdAt'])) ? $orderDetails['createdAt'] : '' ;
                    $preparationStartDateTime = (isset($orderDetails['preparationStartDateTime'])) ? $orderDetails['preparationStartDateTime'] : '' ;

                        $merchantId = (isset($orderDetailsMerchant['id'])) ? $orderDetailsMerchant['id'] : '' ;
                        $merchantName = (isset($orderDetailsMerchant['name'])) ? $orderDetailsMerchant['name'] : '' ;

                        $customerId = (isset($orderDetailsCustomer['id'])) ? $orderDetailsCustomer['id'] : '' ;
                        $customerName = (isset($orderDetailsCustomer['name'])) ? $orderDetailsCustomer['name'] : '' ;
                        $customerDocument = (isset($orderDetailsCustomer['documentNumber'])) ? $orderDetailsCustomer['documentNumber'] : null ;
                            $customerNumber = (isset($orderDetailsCustomerPhone['number'])) ? $orderDetailsCustomerPhone['number'] : null ;
                            $customerLocalizer = (isset($orderDetailsCustomerPhone['localizer'])) ? $orderDetailsCustomerPhone['localizer'] : null ;
                            $customerLocalizerExpiration = (isset($orderDetailsCustomerPhone['localizerExpiration'])) ? $orderDetailsCustomerPhone['localizerExpiration'] : null ;

                    $isTest = (isset($orderDetails['isTest'])) ? $orderDetails['isTest'] : '' ;

                    $extraInfo = (isset($orderDetails['extraInfo'])) ? $orderDetails['extraInfo'] : null ;

                    $statusCancellation = null;
                    $statusTekeout = null;
                    $statusDelivery = null;
                    $onDemandAvailable = null;
                    $onDemandValue = null;                 

                    $statusCod = $polCode ;

                    if($orderTiming == 'IMMEDIATE'):
                        $schedule = '';
                    elseif($orderTiming == 'SCHEDULED '):
                        $orderDetailsSchedule = (array) $orderDetails['schedule'];

                        $deliveryDateTimeStart = (isset($orderDetailsSchedule['deliveryDateTimeStart'])) ? $orderDetailsSchedule['deliveryDateTimeStart'] : '' ;
                        $deliveryDateTimeEnd = (isset($orderDetailsSchedule['deliveryDateTimeEnd'])) ? $orderDetailsSchedule['deliveryDateTimeEnd'] : '' ;

                        $schedule = $deliveryDateTimeStart.' / '.$deliveryDateTimeEnd;
                    endif;

                    if($orderType == 'DELIVERY'):
                        $orderDetailsDelivery = (array) $orderDetails['delivery'];

                        $mode = (isset($orderDetailsDelivery['mode'])) ? $orderDetailsDelivery['mode'] : '' ;
                        $deliveredBy = (isset($orderDetailsDelivery['deliveredBy'])) ? $orderDetailsDelivery['deliveredBy'] : '' ;
                        $deliveryDateTime = (isset($orderDetailsDelivery['deliveryDateTime'])) ? $orderDetailsDelivery['deliveryDateTime'] : '' ;
                        $observations = (isset($orderDetailsDelivery['observations'])) ? $orderDetailsDelivery['observations'] : null ;

                        $deliveryAddress = (array) $orderDetailsDelivery['deliveryAddress'];
                        $deliveryCoordinates = (array) $deliveryAddress['coordinates'];

                        $streetName = (isset($deliveryAddress['streetName'])) ? $deliveryAddress['streetName'] : '' ;
                        $streetNumber = (isset($deliveryAddress['streetNumber'])) ? $deliveryAddress['streetNumber'] : '' ;
                        $formattedAddress = (isset($deliveryAddress['formattedAddress'])) ? $deliveryAddress['formattedAddress'] : '' ;
                        $neighborhood = (isset($deliveryAddress['neighborhood'])) ? $deliveryAddress['neighborhood'] : '' ;
                        $complement = (isset($deliveryAddress['complement'])) ? $deliveryAddress['complement'] : null ;
                        $reference = (isset($deliveryAddress['reference'])) ? $deliveryAddress['reference'] : null ;
                        $postalCode = (isset($deliveryAddress['postalCode'])) ? $deliveryAddress['postalCode'] : '' ;
                        $city = (isset($deliveryAddress['city'])) ? $deliveryAddress['city'] : '' ;
                        $state = (isset($deliveryAddress['state'])) ? $deliveryAddress['state'] : '' ;
                        $country = (isset($deliveryAddress['country'])) ? $deliveryAddress['country'] : '' ;

                        $latitude = (isset($deliveryCoordinates['latitude'])) ? $deliveryCoordinates['latitude'] : '' ;
                        $longitude = (isset($deliveryCoordinates['longitude'])) ? $deliveryCoordinates['longitude'] : '' ;

                        //VALIDA CAMPOS EMPTY

                        $sql = 'INSERT INTO ifood_delivery_anddress (orderId, streetName, streetNumber, formattedAddress, neighborhood, complement, reference, postalCode, city, stateDelivery, country, latitude, longitude) VALUES (:orderId, :streetName, :streetNumber, :formattedAddress, :neighborhood, :complement, :reference, :postalCode, :city, :stateDelivery, :country, :latitude, :longitude)';
                        $stmt = $conexao->prepare($sql);
                        $stmt->bindParam(':orderId', $polOrderId);
                        $stmt->bindParam(':streetName', $streetName);
                        $stmt->bindParam(':streetNumber', $streetNumber);
                        $stmt->bindParam(':formattedAddress', $formattedAddress);
                        $stmt->bindParam(':neighborhood', $neighborhood);
                        $stmt->bindParam(':complement', $complement);
                        $stmt->bindParam(':reference', $reference);
                        $stmt->bindParam(':postalCode', $postalCode);
                        $stmt->bindParam(':city', $city);
                        $stmt->bindParam(':stateDelivery', $state);
                        $stmt->bindParam(':country', $country);
                        $stmt->bindParam(':latitude', $latitude);
                        $stmt->bindParam(':longitude', $longitude);
                        $resposta = $stmt->execute();
    
                        if(!$resposta):
                            $erro  = '1';
                            $mensagem = 'Erro interno BD.';
                            echo $erro.' - '.$mensagem.'<br>';
                        else:
                            $erro  = '0';
                            $mensagem = 'Endereço cadastrado.';
                            echo $erro.' - '.$mensagem.'<br>';
                        endif;
                    elseif($orderType == 'INDOOR '):
                        $orderDetailsIndoor = (array) $orderDetails['indoor'];

                        $mode = (isset($orderDetailsorderDetailsIndoorDelivery['mode'])) ? $orderDetailsIndoor['mode'] : '' ;
                        $tableIndoor = (isset($orderDetailsIndoor['table'])) ? $orderDetailsIndoor['table'] : '' ;
                        $deliveryDateTime = (isset($orderDetailsIndoor['deliveryDateTime'])) ? $orderDetailsIndoor['deliveryDateTime'] : '' ;
                        $observations = (isset($orderDetailsIndoor['observations'])) ? $orderDetailsIndoor['observations'] : null ;
                    elseif($orderType == 'TAKEOUT'):
                        $orderDetailsTakeout = (array) $orderDetails['takeout'];

                        $mode = (isset($orderDetailsTakeout['mode'])) ? $orderDetailsTakeout['mode'] : '' ;
                        $takeoutDateTime = (isset($orderDetailsTakeout['takeoutDateTime'])) ? $orderDetailsTakeout['takeoutDateTime'] : '' ;
                        $observations = (isset($orderDetailsTakeout['observations'])) ? $orderDetailsTakeout['observations'] : null ;
                    endif;

                    $deliveredBy = (isset($deliveredBy)) ? $deliveredBy : null;
                    $deliveryDateTime = (isset($deliveryDateTime)) ? $deliveryDateTime : null;
                    $takeoutDateTime = (isset($takeoutDateTime)) ? $takeoutDateTime : null;
                    $tableIndoor = (isset($tableIndoor)) ? $tableIndoor : null;
                    $deliveryDateTimeStart = (isset($deliveryDateTimeStart)) ? $deliveryDateTimeStart : null;
                    $deliveryDateTimeEnd = (isset($deliveryDateTimeEnd)) ? $deliveryDateTimeEnd : null;


                    if(array_key_exists("benefits", $orderDetails)):
                        $benefits = (array) $orderDetails['benefits'];
                    
                        $sponValue = array();
                        $sponValue[0] = array( "name" => "ifood", "value" => 0 );
                        $sponValue[1] = array( "name" => "loja", "value" => 0 );
                    
                        foreach($benefits as $inB){
                            $inB = (array) $inB;
                            $sponsor = $inB['sponsorshipValues'];
                    
                            foreach($sponsor as $spon){
                                $spon = (array) $spon;
                            
                                $name = $spon['name'];
                                $value = $spon['value'];
                                
                                if($name == 'IFOOD'):
                                    $sponValue[0]['value'] = $sponValue[0]['value'] + $value; 
                                elseif($name == 'MERCHANT'):
                                    $sponValue[1]['value'] = $sponValue[1]['value'] + $value; 
                                endif;
                            };
                        };

                        var_dump($sponValue).'<br>';
                    endif;


                    //continue;

                    //VALIDA CAMPOS EMPTY

                    $sql = 'INSERT INTO ifood_orders (orderId, displayId, orderType, orderTiming, salesChannel, dateCreated, preparationStartDateTime, merchantId, merchantName, customerId, customerName, customerDocument, customerNumber, customerLocalizer, customerLocalizerExpiration, isTest, extraInfo, statusCancellation, statusTekeout, statusDelivery, onDemandAvailable, onDemandValue, mode, deliveredBy, deliveryDateTime, takeoutDateTime, tableIndoor, observations, deliveryDateTimeStart, deliveryDateTimeEnd, statusCod) VALUES (:orderId, :displayId, :orderType, :orderTiming, :salesChannel, :dateCreated, :preparationStartDateTime, :merchantId, :merchantName, :customerId, :customerName, :customerDocument, :customerNumber, :customerLocalizer, :customerLocalizerExpiration, :isTest, :extraInfo, :statusCancellation, :statusTekeout, :statusDelivery, :onDemandAvailable, :onDemandValue, :mode, :deliveredBy, :deliveryDateTime, :takeoutDateTime, :tableIndoor, :observations, :deliveryDateTimeStart, :deliveryDateTimeEnd, :statusCod)';
                    $stmt = $conexao->prepare($sql);
                    $stmt->bindParam(':orderId', $polOrderId);
                    $stmt->bindParam(':displayId', $displayId);
                    $stmt->bindParam(':orderType', $orderType);
                    $stmt->bindParam(':orderTiming', $orderTiming);
                    $stmt->bindParam(':salesChannel', $salesChannel);
                    $stmt->bindParam(':dateCreated', $dateCreated);
                    $stmt->bindParam(':preparationStartDateTime', $preparationStartDateTime);
                    $stmt->bindParam(':merchantId', $merchantId);
                    $stmt->bindParam(':merchantName', $merchantName);
                    $stmt->bindParam(':customerId', $customerId);
                    $stmt->bindParam(':customerName', $customerName);
                    $stmt->bindParam(':customerDocument', $customerDocument);
                    $stmt->bindParam(':customerNumber', $customerNumber);
                    $stmt->bindParam(':customerLocalizer', $customerLocalizer);
                    $stmt->bindParam(':customerLocalizerExpiration', $customerLocalizerExpiration);
                    $stmt->bindParam(':isTest', $isTest);
                    $stmt->bindParam(':extraInfo', $extraInfo);
                    $stmt->bindParam(':statusCancellation', $statusCancellation);
                    $stmt->bindParam(':statusTekeout', $statusTekeout);
                    $stmt->bindParam(':statusDelivery', $statusDelivery);
                    $stmt->bindParam(':onDemandAvailable', $onDemandAvailable);
                    $stmt->bindParam(':onDemandValue', $onDemandValue);
                    $stmt->bindParam(':mode', $mode);
                    $stmt->bindParam(':deliveredBy', $deliveredBy);
                    $stmt->bindParam(':deliveryDateTime', $deliveryDateTime);
                    $stmt->bindParam(':takeoutDateTime', $takeoutDateTime);
                    $stmt->bindParam(':tableIndoor', $tableIndoor);
                    $stmt->bindParam(':observations', $observations);
                    $stmt->bindParam(':deliveryDateTimeStart', $deliveryDateTimeStart);
                    $stmt->bindParam(':deliveryDateTimeEnd', $deliveryDateTimeEnd);
                    $stmt->bindParam(':statusCod', $statusCod);
                    $resposta = $stmt->execute();

                    if(!$resposta):
                        $erro  = '1';
                        $mensagem = 'Erro interno BD.';
                        echo $erro.' - '.$mensagem.'<br>';
                    else:
                        $erro  = '0';
                        $mensagem = 'Pedido cadastrado.';
                        echo $erro.' - '.$mensagem.'<br>';
                    endif;
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

$details = '{
    "id": "63895716-37c3-4372-afd0-3240bfef708d",
    "orderTiming": "IMMEDIATE",
    "orderType": "DELIVERY",
    "salesChannel": "IFOOD",
    "delivery": {
      "mode": "ECONOMIC",
      "deliveredBy": "IFOOD",
      "deliveryDateTime": "2021-02-09T18:10:32Z",
      "deliveryAddress": {
        "streetName": "Example",
        "streetNumber": "1234",
        "formattedAddress": "Example St., 1234, Apt. 1234",
        "neighborhood": "Examplehood",
        "complement": "Apt. 1234",
        "reference": "perto da praça",
        "postalCode": "12345678",
        "city": "Example City",
        "state": "Example State",
        "country": "BR",
        "coordinates": {
          "latitude": -2.1059418202311173e141,
          "longitude": -49545.71
        }
      }
    },
    "displayId": "XPTO",
    "createdAt": "2021-02-16T18:10:27Z",
    "preparationStartDateTime": "2021-02-09T20:15:13Z",
    "merchant": {
      "id": "c54bb20a-bce0-4e38-bd4a-fe5f0a7b6b5a",
      "name": "Example Merchant"
    },
    "customer": {
      "id": "22587f70-60b4-423c-8cd2-27d288f47f99",
      "name": "Example Customer",
      "documentNumber": "123456789",
      "phone": {
        "number": "123456789",
        "localizer": "12345678",
        "localizerExpiration": "2021-02-09T18:11:07Z"
      },
      "ordersCountOnMerchant": 1234
    },
    "items": [
      {
        "index": 0,
        "id": "f1e48636-4bf0-4656-bce8-0e2214fcd3d4",
        "name": "Example Item",
        "imageUrl": "https://static-images.ifood.com.br/image/upload/t_high/pratos/4c714577-fe5d-4d31-9531-f9ebb7f89249/202104071957_0mfD_.jpeg",
        "externalCode": "ex01",
        "ean": "12345678910",
        "unit": "G",
        "quantity": 12,
        "unitPrice": 0.12,
        "addition": 0,
        "price": 1.44,
        "optionsPrice": 1.69,
        "totalPrice": 3.13,
        "observations": "This is an example item.",
        "options": [
          {
            "index": 0,
            "id": "acea6ac1-f595-4a6b-af00-cc2f1fa0886a",
            "name": "Example Option",
            "externalCode": "ex02",
            "ean": "12345678911",
            "unit": "UN",
            "quantity": 13,
            "unitPrice": 0.13,
            "addition": 0,
            "price": 1.69
          }
        ]
      }
    ],
    "benefits": [
      {
        "value": 1.0,
        "sponsorshipValues": [
          {
            "name": "IFOOD",
            "value": 0.5
          },
          {
            "name": "MERCHANT",
            "value": 0.5
          }
        ],
        "target": "CART"
      },
      {
        "value": 0.5,
        "sponsorshipValues": [
          {
            "name": "IFOOD",
            "value": 0.5
          },
          {
            "name": "MERCHANT",
            "value": 0
          }
        ],
        "target": "ITEM",
        "targetId": "1"
      },
      {
        "value": 0.49,
        "sponsorshipValues": [
          {
            "name": "IFOOD",
            "value": 0
          },
          {
            "name": "MERCHANT",
            "value": 0.49
          }
        ],
        "target": "DELIVERY_FEE"
      }
    ],
    "additionalFees": [
      {
        "type": "SMALL_ORDER_FEE",
        "value": 1.0
      }
    ],
    "total": {
      "subTotal": 3.13,
      "deliveryFee": 5.99,
      "additionalFees": 1,
      "benefits": 1.99,
      "orderAmount": 8.13
    },
    "payments": {
      "prepaid": 2.13,
      "pending": 5,
      "methods": [
        {
          "value": 5,
          "currency": "BRL",
          "method": "CASH",
          "type": "OFFLINE",
          "prepaid": false
        },
        {
          "value": 2.13,
          "currency": "BRL",
          "method": "MEAL_VOUCHER",
          "type": "ONLINE",
          "prepaid": true
        }
      ]
    },
    "picking": {
      "picker": "DRIVER_SHOPPER",
      "replacementOptions": "STORE_REMOVE_ITEMS"
    },
    "test": false,
    "additionalInfo": {
      "metadata": {
        "codigoInternoPdv": "18bf73f64715",
        "nomeVendedor": "João"
      }
    }
  }';



$details = json_decode($details);
$orderDetails = (array) $details;

if(array_key_exists("benefits", $orderDetails)):
    $benefits = $orderDetails['benefits'];
    $benefits = (array) $benefits;

    $sponValue = array();
    $sponValue[0] = array( "name" => "ifood", "value" => 0 );
    $sponValue[1] = array( "name" => "loja", "value" => 0 );

    foreach($benefits as $inB){
        $inB = (array) $inB;
        $sponsor = $inB['sponsorshipValues'];

        foreach($sponsor as $spon){
            $spon = (array) $spon;
        
            $name = $spon['name'];
            $value = $spon['value'];
            
            if($name == 'IFOOD'):
                $sponValue[0]['value'] = $sponValue[0]['value'] + $value; 
            elseif($name == 'MERCHANT'):
                $sponValue[1]['value'] = $sponValue[1]['value'] + $value; 
            endif;
        };
    };

    foreach($sponValue as $sponIn){
        if($sponIn['value'] =! 0):

        endif;
    };
endif;































$details = '{
    "id": "a78c1d62-3cd6-4dac-a1c2-f413b363d671",
    "delivery": {
        "mode": "DEFAULT",
        "deliveredBy": "MERCHANT",
        "deliveryDateTime": "2022-04-13T20:53:01.364Z",
        "observations": "Portão da casa/prédio",
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
    "displayId": "9745",
    "createdAt": "2022-04-13T20:13:01.364Z",
    "preparationStartDateTime": "2022-04-13T20:13:01.364Z",
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
            "number": "0800 007 0110",
            "localizer": "56132078",
            "localizerExpiration": "2022-04-13T23:13:01.364Z"
        },
        "ordersCountOnMerchant": 0
    },
    "items": [
        {
            "index": 1,
            "id": "1e71b649-8b72-43a4-89d7-3fd8b3e8c6f4",
            "name": "PEDIDO DE TESTE - Nome do Refrigerante 2 L",
            "unit": "GRAMS",
            "quantity": 1,
            "unitPrice": 10.00,
            "optionsPrice": 0,
            "totalPrice": 10.00,
            "price": 10.00
        }
    ],
    "salesChannel": "IFOOD",
    "total": {
        "subTotal": 10.00,
        "deliveryFee": 8.90,
        "benefits": 0,
        "orderAmount": 19.89,
        "additionalFees": 0.99
    },
    "payments": {
        "prepaid": 19.89,
        "pending": 0,
        "methods": [
            {
                "value": 19.89,
                "currency": "BRL",
                "method": "CREDIT",
                "type": "ONLINE",
                "card": {
                    "brand": "VISA"
                },
                "prepaid": true
            }
        ]
    },
    "additionalFees": [
        {
            "type": "SMALL_ORDER_FEE",
            "value": 0.99
        }
    ]
}';

$details = json_decode($details);

$orderDetails = (array) $details;
$orderDetailsMerchant = (array) $orderDetails['merchant'];
$orderDetailsCustomer = (array) $orderDetails['customer'];
$orderDetailsCustomerPhone = (array) $orderDetailsCustomer['phone'];

echo $orderDetailsCustomerPhone['localizer'];



















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


