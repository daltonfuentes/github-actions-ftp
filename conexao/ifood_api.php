<?php
ob_start();
session_start();

require("functions.php");
//$_POST['status_ifood'] = true;

if (isset($_POST['status_ifood']) && $_POST['status_ifood'] == true) :

    $retorno = array();

    $outToken = accessToken();
    $accessToken = $outToken['accessToken'];

    $outState = merchantStatus($accessToken);

    if($outState['code'] == 200):
        $state = $outState['state'];
    else:
        errorLog('error-merchantStatus-'.$outState['code'].'-'.$outState['mensagem']);
        $retorno['code']  = $outState['code'];
        echo json_encode($retorno);
        exit();
    endif;

    $test = json_encode($outState);

    $validations = (array) $test['validations'];
    $validation1 = (array) $validations[1];

    if($state == 'CLOSED' || $state == 'ERROR'):
        // LOJA FECHADA
        $retorno['title']  = $outState['title'];
        $retorno['subtitle']  = $outState['subtitle'];
        $retorno['code']  = $outState['code'];
        echo json_encode($retorno);
        exit();
    elseif($state == 'OK' || $state == 'WARNING'):
        // LOJA ABERTA
        $retorno['title']  = $outState['title'];
        $retorno['subtitle']  = $validation1['code'];
        $retorno['code']  = $outState['code'];
        echo json_encode($retorno);
        exit();
    endif;
endif;

//$_POST['polling'] = true;

if (isset($_POST['polling']) && $_POST['polling'] == true) :
    require("conexao_hostgator.php");

    $retorno = array();

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
                // START - Consulta BD se este evento ja foi recebido e tratado, caso sim pula diretamente para acknowledgment
                //
                $sql = "SELECT * FROM ifood_events WHERE id=:id && orderId=:orderId && createdAt=:createdAt";
                $stmt = $conexao->prepare($sql);
                $stmt->bindParam(':id', $polId);
                $stmt->bindParam(':orderId', $polOrderId);	
                $stmt->bindParam(':createdAt', $polCreatedAt);
                $stmt->execute();
                $contar = $stmt->rowCount();

                //$sql = "SELECT * FROM ifood_events WHERE id='$polId' && orderId='$polOrderId' && createdAt='$polCreatedAt'";
                //$resultado = $conexao->prepare($sql);	
                //$resultado->execute();
                //$contar = $resultado->rowCount();

                if($contar > 0):
                    //MANDA PARA acknowledgment
                    $ack = json_encode($in);
                    $send = "[ $ack ]";

                    $outAck = acknowledgment($send, $accessToken);
                        
                    if($outAck['code'] != 202):
                        errorLog('error-ifood_events-'.$outAck['code'].'-'.$outAck['mensagem']);
                    endif;
                    continue;
                endif;
                //
                // END
                //

                // PEDIDO NOVO
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
                        $customerCountOnMerchant = (isset($orderDetailsCustomer['ordersCountOnMerchant'])) ? $orderDetailsCustomer['ordersCountOnMerchant'] : '' ;
                            $customerNumber = (isset($orderDetailsCustomerPhone['number'])) ? $orderDetailsCustomerPhone['number'] : null ;
                            $customerLocalizer = (isset($orderDetailsCustomerPhone['localizer'])) ? $orderDetailsCustomerPhone['localizer'] : null ;
                            $customerLocalizerExpiration = (isset($orderDetailsCustomerPhone['localizerExpiration'])) ? $orderDetailsCustomerPhone['localizerExpiration'] : null ;

                    $isTest = (isset($orderDetails['isTest'])) ? $orderDetails['isTest'] : '' ;

                    $extraInfo = (isset($orderDetails['extraInfo'])) ? $orderDetails['extraInfo'] : null ;

                    $originCancellation = null;
                    $statusTekeout = null;
                    $statusDelivery = null;
                    $onDemandAvailable = null;
                    $onDemandValue = null;                 

                    $statusCod = $polCode ;

                    if($orderTiming == 'IMMEDIATE'):
                        $schedule = '';
                    elseif($orderTiming == 'SCHEDULED'):
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
                            errorLog('error-ifood_delivery_anddress-101-Erro interno BD.');
                        endif;
                    elseif($orderType == 'INDOOR'):
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

                    //
                    //
                    // BENEFITS
                    //
                    //

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
                    
                        foreach($sponValue as $sponIn){
                            $valor = $sponIn['value'];
                            $name = $sponIn['name'];

                            if($valor > 0):
                                $sql = 'INSERT INTO ifood_benefits (orderId, valueBenef, nameBenef) VALUES (:orderId, :valueBenef, :nameBenef)';
                                $stmt = $conexao->prepare($sql);
                                $stmt->bindParam(':orderId', $polOrderId);
                                $stmt->bindParam(':valueBenef', $valor);
                                $stmt->bindParam(':nameBenef', $name);
                                $resposta = $stmt->execute();

                                if(!$resposta):
                                    errorLog('error-ifood_benefits-101-Erro interno BD.');
                                endif;
                            endif;
                        };
                    endif;
                    //
                    //
                    // PAYMENTS
                    //
                    //
                    $total = (array) $orderDetails['total'];
                    $payments = (array) $orderDetails['payments'];
                    $methods = (array) $payments['methods'];

                    $subTotal = (isset($total['subTotal'])) ? $total['subTotal'] : '' ;
                    $deliveryFee = (isset($total['deliveryFee'])) ? $total['deliveryFee'] : '' ;
                    $benefits = (isset($total['benefits'])) ? $total['benefits'] : '' ;
                    $orderAmount = (isset($total['orderAmount'])) ? $total['orderAmount'] : '' ;
                    $additionalFees = (isset($total['additionalFees'])) ? $total['additionalFees'] : '' ;

                    $prepaid = (isset($payments['prepaid'])) ? $payments['prepaid'] : '' ;
                    $pending = (isset($payments['pending'])) ? $payments['pending'] : '' ;

                    foreach($methods as $inMethods){
                        $inMethods = (array) $inMethods;

                        $methodValue = (isset($inMethods['value'])) ? $inMethods['value'] : '' ;
                        $methodMethod = (isset($inMethods['method'])) ? $inMethods['method'] : '' ;
                        $methodType = (isset($inMethods['type'])) ? $inMethods['type'] : '' ;

                        $changeFor = null; $brand = null; $walletName = null; 

                        if($methodMethod == 'CASH'):
                            if(array_key_exists("cash", $inMethods)):
                                $cash = (array) $inMethods['cash'];
                                $changeFor = $cash['changeFor'];
                            endif;
                        elseif($methodMethod == 'DIGITAL_WALLET'):
                            if(array_key_exists("wallet", $inMethods)):
                                $wallet = (array) $inMethods['wallet'];
                                $walletName = $wallet['name'];
                            endif;
                            if(array_key_exists("card", $inMethods)):
                                $card = (array) $inMethods['card'];
                                $brand = $card['brand'];
                            endif;  
                        elseif($methodMethod == 'CREDIT' || $methodMethod == 'DEBIT' || $methodMethod == 'MEAL_VOUCHER' || $methodMethod == 'FOOD_VOUCHER' || $methodMethod == 'PIX'):
                            if(array_key_exists("card", $inMethods)):
                                $card = (array) $inMethods['card'];
                                $brand = $card['brand'];  
                            endif;
                        endif;

                        $changeFor = (isset($changeFor)) ? $changeFor : null ;
                        $brand = (isset($brand)) ? $brand : null ;
                        $walletName = (isset($walletName)) ? $walletName : null ;

                        $sql = 'INSERT INTO ifood_total_payments (orderId, subTotal, deliveryFee, additionalFees, benefits, orderAmount, prepaid, pending, methods_value, methods_type, methods_method, methods_wallet_name, methods_card_brand, methods_cash_changeFor) VALUES (:orderId, :subTotal, :deliveryFee, :additionalFees, :benefits, :orderAmount, :prepaid, :pending, :methods_value, :methods_type, :methods_method, :methods_wallet_name, :methods_card_brand, :methods_cash_changeFor)';
                        $stmt = $conexao->prepare($sql);
                        $stmt->bindParam(':orderId', $polOrderId);
                        $stmt->bindParam(':subTotal', $subTotal);
                        $stmt->bindParam(':deliveryFee', $deliveryFee);
                        $stmt->bindParam(':additionalFees', $additionalFees);
                        $stmt->bindParam(':benefits', $benefits);
                        $stmt->bindParam(':orderAmount', $orderAmount);
                        $stmt->bindParam(':prepaid', $prepaid);
                        $stmt->bindParam(':pending', $pending);
                        $stmt->bindParam(':methods_value', $methodValue);
                        $stmt->bindParam(':methods_type', $methodType);
                        $stmt->bindParam(':methods_method', $methodMethod);
                        $stmt->bindParam(':methods_wallet_name', $walletName);
                        $stmt->bindParam(':methods_card_brand', $brand);
                        $stmt->bindParam(':methods_cash_changeFor', $changeFor);
                        $resposta = $stmt->execute();

                        if(!$resposta):
                            errorLog('error-ifood_total_payments-101-Erro interno BD.');
                        endif;
                    };

                    //
                    //
                    // ITEMS
                    //
                    //

                    foreach($orderDetails['items'] as $item){
                        $item = (array) $item;
                    
                        $itemIndex = (isset($item['index'])) ? $item['index'] : '' ;
                        $itemId = (isset($item['id'])) ? $item['id'] : '' ;
                        $itemName = (isset($item['name'])) ? $item['name'] : '' ;
                        $itemImage = (isset($item['imageUrl'])) ? $item['imageUrl'] : null ;
                        $itemExternalCode = (isset($item['externalCode'])) ? $item['externalCode'] : null ;
                        $itemEan = (isset($item['ean'])) ? $item['ean'] : null ;
                        $itemQtd = (isset($item['quantity'])) ? $item['quantity'] : '' ;
                        $itemUn = (isset($item['unit'])) ? $item['unit'] : null ;
                        $itemUnitPrice = (isset($item['unitPrice'])) ? $item['unitPrice'] : '' ;
                        $itemAddition = (isset($item['addition'])) ? $item['addition'] : null ;
                        $itemPrice = (isset($item['price'])) ? $item['price'] : '' ;
                        $itemOpPrice = (isset($item['optionsPrice'])) ? $item['optionsPrice'] : '' ;
                        $itemTotalPrice = (isset($item['totalPrice'])) ? $item['totalPrice'] : '' ;
                        $itemObs = (isset($item['observations'])) ? $item['observations'] : null ;
                    
                        $sql = 'INSERT INTO ifood_order_items (orderId, indexId, id, itemName, imageUrl, externalCode, ean, quantity, unit, unitPrice, addition, price, optionsPrice, totalPrice, observations) VALUES (:orderId, :indexId, :id, :itemName, :imageUrl, :externalCode, :ean, :quantity, :unit, :unitPrice, :addition, :price, :optionsPrice, :totalPrice, :observations)';
                        $stmt = $conexao->prepare($sql);
                        $stmt->bindParam(':orderId', $polOrderId);
                        $stmt->bindParam(':indexId', $itemIndex);
                        $stmt->bindParam(':id', $itemId);
                        $stmt->bindParam(':itemName', $itemName);
                        $stmt->bindParam(':imageUrl', $itemImage);
                        $stmt->bindParam(':externalCode', $itemExternalCode);
                        $stmt->bindParam(':ean', $itemEan);
                        $stmt->bindParam(':quantity', $itemQtd);
                        $stmt->bindParam(':unit', $itemUn);
                        $stmt->bindParam(':unitPrice', $itemUnitPrice);
                        $stmt->bindParam(':addition', $itemAddition);
                        $stmt->bindParam(':price', $itemPrice);
                        $stmt->bindParam(':optionsPrice', $itemOpPrice);
                        $stmt->bindParam(':totalPrice', $itemTotalPrice);
                        $stmt->bindParam(':observations', $itemObs);
                        $resposta = $stmt->execute();

                        if(!$resposta):
                            errorLog('error-ifood_order_items-101-Erro interno BD.');
                        endif;
                    
                        if(array_key_exists("options", $item)):
                            foreach($item['options'] as $itemOption){
                                $itemOption = (array) $itemOption;
                    
                                $optionIndex = (isset($itemOption['index'])) ? $itemOption['index'] : '' ;
                                $optionId = (isset($itemOption['id'])) ? $itemOption['id'] : '' ;
                                $optionName = (isset($itemOption['name'])) ? $itemOption['name'] : '' ;
                                $optionExternalCode = (isset($itemOption['externalCode'])) ? $itemOption['externalCode'] : '' ;
                                $optionEan = (isset($itemOption['ean'])) ? $itemOption['ean'] : '' ;
                                $optionQtd = (isset($itemOption['quantity'])) ? $itemOption['quantity'] : '' ;
                                $optionUn = (isset($itemOption['unit'])) ? $itemOption['unit'] : '' ;
                                $optionUnitPrice = (isset($itemOption['unitPrice'])) ? $itemOption['unitPrice'] : '' ;
                                $optionAddition = (isset($itemOption['addition'])) ? $itemOption['addition'] : '' ;
                                $optionPrice = (isset($itemOption['price'])) ? $itemOption['price'] : '' ;
                    
                                $sql = 'INSERT INTO ifood_items_options (id, orderId, indexId, itemId, optionName, externalCode, ean, quantity, unit, unitPrice, addition, price) VALUES (:id, :orderId, :indexId, :itemId, :optionName, :externalCode, :ean, :quantity, :unit, :unitPrice, :addition, :price)';
                                $stmt = $conexao->prepare($sql);
                                $stmt->bindParam(':id', $optionId);
                                $stmt->bindParam(':orderId', $polOrderId);
                                $stmt->bindParam(':indexId', $optionIndex);
                                $stmt->bindParam(':itemId', $itemId);
                                $stmt->bindParam(':optionName', $optionName);
                                $stmt->bindParam(':externalCode', $optionExternalCode);
                                $stmt->bindParam(':ean', $optionEan);
                                $stmt->bindParam(':quantity', $optionQtd);
                                $stmt->bindParam(':unit', $optionUn);
                                $stmt->bindParam(':unitPrice', $optionUnitPrice);
                                $stmt->bindParam(':addition', $optionAddition);
                                $stmt->bindParam(':price', $optionPrice);
                                $resposta = $stmt->execute();

                                if(!$resposta):
                                    errorLog('error-ifood_items_options-101-Erro interno BD.');
                                endif;
                            };
                        endif;
                    };

                    //continue;

                    //VALIDA CAMPOS EMPTY

                    $sql = 'INSERT INTO ifood_orders (orderId, displayId, orderType, orderTiming, salesChannel, dateCreated, preparationStartDateTime, merchantId, merchantName, customerId, customerName, customerDocument, customerCountOnMerchant, customerNumber, customerLocalizer, customerLocalizerExpiration, isTest, extraInfo, originCancellation, statusTekeout, statusDelivery, onDemandAvailable, onDemandValue, mode, deliveredBy, deliveryDateTime, takeoutDateTime, tableIndoor, observations, deliveryDateTimeStart, deliveryDateTimeEnd, statusCod) VALUES (:orderId, :displayId, :orderType, :orderTiming, :salesChannel, :dateCreated, :preparationStartDateTime, :merchantId, :merchantName, :customerId, :customerName, :customerDocument, :customerCountOnMerchant, :customerNumber, :customerLocalizer, :customerLocalizerExpiration, :isTest, :extraInfo, :originCancellation, :statusTekeout, :statusDelivery, :onDemandAvailable, :onDemandValue, :mode, :deliveredBy, :deliveryDateTime, :takeoutDateTime, :tableIndoor, :observations, :deliveryDateTimeStart, :deliveryDateTimeEnd, :statusCod)';
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
                    $stmt->bindParam(':customerCountOnMerchant', $customerCountOnMerchant);
                    $stmt->bindParam(':customerNumber', $customerNumber);
                    $stmt->bindParam(':customerLocalizer', $customerLocalizer);
                    $stmt->bindParam(':customerLocalizerExpiration', $customerLocalizerExpiration);
                    $stmt->bindParam(':isTest', $isTest);
                    $stmt->bindParam(':extraInfo', $extraInfo);
                    $stmt->bindParam(':originCancellation', $originCancellation);
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
                        errorLog('error-ifood_orders-101-Erro interno BD.');
                    endif;
                    //
                    //  ENVIA EVENTRO PARA BD
                    //
                    $sql = 'INSERT INTO ifood_events (id, orderId, createdAt) VALUES (:id, :orderId, :createdAt)';
                    $stmt = $conexao->prepare($sql);
                    $stmt->bindParam(':id', $polId);
                    $stmt->bindParam(':orderId', $polOrderId);
                    $stmt->bindParam(':createdAt', $polCreatedAt);
                    $resposta = $stmt->execute();

                    if(!$resposta):
                        errorLog('error-ifood_events-101-Erro interno BD.');
                    endif;
                endif;

                // ATUALIZAÇÃO DE STATUS
                if($polCode == 'CFM' || $polCode == 'RTP' || $polCode == 'DSP' || $polCode == 'CON' || $polCode == 'CAN'):
                    //
                    // CFM - Pedido foi confirmado e será preparado
                    // RTP - Indica que o pedido está pronto para ser retirado (Pra Retirar ou Na Mesa)
                    // DSP - Indica que o pedido saiu para entrega (Delivery)
                    // CON - Pedido foi concluído
                    // CAN - Pedido foi Cancelado
                    //
                    $sql = 'UPDATE ifood_orders SET statusCod=:statusCod WHERE orderId=:orderId && merchantId=:merchantId';
                    $stmt = $conexao->prepare($sql);
                    $stmt->bindParam(':statusCod', $polCode);
                    $stmt->bindParam(':orderId', $polOrderId);
                    $stmt->bindParam(':merchantId', $merchantId);
                    $resposta = $stmt->execute();

                    if(!$resposta):
                        errorLog('error-ifood_delivery_anddress-101-Erro interno BD.');
                    endif;
                    //
                    //  ENVIA EVENTRO PARA BD
                    //
                    $sql = 'INSERT INTO ifood_events (id, orderId, createdAt) VALUES (:id, :orderId, :createdAt)';
                    $stmt = $conexao->prepare($sql);
                    $stmt->bindParam(':id', $polId);
                    $stmt->bindParam(':orderId', $polOrderId);
                    $stmt->bindParam(':createdAt', $polCreatedAt);
                    $resposta = $stmt->execute();

                    if(!$resposta):
                        errorLog('error-ifood_events-101-Erro interno BD.');
                    endif;
                endif;
                //
                // START - CANCELAMENTOS
                //
                if($polCode == 'CAN'):
                    //
                    // Pedido foi Cancelado
                    //
                    $metadata       = (array) $in['metadata'];
                    $stage    = $metadata['CANCEL_STAGE'];
                    $code        = $metadata['CANCEL_CODE'];
                    $origin         = $metadata['CANCEL_ORIGIN'];
                    $reason         = $metadata['CANCEL_REASON'];

                    $sql = 'INSERT INTO ifood_cancel_finish (orderId, stage, code, origin, reason) VALUES (:orderId, :stage, :code, :origin, :reason)';
                    $stmt = $conexao->prepare($sql);
                    $stmt->bindParam(':orderId', $polOrderId);
                    $stmt->bindParam(':stage', $stage);
                    $stmt->bindParam(':code', $code);
                    $stmt->bindParam(':origin', $origin);
                    $stmt->bindParam(':reason', $reason);
                    $resposta = $stmt->execute();

                    if(!$resposta):
                        errorLog('error-ifood_cancel_finish-101-Erro interno BD.');
                    endif;
                endif;

                if($polCode == 'CAR'):
                    //
                    // Solicitação de cancelamento feita pelo Merchant (loja) ou pelo iFood (atendimento ao cliente)
                    //
                    $metadata       = (array) $in['metadata'];
                    $reason_code    = $metadata['reason_code'];
                    $details        = $metadata['details'];
                    $origin         = $metadata['ORIGIN'];

                    $sql = 'INSERT INTO ifood_cancel_merchant (orderId, reasonCode, details, origin) VALUES (:orderId, :reasonCode, :details, :origin)';
                    $stmt = $conexao->prepare($sql);
                    $stmt->bindParam(':orderId', $polOrderId);
                    $stmt->bindParam(':reasonCode', $reason_code);
                    $stmt->bindParam(':details', $details);
                    $stmt->bindParam(':origin', $origin);
                    $resposta = $stmt->execute();

                    if(!$resposta):
                        errorLog('error-ifood_cancel_merchant-101-Erro interno BD.');
                    endif;

                    $originCancellation = 'merchant';

                    $sql = 'UPDATE ifood_orders SET originCancellation=:originCancellation WHERE orderId=:orderId && merchantId=:merchantId';
                    $stmt = $conexao->prepare($sql);
                    $stmt->bindParam(':originCancellation', $originCancellation);
                    $stmt->bindParam(':orderId', $polOrderId);
                    $stmt->bindParam(':merchantId', $merchantId);
                    $resposta = $stmt->execute();

                    if(!$resposta):
                        errorLog('error-ifood_orders-101-Erro interno BD.');
                    endif;
                    //
                    //  ENVIA EVENTRO PARA BD
                    //
                    $sql = 'INSERT INTO ifood_events (id, orderId, createdAt) VALUES (:id, :orderId, :createdAt)';
                    $stmt = $conexao->prepare($sql);
                    $stmt->bindParam(':id', $polId);
                    $stmt->bindParam(':orderId', $polOrderId);
                    $stmt->bindParam(':createdAt', $polCreatedAt);
                    $resposta = $stmt->execute();

                    if(!$resposta):
                        errorLog('error-ifood_events-101-Erro interno BD.');
                    endif;
                endif;

                if($polCode == 'CCR'):
                    //
                    // Solicitação de cancelamento feita pelo cliente
                    //
                    $metadata       = (array) $in['metadata'];
                    $cancelReason    = $metadata['CANCEL_REASON'];
                    $cancelUser        = $metadata['CANCEL_USER'];
                    $cancelCode         = $metadata['CANCEL_CODE'];

                    $sql = 'INSERT INTO ifood_cancel_customer (orderId, cancelReason, cancelUser, cancelCode) VALUES (:orderId, :cancelReason, :cancelUser, :cancelCode)';
                    $stmt = $conexao->prepare($sql);
                    $stmt->bindParam(':orderId', $polOrderId);
                    $stmt->bindParam(':cancelReason', $cancelReason);
                    $stmt->bindParam(':cancelUser', $cancelUser);
                    $stmt->bindParam(':cancelCode', $cancelCode);
                    $resposta = $stmt->execute();

                    if(!$resposta):
                        errorLog('error-ifood_cancel_merchant-101-Erro interno BD.');
                    endif;

                    $originCancellation = 'customer';

                    $sql = 'UPDATE ifood_orders SET originCancellation=:originCancellation WHERE orderId=:orderId && merchantId=:merchantId';
                    $stmt = $conexao->prepare($sql);
                    $stmt->bindParam(':originCancellation', $originCancellation);
                    $stmt->bindParam(':orderId', $polOrderId);
                    $stmt->bindParam(':merchantId', $merchantId);
                    $resposta = $stmt->execute();

                    if(!$resposta):
                        errorLog('error-ifood_orders-101-Erro interno BD.');
                    endif;
                    //
                    //  ENVIA EVENTRO PARA BD
                    //
                    $sql = 'INSERT INTO ifood_events (id, orderId, createdAt) VALUES (:id, :orderId, :createdAt)';
                    $stmt = $conexao->prepare($sql);
                    $stmt->bindParam(':id', $polId);
                    $stmt->bindParam(':orderId', $polOrderId);
                    $stmt->bindParam(':createdAt', $polCreatedAt);
                    $resposta = $stmt->execute();

                    if(!$resposta):
                        errorLog('error-ifood_events-101-Erro interno BD.');
                    endif;
                endif;

                if($polCode == 'CARF'):
                    //
                    // Solicitação de cancelamento do merchant negada
                    //
                    $sql = 'UPDATE ifood_cancel_merchant SET result=:result WHERE orderId=:orderId';
                    $stmt = $conexao->prepare($sql);
                    $stmt->bindParam(':result', $polCode);
                    $stmt->bindParam(':orderId', $polOrderId);
                    $resposta = $stmt->execute();

                    if(!$resposta):
                        errorLog('error-ifood_orders-101-Erro interno BD.');
                    endif;
                    //
                    //  ENVIA EVENTRO PARA BD
                    //
                    $sql = 'INSERT INTO ifood_events (id, orderId, createdAt) VALUES (:id, :orderId, :createdAt)';
                    $stmt = $conexao->prepare($sql);
                    $stmt->bindParam(':id', $polId);
                    $stmt->bindParam(':orderId', $polOrderId);
                    $stmt->bindParam(':createdAt', $polCreatedAt);
                    $resposta = $stmt->execute();

                    if(!$resposta):
                        errorLog('error-ifood_events-101-Erro interno BD.');
                    endif;
                endif;

                if($polCode == 'CCA' || $polCode == 'CCD'):
                    //
                    // CCA - A solicitação de cancelamento feita pelo cliente foi aprovada pelo Merchant (loja)
                    // CCD - A solicitação de cancelamento feita pelo cliente foi negada pelo Merchant (loja)
                    //
                    $sql = 'UPDATE ifood_cancel_customer SET result=:result WHERE orderId=:orderId';
                    $stmt = $conexao->prepare($sql);
                    $stmt->bindParam(':result', $polCode);
                    $stmt->bindParam(':orderId', $polOrderId);
                    $resposta = $stmt->execute();

                    if(!$resposta):
                        errorLog('error-ifood_orders-101-Erro interno BD.');
                    endif;
                    //
                    //  ENVIA EVENTRO PARA BD
                    //
                    $sql = 'INSERT INTO ifood_events (id, orderId, createdAt) VALUES (:id, :orderId, :createdAt)';
                    $stmt = $conexao->prepare($sql);
                    $stmt->bindParam(':id', $polId);
                    $stmt->bindParam(':orderId', $polOrderId);
                    $stmt->bindParam(':createdAt', $polCreatedAt);
                    $resposta = $stmt->execute();

                    if(!$resposta):
                        errorLog('error-ifood_events-101-Erro interno BD.');
                    endif;
                endif;
                //
                // END - CANCELAMENTOS
                //

                //
                // START - TAKEOUT
                //
                if($polCode == 'PAA'):
                    //
                    // PAA - Cliente está aguardando na vaga especial para retirar o pedido
                    //
                    $metadata       = (array) $in['metadata'];
                    $pickupAreaCode    = (isset($metadata['PICKUP_AREA_CODE'])) ? $metadata['PICKUP_AREA_CODE'] : null ;
                    $pickupAreaType    = (isset($metadata['PICKUP_AREA_TYPE'])) ? $metadata['PICKUP_AREA_TYPE'] : null ;

                    $sql = 'INSERT INTO ifood_takeout (orderId, areaCode, areaType) VALUES (:orderId, :areaCode, :areaType)';
                    $stmt = $conexao->prepare($sql);
                    $stmt->bindParam(':orderId', $polOrderId);
                    $stmt->bindParam(':areaCode', $pickupAreaCode);
                    $stmt->bindParam(':areaType', $pickupAreaType);
                    $resposta = $stmt->execute();

                    if(!$resposta):
                        errorLog('error-ifood_events-101-Erro interno BD.');
                    endif;

                    $sql = 'UPDATE ifood_orders SET statusTekeout=:statusTekeout WHERE orderId=:orderId && merchantId=:merchantId';
                    $stmt = $conexao->prepare($sql);
                    $stmt->bindParam(':statusTekeout', $polCode);
                    $stmt->bindParam(':orderId', $polOrderId);
                    $stmt->bindParam(':merchantId', $merchantId);
                    $resposta = $stmt->execute();

                    if(!$resposta):
                        errorLog('error-ifood_orders-101-Erro interno BD.');
                    endif;
                    //
                    //  ENVIA EVENTRO PARA BD
                    //
                    $sql = 'INSERT INTO ifood_events (id, orderId, createdAt) VALUES (:id, :orderId, :createdAt)';
                    $stmt = $conexao->prepare($sql);
                    $stmt->bindParam(':id', $polId);
                    $stmt->bindParam(':orderId', $polOrderId);
                    $stmt->bindParam(':createdAt', $polCreatedAt);
                    $resposta = $stmt->execute();

                    if(!$resposta):
                        errorLog('error-ifood_events-101-Erro interno BD.');
                    endif;
                endif;
                //
                // END - TAKEOUT
                //

                //
                // START - DELIVERY
                //
                if($polCode == 'ADR'):
                    //
                    // ADR - Um entregador foi alocado para realizar a entrega
                    //
                    $metadata       = (array) $in['metadata'];
                    $workerPhone    = (isset($metadata['workerPhone'])) ? $metadata['workerPhone'] : '' ;
                    $workerName    = (isset($metadata['workerName'])) ? $metadata['workerName'] : '' ;
                    $workerExternalUuid    = (isset($metadata['workerExternalUuid'])) ? $metadata['workerExternalUuid'] : '' ;
                    $workerPhotoUrl    = (isset($metadata['workerPhotoUrl'])) ? $metadata['workerPhotoUrl'] : '' ;

                    $sql = 'INSERT INTO ifood_delivery_ifood (orderId, code, workerPhone, workerName, workerExternalUuid, workerPhotoUrl) VALUES (:orderId, :code, :workerPhone, :workerName, :workerExternalUuid, :workerPhotoUrl)';
                    $stmt = $conexao->prepare($sql);
                    $stmt->bindParam(':orderId', $polOrderId);
                    $stmt->bindParam(':code', $polCode);
                    $stmt->bindParam(':workerPhone', $workerPhone);
                    $stmt->bindParam(':workerName', $workerName);
                    $stmt->bindParam(':workerExternalUuid', $workerExternalUuid);
                    $stmt->bindParam(':workerPhotoUrl', $workerPhotoUrl);
                    $resposta = $stmt->execute();

                    if(!$resposta):
                        errorLog('error-ifood_delivery_ifood-101-Erro interno BD.');
                    endif;

                    $sql = 'UPDATE ifood_orders SET statusDelivery=:statusDelivery WHERE orderId=:orderId && merchantId=:merchantId';
                    $stmt = $conexao->prepare($sql);
                    $stmt->bindParam(':statusDelivery', $polCode);
                    $stmt->bindParam(':orderId', $polOrderId);
                    $stmt->bindParam(':merchantId', $merchantId);
                    $resposta = $stmt->execute();

                    if(!$resposta):
                        errorLog('error-ifood_orders-101-Erro interno BD.');
                    endif;
                    //
                    //  ENVIA EVENTRO PARA BD
                    //
                    $sql = 'INSERT INTO ifood_events (id, orderId, createdAt) VALUES (:id, :orderId, :createdAt)';
                    $stmt = $conexao->prepare($sql);
                    $stmt->bindParam(':id', $polId);
                    $stmt->bindParam(':orderId', $polOrderId);
                    $stmt->bindParam(':createdAt', $polCreatedAt);
                    $resposta = $stmt->execute();

                    if(!$resposta):
                        errorLog('error-ifood_events-101-Erro interno BD.');
                    endif;
                endif;
   
                if($polCode == 'GTO' || $polCode == 'AAO' || $polCode == 'CLT' || $polCode == 'AAD'):
                    //
                    // GTO - Entregador está a caminho da origem para retirar o pedido
                    // AAO - Entregador chegou na origem para retirar o pedido
                    // CLT - Entregador coletou o pedido
                    // AAD - Entregador chegou no endereço de destino
                    //
                    $sql = 'UPDATE ifood_orders SET statusDelivery=:statusDelivery WHERE orderId=:orderId && merchantId=:merchantId';
                    $stmt = $conexao->prepare($sql);
                    $stmt->bindParam(':statusDelivery', $polCode);
                    $stmt->bindParam(':orderId', $polOrderId);
                    $stmt->bindParam(':merchantId', $merchantId);
                    $resposta = $stmt->execute();

                    if(!$resposta):
                        errorLog('error-ifood_orders-101-Erro interno BD.');
                    endif;
                    //
                    //  ENVIA EVENTRO PARA BD
                    //
                    $sql = 'INSERT INTO ifood_events (id, orderId, createdAt) VALUES (:id, :orderId, :createdAt)';
                    $stmt = $conexao->prepare($sql);
                    $stmt->bindParam(':id', $polId);
                    $stmt->bindParam(':orderId', $polOrderId);
                    $stmt->bindParam(':createdAt', $polCreatedAt);
                    $resposta = $stmt->execute();

                    if(!$resposta):
                        errorLog('error-ifood_events-101-Erro interno BD.');
                    endif;
                endif;

                if($polCode == 'RDA'):
                    //
                    // RDA - Indica se o pedido é elegível para requisitar o serviço de entrega sob demanda e o custo do serviço caso seja elegível
                    //
                    $metadata       = (array) $in['metadata'];
                    $available    = (isset($metadata['available'])) ? $metadata['available'] : 0;

                    if($available == true):
                        $quote = (array) $metadata['quote'];
                        $final = (array) $quote['final'];

                        $value    = (isset($final['value'])) ? $final['value'] : '';
                        $rejectReason = null;
                    else:
                        $value = null;
                        $rejectReason = (isset($metadata['rejectReason'])) ? $metadata['rejectReason'] : null;
                    endif;

                    $sql = 'UPDATE ifood_orders SET onDemandAvailable=:onDemandAvailable, onDemandValue=:onDemandValue, onDemandRejectReason=:onDemandRejectReason WHERE orderId=:orderId && merchantId=:merchantId';
                    $stmt = $conexao->prepare($sql);
                    $stmt->bindParam(':onDemandAvailable', $available);
                    $stmt->bindParam(':onDemandValue', $value);
                    $stmt->bindParam(':onDemandRejectReason', $rejectReason);
                    $stmt->bindParam(':orderId', $polOrderId);
                    $stmt->bindParam(':merchantId', $merchantId);
                    $resposta = $stmt->execute();

                    if(!$resposta):
                        errorLog('error-ifood_orders-101-Erro interno BD.');
                    endif;
                    //
                    //  ENVIA EVENTRO PARA BD
                    //
                    $sql = 'INSERT INTO ifood_events (id, orderId, createdAt) VALUES (:id, :orderId, :createdAt)';
                    $stmt = $conexao->prepare($sql);
                    $stmt->bindParam(':id', $polId);
                    $stmt->bindParam(':orderId', $polOrderId);
                    $stmt->bindParam(':createdAt', $polCreatedAt);
                    $resposta = $stmt->execute();

                    if(!$resposta):
                        errorLog('error-ifood_events-101-Erro interno BD.');
                    endif;
                endif;

                if($polCode == 'RDR' || $polCode == 'RDS'):
                    //
                    // RDR - Indica que foi feita uma requisição do serviço de entrega sob demanda
                    // RDS - Requisição de entrega aprovada
                    //
                    $sql = 'UPDATE ifood_orders SET statusDelivery=:statusDelivery WHERE orderId=:orderId && merchantId=:merchantId';
                    $stmt = $conexao->prepare($sql);
                    $stmt->bindParam(':statusDelivery', $polCode);
                    $stmt->bindParam(':orderId', $polOrderId);
                    $stmt->bindParam(':merchantId', $merchantId);
                    $resposta = $stmt->execute();

                    if(!$resposta):
                        errorLog('error-ifood_orders-101-Erro interno BD.');
                    endif;
                    //
                    //  ENVIA EVENTRO PARA BD
                    //
                    $sql = 'INSERT INTO ifood_events (id, orderId, createdAt) VALUES (:id, :orderId, :createdAt)';
                    $stmt = $conexao->prepare($sql);
                    $stmt->bindParam(':id', $polId);
                    $stmt->bindParam(':orderId', $polOrderId);
                    $stmt->bindParam(':createdAt', $polCreatedAt);
                    $resposta = $stmt->execute();

                    if(!$resposta):
                        errorLog('error-ifood_events-101-Erro interno BD.');
                    endif;
                endif;

                if($polCode == 'RDF'):
                    //
                    // RDF - Requisição de entrega negada - Valores possíveis: SAFE_MODE_ON, OFF_WORKING_SHIFT_POST, CLOSED_REGION, SATURATED_REGION
                    //
                    $metadata       = (array) $in['metadata'];
                    $available    = (isset($metadata['available'])) ? $metadata['available'] : null;
                    $rejectReason    = (isset($metadata['rejectReason'])) ? $metadata['rejectReason'] : null;

                    $sql = 'UPDATE ifood_orders SET statusDelivery=:statusDelivery, onDemandAvailable=:onDemandAvailable, onDemandRejectReason=:onDemandRejectReason WHERE orderId=:orderId && merchantId=:merchantId';
                    $stmt = $conexao->prepare($sql);
                    $stmt->bindParam(':statusDelivery', $polCode);
                    $stmt->bindParam(':onDemandAvailable', $available);
                    $stmt->bindParam(':onDemandRejectReason', $rejectReason);
                    $stmt->bindParam(':orderId', $polOrderId);
                    $stmt->bindParam(':merchantId', $merchantId);
                    $resposta = $stmt->execute();

                    if(!$resposta):
                        errorLog('error-ifood_orders-101-Erro interno BD.');
                    endif;
                    //
                    //  ENVIA EVENTRO PARA BD
                    //
                    $sql = 'INSERT INTO ifood_events (id, orderId, createdAt) VALUES (:id, :orderId, :createdAt)';
                    $stmt = $conexao->prepare($sql);
                    $stmt->bindParam(':id', $polId);
                    $stmt->bindParam(':orderId', $polOrderId);
                    $stmt->bindParam(':createdAt', $polCreatedAt);
                    $resposta = $stmt->execute();

                    if(!$resposta):
                        errorLog('error-ifood_events-101-Erro interno BD.');
                    endif;
                endif;
                //
                // END - DELIVERY
                //

                //MANDA PARA acknowledgment
                $ack = json_encode($in);
                $send = "[ $ack ]";

                $outAck = acknowledgment($send, $accessToken);
                    
                if($outAck['code'] != 202):
                    errorLog('error-ifood_events-'.$outAck['code'].'-'.$outAck['mensagem']);
                endif;
            };
            $retorno['mensagem']  = 'Todos os eventos tratados.';
            $retorno['code']  = 200;
        else:
            errorLog('error-if_count-101-Erro interno.');
            $retorno['mensagem']  = 'Erro interno "if count".';
            $retorno['code']  = 400;
        endif;
    elseif($outPolling['code'] == 204):
        $retorno['mensagem']  = 'Polling vazio!';
        $retorno['code']  = 200;
    else:
        errorLog('error-polling-'.$outPolling['code'].'-'.$outPolling['mensagem']);
        $retorno['mensagem']  = 'Erro interno "polling".';
        $retorno['code']  = 400;
    endif;
    echo json_encode($retorno);
endif;

if(isset($_POST['teste']) && $_POST['teste'] == true) :
    $x = 200;

    if($x == 200):
        $retorno['mensagem']  = 'Polling vazio!';
        $retorno['code']  = 200;
    else:
        $retorno['mensagem']  = 'Erro interno "polling".';
        $retorno['code']  = 400;
    endif;
    echo json_encode($retorno);
endif;