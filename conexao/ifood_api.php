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

    $title  = $outState['title'];
    $subtitle  = $outState['subtitle'];

    if($state == 'OK'):
        //
        // Indica que a loja está online.
        //
        $isConnectedStatus = $outState['validations']['is-connected']['state'];
        $openingHoursStatus = $outState['validations']['opening-hours']['state'];

        if($openingHoursStatus == 'OK' || $openingHoursStatus == 'WARNING'):
            $iconOpen = 'fa-solid fa-check text-success';
        elseif($openingHoursStatus == 'CLOSED' || $openingHoursStatus == 'ERROR'):
            $iconOpen = 'fa-regular fa-circle-exclamation text-black';
        endif;

        if($isConnectedStatus == 'OK' || $isConnectedStatus == 'WARNING'):
            $iconConnected = 'fa-solid fa-check text-success';
        elseif($isConnectedStatus == 'CLOSED' || $isConnectedStatus == 'ERROR'):
            $iconConnected = 'fa-regular fa-circle-exclamation text-black';
        endif;

        $retorno['merchantStatus']  = 'open';

        $html = '
            <div class="media align-items-center">
                <i class="fa-solid fa-circle-check text-success fs-16 mr-3"></i>
                <h4 class="fs-14 font-w600 text-black mb-0">'.$title.'</h4>
            </div>
            <hr class="">
            <div class="media py-2">
                <i class="'.$iconConnected.' fs-16 mr-3"></i>
                <h4 class="fs-14 font-w600 text-black mb-0">'.$outState['validations']['is-connected']['message']['title'].'</h4>
            </div>
            <div class="media py-2">
                <i class="'.$iconOpen.' fs-16 mr-3"></i>
                <h4 class="fs-14 font-w600 text-black mb-0">'.$outState['validations']['opening-hours']['message']['title'].' <br>
                    <span class="fs-12 font-w400">'.$outState['validations']['opening-hours']['message']['subtitle'].'</span>
                </h4>
            </div>
            <hr>
            <h5 class="fs-12 font-w500 text-black">Esta informação pode levar até 1 minuto parar atualizar depois de ser alterada.</h5>
            <button type="button" class="btn btn-red btn-sm btn-block mt-4 fechar"><span class="ml-2 fs-16">Fechar agora</span></button>';
    elseif($state == 'WARNING'):
        //
        // Indica que a loja está online, mas podem haver restrições como redução de área de entrega.
        //
        $isConnectedStatus = $outState['validations']['is-connected']['state'];
        $openingHoursStatus = $outState['validations']['opening-hours']['state'];

        if($openingHoursStatus == 'OK' || $openingHoursStatus == 'WARNING'):
            $iconOpen = 'fa-solid fa-check text-success';
        elseif($openingHoursStatus == 'CLOSED' || $openingHoursStatus == 'ERROR'):
            $iconOpen = 'fa-regular fa-circle-exclamation text-black';
        endif;

        if($isConnectedStatus == 'OK' || $isConnectedStatus == 'WARNING'):
            $iconConnected = 'fa-solid fa-check text-success';
        elseif($isConnectedStatus == 'CLOSED' || $isConnectedStatus == 'ERROR'):
            $iconConnected = 'fa-regular fa-circle-exclamation text-black';
        endif;

        $retorno['merchantStatus']  = 'open';

        $html = '
            <div class="media align-items-center">
                <i class="fa-solid fa-circle-check text-success fs-16 mr-3"></i>
                <h4 class="fs-14 font-w600 text-black mb-0">'.$title.'</h4>
            </div>
            <hr>
            <div class="media align-items-center pt-2 p-3 my-2 bg-observation-order" style="margin-left: -16px;margin-right: -16px;">
                <i class="fa-regular fa-clock text-black mr-3 fs-16"></i>
                <h4 class="fs-14 font-w600 text-black mb-0">'.$outState['validations']['radius-restriction']['message']['title'].'  <br><span class="fs-12 font-w400 ml-4">'.$outState['validations']['radius-restriction']['message']['subtitle'].'</span><br>
                    <span class="fs-12 font-w400 ml-4">Muitos pedidos (alterado no Gestor de Pedidos)</span>
                </h4>
            </div>
            <hr>
            <div class="media py-2">
                <i class="'.$iconConnected.' fs-16 mr-3"></i>
                <h4 class="fs-14 font-w600 text-black mb-0">'.$outState['validations']['is-connected']['message']['title'].'</h4>
            </div>
            <div class="media py-2">
                <i class="'.$iconOpen.' fs-16 mr-3"></i>
                <h4 class="fs-14 font-w600 text-black mb-0">'.$outState['validations']['opening-hours']['message']['title'].' <br>
                    <span class="fs-12 font-w400">'.$outState['validations']['opening-hours']['message']['subtitle'].'</span>
                </h4>
            </div>
            <hr>
            <h5 class="fs-12 font-w500 text-black">Esta informação pode levar até 1 minuto parar atualizar depois de ser alterada.</h5>
            <button type="button" class="btn btn-red btn-sm btn-block mt-4 fechar"><span class="ml-2 fs-16">Fechar agora</span></button>';
    elseif($state == 'CLOSED' || $state == 'ERROR'):
        //
        // CLOSED: Indica que a loja está fechada conforme esperado, como em casos de "fora do horário de funcionamento" ou "em pausa programada". Não requer nenhuma ação.
        // ERROR: Indica que a loja está fechada por algum motivo não esperado. Requer uma ação da loja.
        //
        $isConnectedStatus = $outState['validations']['is-connected']['state'];
        $openingHoursStatus = $outState['validations']['opening-hours']['state'];
        $unavailabilitiesStatus = $outState['validations']['unavailabilities']['state'];
        $radiusRestrictionStatus = $outState['validations']['radius-restriction']['state'];
        $payoutBlockedStatus = $outState['validations']['payout-blocked']['state'];
        $logisticsBlockedStatus = $outState['validations']['logistics-blocked']['state'];
        $termsServiceViolationStatus = $outState['validations']['terms-service-violation']['state'];
        $statusAvailabilityStatus = $outState['validations']['status-availability']['state'];

        $retorno['merchantStatus']  = 'open';

        if(!empty($unavailabilitiesStatus)):
            $linhaUnavailabilities = '
            <div class="media align-items-center pt-2 p-3 my-2 bg-observation-order" style="margin-left: -16px;margin-right: -16px;">
                <i class="fa-regular fa-clock text-black mr-3 fs-16"></i>
                <h4 class="fs-14 font-w600 text-black mb-0">'.$outState['validations']['unavailabilities']['message']['title'].'<br>
                    <span class="fs-12 font-w400">'.$outState['validations']['unavailabilities']['message']['subtitle'].'</span><br>
                    <span class="fs-12 font-w400">'.$outState['validations']['unavailabilities']['message']['description'].'</span>
                </h4>
            </div>';
            $button = '<button type="button" class="btn btn-success btn-sm btn-block mt-4 disabled" disabled><span class="ml-2 fs-16">Abrir agora</span></button>';
        else:
            $linhaUnavailabilities = '';
            $button = '<button type="button" class="btn btn-success btn-sm btn-block mt-4"><span class="ml-2 fs-16">Abrir agora</span></button>';
        endif;

        if(!empty($radiusRestrictionStatus)):
            $linhaRadiusRestriction = '
            <div class="media align-items-center pt-2 p-3 my-2 bg-observation-order" style="margin-left: -16px;margin-right: -16px;">
                <i class="fa-regular fa-clock text-black mr-3 fs-16"></i>
                <h4 class="fs-14 font-w600 text-black mb-0">'.$outState['validations']['radius-restriction']['message']['title'].'<br>
                    <span class="fs-12 font-w400">'.$outState['validations']['radius-restriction']['message']['subtitle'].'</span><br>
                    <span class="fs-12 font-w400">'.$outState['validations']['radius-restriction']['message']['description'].'</span>
                </h4>
            </div>';
        else:
            $linhaRadiusRestriction = '';
        endif;

        if(!empty($payoutBlockedStatus)):
            $linhaPayoutBlocked = '
            <div class="media align-items-center pt-2 p-3 my-2 bg-observation-order" style="margin-left: -16px;margin-right: -16px;">
                <i class="fa-regular fa-clock text-black mr-3 fs-16"></i>
                <h4 class="fs-14 font-w600 text-black mb-0">'.$outState['validations']['payout-blocked']['message']['title'].'<br>
                    <span class="fs-12 font-w400">'.$outState['validations']['payout-blocked']['message']['subtitle'].'</span><br>
                    <span class="fs-12 font-w400">'.$outState['validations']['payout-blocked']['message']['description'].'</span>
                </h4>
            </div>';
        else:
            $linhaPayoutBlocked = '';
        endif;

        if(!empty($logisticsBlockedStatus)):
            $linhaLogisticsBlocked = '<h4 class="fs-14 font-w600 text-black p-3 bg-observation-order" style="margin-left: -16px;margin-right: -16px;"><i class="fa-regular fa-clock text-black mr-2 fs-16"></i>'.$outState['validations']['logistics-blocked']['message']['title'].'  <br><span class="fs-12 font-w400 ml-4">'.$outState['validations']['logistics-blocked']['message']['subtitle'].'</span><br><span class="fs-12 font-w400 ml-4">'.$outState['validations']['logistics-blocked']['message']['description'].'</span></h4>';
        else:
            $linhaLogisticsBlocked = '';
        endif;

        if(!empty($termsServiceViolationStatus)):
            $linhaTermsServiceViolation = '<h4 class="fs-14 font-w600 text-black p-3 bg-observation-order" style="margin-left: -16px;margin-right: -16px;"><i class="fa-regular fa-clock text-black mr-2 fs-16"></i>'.$outState['validations']['terms-service-violation']['message']['title'].'  <br><span class="fs-12 font-w400 ml-4">'.$outState['validations']['terms-service-violation']['message']['subtitle'].'</span><br><span class="fs-12 font-w400 ml-4">'.$outState['validations']['terms-service-violation']['message']['description'].'</span></h4>';
        else:
            $linhaTermsServiceViolation = '';
        endif;

        if(!empty($statusAvailabilityStatus)):
            $linhaStatusAvailability = '<h4 class="fs-14 font-w600 text-black p-3 bg-observation-order"  ><i class="fa-regular fa-clock text-black mr-2 fs-16"></i>'.$outState['validations']['status-availability']['message']['title'].'  <br><span class="fs-12 font-w400 ml-4">'.$outState['validations']['status-availability']['message']['subtitle'].'</span><br><span class="fs-12 font-w400 ml-4">'.$outState['validations']['status-availability']['message']['description'].'</span></h4>';
        else:
            $linhaStatusAvailability = '';
        endif;

        if($openingHoursStatus == 'OK' || $openingHoursStatus == 'WARNING'):
            $iconOpen = 'fa-solid fa-check text-success';
        elseif($openingHoursStatus == 'CLOSED' || $openingHoursStatus == 'ERROR'):
            $iconOpen = 'fa-regular fa-circle-exclamation text-black';
        endif;

        if($isConnectedStatus == 'OK' || $isConnectedStatus == 'WARNING'):
            $iconConnected = 'fa-solid fa-check text-success';
        elseif($isConnectedStatus == 'CLOSED' || $isConnectedStatus == 'ERROR'):
            $iconConnected = 'fa-regular fa-circle-exclamation text-black';
        endif;

        $html = '
            <div class="media align-items-center">
                <i class="fa-solid fa-ban text-black fs-16 mr-3"></i>
                <h4 class="fs-14 font-w600 text-black mb-0">'.$title.'<br><span class="fs-14 font-w400">'.$subtitle.'</span></h4>
            </div>
            <hr class="">'.
            $linhaUnavailabilities.$linhaRadiusRestriction.$linhaPayoutBlocked.$linhaLogisticsBlocked.$linhaTermsServiceViolation.$linhaStatusAvailability
            .'
            <div class="media py-2">
                <i class="'.$iconConnected.' fs-16 mr-3"></i>
                <h4 class="fs-14 font-w600 text-black mb-0">'.$outState['validations']['is-connected']['message']['title'].'</h4>
            </div>
            <div class="media py-2">
                <i class="'.$iconOpen.' fs-16 mr-3"></i>
                <h4 class="fs-14 font-w600 text-black mb-0">'.$outState['validations']['opening-hours']['message']['title'].' <br>
                    <span class="fs-12 font-w400">'.$outState['validations']['opening-hours']['message']['subtitle'].'</span>
                </h4>
            </div>
            <hr>
            <h5 class="fs-12 font-w500 text-black">Esta informação pode levar até 1 minuto parar atualizar depois de ser alterada.</h5>
            '.$button;
    endif;

    $retorno['code']  = $outState['code'];

    $retorno['state']  = $state;
    $retorno['title']  = $outState['title'];
    $retorno['subtitle']  = $outState['subtitle'];
    $retorno['html']  = $html;
    echo json_encode($retorno);
    exit();
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

                    if($orderTiming == 'SCHEDULED'):
                        $orderDetailsSchedule = (array) $orderDetails['schedule'];

                        $deliveryDateTimeStart = (isset($orderDetailsSchedule['deliveryDateTimeStart'])) ? $orderDetailsSchedule['deliveryDateTimeStart'] : '' ;
                        $deliveryDateTimeEnd = (isset($orderDetailsSchedule['deliveryDateTimeEnd'])) ? $orderDetailsSchedule['deliveryDateTimeEnd'] : '' ;
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
                    
                                $sql = 'INSERT INTO ifood_items_options (id, orderId, indexId, itemId, itemIndex, optionName, externalCode, ean, quantity, unit, unitPrice, addition, price) VALUES (:id, :orderId, :indexId, :itemId, :itemIndex, :optionName, :externalCode, :ean, :quantity, :unit, :unitPrice, :addition, :price)';
                                $stmt = $conexao->prepare($sql);
                                $stmt->bindParam(':id', $optionId);
                                $stmt->bindParam(':orderId', $polOrderId);
                                $stmt->bindParam(':indexId', $optionIndex);
                                $stmt->bindParam(':itemId', $itemId);
                                $stmt->bindParam(':itemIndex', $itemIndex);
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

                    $dateDisplay = dateDisplay($preparationStartDateTime);

                    $sql = 'INSERT INTO ifood_orders (orderId, displayId, orderType, orderTiming, salesChannel, dateCreated, preparationStartDateTime, merchantId, merchantName, customerId, customerName, customerDocument, customerCountOnMerchant, customerNumber, customerLocalizer, customerLocalizerExpiration, isTest, extraInfo, originCancellation, statusTekeout, statusDelivery, onDemandAvailable, onDemandValue, mode, deliveredBy, deliveryDateTime, takeoutDateTime, tableIndoor, observations, deliveryDateTimeStart, deliveryDateTimeEnd, statusCod, dateDisplay) VALUES (:orderId, :displayId, :orderType, :orderTiming, :salesChannel, :dateCreated, :preparationStartDateTime, :merchantId, :merchantName, :customerId, :customerName, :customerDocument, :customerCountOnMerchant, :customerNumber, :customerLocalizer, :customerLocalizerExpiration, :isTest, :extraInfo, :originCancellation, :statusTekeout, :statusDelivery, :onDemandAvailable, :onDemandValue, :mode, :deliveredBy, :deliveryDateTime, :takeoutDateTime, :tableIndoor, :observations, :deliveryDateTimeStart, :deliveryDateTimeEnd, :statusCod, :dateDisplay)';
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
                    $stmt->bindParam(':dateDisplay', $dateDisplay);
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
                    ////////
                    ////////
                    //////// VERIFICA SE EVENTO JA FOI PEDIDO JA FOI CADASTRADO
                    ////////
                    //////// START >>>>
                    ////////
                    $sql = "SELECT id FROM ifood_orders WHERE orderId=:orderId && merchantId=:merchantId";
                    $stmt = $conexao->prepare($sql);
                    $stmt->bindParam(':orderId', $polOrderId);
                    $stmt->bindParam(':merchantId', $merchantId);
                    $stmt->execute();
                    $contar = $stmt->rowCount();
                    
                    if($contar == 0):
                        continue;
                    endif;
                    ////////
                    //////// >>>> END
                    ////////

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
                    $sql = 'INSERT INTO ifood_events (id, orderId, createdAt, code) VALUES (:id, :orderId, :createdAt, :code)';
                    $stmt = $conexao->prepare($sql);
                    $stmt->bindParam(':id', $polId);
                    $stmt->bindParam(':orderId', $polOrderId);
                    $stmt->bindParam(':createdAt', $polCreatedAt);
                    $stmt->bindParam(':code', $polCode);
                    $resposta = $stmt->execute();

                    if(!$resposta):
                        errorLog('error-ifood_events-101-Erro interno BD.');
                    endif;
                endif;
                //
                // START - CANCELAMENTOS
                //
                if($polCode == 'CAN'):
                    ////////
                    ////////
                    //////// VERIFICA SE EVENTO JA FOI PEDIDO JA FOI CADASTRADO
                    ////////
                    //////// START >>>>
                    ////////
                    $sql = "SELECT id FROM ifood_orders WHERE orderId=:orderId && merchantId=:merchantId";
                    $stmt = $conexao->prepare($sql);
                    $stmt->bindParam(':orderId', $polOrderId);
                    $stmt->bindParam(':merchantId', $merchantId);
                    $stmt->execute();
                    $contar = $stmt->rowCount();
                    
                    if($contar == 0):
                        continue;
                    endif;
                    ////////
                    //////// >>>> END
                    ////////

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
                    ////////
                    ////////
                    //////// VERIFICA SE EVENTO JA FOI PEDIDO JA FOI CADASTRADO
                    ////////
                    //////// START >>>>
                    ////////
                    $sql = "SELECT id FROM ifood_orders WHERE orderId=:orderId && merchantId=:merchantId";
                    $stmt = $conexao->prepare($sql);
                    $stmt->bindParam(':orderId', $polOrderId);
                    $stmt->bindParam(':merchantId', $merchantId);
                    $stmt->execute();
                    $contar = $stmt->rowCount();
                    
                    if($contar == 0):
                        continue;
                    endif;
                    ////////
                    //////// >>>> END
                    ////////

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
                    ////////
                    ////////
                    //////// VERIFICA SE EVENTO JA FOI PEDIDO JA FOI CADASTRADO
                    ////////
                    //////// START >>>>
                    ////////
                    $sql = "SELECT id FROM ifood_orders WHERE orderId=:orderId && merchantId=:merchantId";
                    $stmt = $conexao->prepare($sql);
                    $stmt->bindParam(':orderId', $polOrderId);
                    $stmt->bindParam(':merchantId', $merchantId);
                    $stmt->execute();
                    $contar = $stmt->rowCount();
                    
                    if($contar == 0):
                        continue;
                    endif;
                    ////////
                    //////// >>>> END
                    ////////

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
                    ////////
                    ////////
                    //////// VERIFICA SE EVENTO JA FOI PEDIDO JA FOI CADASTRADO
                    ////////
                    //////// START >>>>
                    ////////
                    $sql = "SELECT id FROM ifood_orders WHERE orderId=:orderId && merchantId=:merchantId";
                    $stmt = $conexao->prepare($sql);
                    $stmt->bindParam(':orderId', $polOrderId);
                    $stmt->bindParam(':merchantId', $merchantId);
                    $stmt->execute();
                    $contar = $stmt->rowCount();
                    
                    if($contar == 0):
                        continue;
                    endif;
                    ////////
                    //////// >>>> END
                    ////////

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
                    ////////
                    ////////
                    //////// VERIFICA SE EVENTO JA FOI PEDIDO JA FOI CADASTRADO
                    ////////
                    //////// START >>>>
                    ////////
                    $sql = "SELECT id FROM ifood_orders WHERE orderId=:orderId && merchantId=:merchantId";
                    $stmt = $conexao->prepare($sql);
                    $stmt->bindParam(':orderId', $polOrderId);
                    $stmt->bindParam(':merchantId', $merchantId);
                    $stmt->execute();
                    $contar = $stmt->rowCount();
                    
                    if($contar == 0):
                        continue;
                    endif;
                    ////////
                    //////// >>>> END
                    ////////

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
                    ////////
                    ////////
                    //////// VERIFICA SE EVENTO JA FOI PEDIDO JA FOI CADASTRADO
                    ////////
                    //////// START >>>>
                    ////////
                    $sql = "SELECT id FROM ifood_orders WHERE orderId=:orderId && merchantId=:merchantId";
                    $stmt = $conexao->prepare($sql);
                    $stmt->bindParam(':orderId', $polOrderId);
                    $stmt->bindParam(':merchantId', $merchantId);
                    $stmt->execute();
                    $contar = $stmt->rowCount();
                    
                    if($contar == 0):
                        continue;
                    endif;
                    ////////
                    //////// >>>> END
                    ////////

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
                    ////////
                    ////////
                    //////// VERIFICA SE EVENTO JA FOI PEDIDO JA FOI CADASTRADO
                    ////////
                    //////// START >>>>
                    ////////
                    $sql = "SELECT id FROM ifood_orders WHERE orderId=:orderId && merchantId=:merchantId";
                    $stmt = $conexao->prepare($sql);
                    $stmt->bindParam(':orderId', $polOrderId);
                    $stmt->bindParam(':merchantId', $merchantId);
                    $stmt->execute();
                    $contar = $stmt->rowCount();
                    
                    if($contar == 0):
                        continue;
                    endif;
                    ////////
                    //////// >>>> END
                    ////////

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
                    ////////
                    ////////
                    //////// VERIFICA SE EVENTO JA FOI PEDIDO JA FOI CADASTRADO
                    ////////
                    //////// START >>>>
                    ////////
                    $sql = "SELECT id FROM ifood_orders WHERE orderId=:orderId && merchantId=:merchantId";
                    $stmt = $conexao->prepare($sql);
                    $stmt->bindParam(':orderId', $polOrderId);
                    $stmt->bindParam(':merchantId', $merchantId);
                    $stmt->execute();
                    $contar = $stmt->rowCount();
                    
                    if($contar == 0):
                        continue;
                    endif;
                    ////////
                    //////// >>>> END
                    ////////

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
                    ////////
                    ////////
                    //////// VERIFICA SE EVENTO JA FOI PEDIDO JA FOI CADASTRADO
                    ////////
                    //////// START >>>>
                    ////////
                    $sql = "SELECT id FROM ifood_orders WHERE orderId=:orderId && merchantId=:merchantId";
                    $stmt = $conexao->prepare($sql);
                    $stmt->bindParam(':orderId', $polOrderId);
                    $stmt->bindParam(':merchantId', $merchantId);
                    $stmt->execute();
                    $contar = $stmt->rowCount();
                    
                    if($contar == 0):
                        continue;
                    endif;
                    ////////
                    //////// >>>> END
                    ////////

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
                    ////////
                    ////////
                    //////// VERIFICA SE EVENTO JA FOI PEDIDO JA FOI CADASTRADO
                    ////////
                    //////// START >>>>
                    ////////
                    $sql = "SELECT id FROM ifood_orders WHERE orderId=:orderId && merchantId=:merchantId";
                    $stmt = $conexao->prepare($sql);
                    $stmt->bindParam(':orderId', $polOrderId);
                    $stmt->bindParam(':merchantId', $merchantId);
                    $stmt->execute();
                    $contar = $stmt->rowCount();
                    
                    if($contar == 0):
                        continue;
                    endif;
                    ////////
                    //////// >>>> END
                    ////////

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
                    ////////
                    ////////
                    //////// VERIFICA SE EVENTO JA FOI PEDIDO JA FOI CADASTRADO
                    ////////
                    //////// START >>>>
                    ////////
                    $sql = "SELECT id FROM ifood_orders WHERE orderId=:orderId && merchantId=:merchantId";
                    $stmt = $conexao->prepare($sql);
                    $stmt->bindParam(':orderId', $polOrderId);
                    $stmt->bindParam(':merchantId', $merchantId);
                    $stmt->execute();
                    $contar = $stmt->rowCount();
                    
                    if($contar == 0):
                        continue;
                    endif;
                    ////////
                    //////// >>>> END
                    ////////

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
            $retorno['code']  = $outPolling['code'];
        else:
            errorLog('error-if_count-101-Erro interno.');
            $retorno['mensagem']  = 'Erro interno "if count".';
            $retorno['code']  = 400;
        endif;
    elseif($outPolling['code'] == 204):
        $retorno['mensagem']  = 'Polling vazio!';
        $retorno['code']  = $outPolling['code'];
    else:
        errorLog('error-polling-'.$outPolling['code'].'-'.$outPolling['mensagem']);
        $retorno['mensagem']  = 'Erro interno "polling".';
        $retorno['code']  = $outPolling['code'];
    endif;
    echo json_encode($retorno);
endif;

//$_POST['orders_list'] = true;

if(isset($_POST['orders_list']) && $_POST['orders_list'] == true) :
    require("conexao_hostgator.php");

    $retorno = array();
    
    $orderIdAtivo = (isset($_POST['orderIdAtivo'])) ? $_POST['orderIdAtivo'] : null ;
    
    $fuso = 3;
    $dateAtual = date_format(date_create(),"YmdHis");
    
    $sql = "SELECT * FROM ifood_orders WHERE dateDisplay > :dateActual ORDER BY CASE statusCod WHEN 'PLC' THEN 1 WHEN 'CFM' THEN 2 WHEN 'RTP' THEN 3 WHEN 'DSP' THEN 4 WHEN 'CON' THEN 5 WHEN 'CAN' THEN 6 ELSE 7 END";
    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':dateActual', $dateAtual);	
    $stmt->execute();
    $contar = $stmt->rowCount();
    
    if($contar != 0):
    
        $immediate = '';
        $scheduled = '';
    
        while($exibe = $stmt->fetch(PDO::FETCH_OBJ)){
            $preparationStart = date_format(date_sub(date_create($exibe->preparationStartDateTime),date_interval_create_from_date_string("$fuso hours")),"YmdHis");

            $timing = $exibe->orderTiming;
            $status = $exibe->statusCod;
            $orderId = $exibe->orderId;
            $customerName = $exibe->customerName;
            $displayId = $exibe->displayId;

            if($orderIdAtivo == $orderId):
                $active = 'active';
            else:
                $active = '';
            endif;
    
            if($timing == 'IMMEDIATE' || ($timing == 'SCHEDULED' && $preparationStart <= $dateAtual)): //APARECE EM IMEDIATE
                if($status == 'PLC'):
                    $immediate = $immediate.'
                    <div class="col-12 mb-3">
                        <div class="card shadow  mb-0 d-block">
                            <div class="card-body cPointer pl-4 mb-0 bg-danger rounded faixa-pedido '.$active.' pendente" data-orderId="'.$orderId.'">
                                <div class="media">
                                    <div class="details">
                                        <h4 class="font-gilroy-bold fs-20 mb-0 text-white">'.abreviaNomeDisplay($customerName).' <small class="fs-20 ml-2">#'.$displayId.'</small></h4>
                                    </div>
                                    <div class="media-footer status-pedido-new">
                                        <h4 class="mb-0 font-gilroy-extrabold text-terceiro fs-22 badge-new"><span class="badge badge-danger light">PENDENTE</span></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';
                elseif($status == 'CFM'):
                    $dateFinish = (isset($exibe->deliveryDateTime)) ? $exibe->deliveryDateTime : null ;
                    $dateFinish = (isset($exibe->takeoutDateTime)) ? $exibe->takeoutDateTime : $dateFinish ;
                    $dateFinish = date_format(date_sub(date_create($dateFinish),date_interval_create_from_date_string("$fuso hours")),"YmdHis");
                    $horaFinish = date_format(date_create($dateFinish), 'H:i');

                    if($active != ''):
                        $animate = '';
                    else:
                        $animate = 'animate__animated';
                    endif;
    
                    if($dateFinish < $dateAtual):
                        $immediate = $immediate.'
                        <div class="col-12 mb-3">
                            <div class="card shadow  mb-0 d-block">
                                <div class="card-body cPointer pl-4 mb-0 bg-white rounded faixa-pedido '.$active.' animate__pulse animate__infinite '.$animate.' alerta" data-orderId="'.$orderId.'">
                                    <div class="media">
                                        <div class="details">
                                            <h4 class="font-gilroy-bold fs-20 mb-1">'.abreviaNomeDisplay($customerName).' <small class="fs-20 ml-2 text-dark">#'.$displayId.'</small></h4>
                                            <span class=""><i class="fa-regular fa-clock fs-16"></i></span>
                                            <span class="font-gilroy-medium fs-16 ml-2 position-absolute">Enviar até '.$horaFinish.'</span>
                                        </div>
                                        <div class="media-footer status-pedido">
                                            <h4 class="mb-0 font-gilroy-extrabold text-terceiro fs-22 badge-atraso">
                                                <span class="badge badge-warning light">ATRASO</span></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>';
                    else:
                        $immediate = $immediate.'
                        <div class="col-12 mb-3">
                            <div class="card shadow mb-0 d-block">
                                <div class="card-body cPointer pl-4 mb-0 bg-white rounded faixa-pedido '.$active.'" data-orderId="'.$orderId.'">
                                    <div class="media align-items-center">
                                        <div class="details">
                                            <h4 class="font-gilroy-bold fs-20 mb-1">'.abreviaNomeDisplay($customerName).' <small class="fs-20 ml-2 text-dark">#'.$displayId.'</small></h4>
                                            <span class=""><i class="fa-regular fa-clock fs-16"></i></span>
                                            <span class="font-gilroy-medium fs-16 ml-2 position-absolute">Enviar até '.$horaFinish.'</span>
                                        </div>
                                        <div class="media-footer status-pedido">
                                            <h4 class="mb-0 font-gilroy-extrabold text-terceiro fs-22 badge-preparo">
                                                <span class="badge badge-primary light">PREPARO</span>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>';
                    endif;
                elseif($status == 'RTP'):
                    $orderType = $exibe->orderType;

                    if($orderType == 'DELIVERY'):
                        $desc = '<span class=""><i class="fa-solid fa-motorcycle fs-16"></i></span><span class="font-gilroy-medium fs-16 ml-2 position-absolute">Entregador a caminho</span>';
                    else:
                        $desc = '<span class=""><i class="fa-solid fa-person-carry-box fs-16"></i></span><span class="font-gilroy-medium fs-16 ml-2 position-absolute">Pronto para ser retirado</span>';
                    endif;
                    
                    $immediate = $immediate.'
                    <div class="col-12 mb-3">
                        <div class="card shadow  mb-0 d-block">
                            <div class="card-body cPointer pl-4 mb-0 bg-white rounded faixa-pedido '.$active.'" data-orderId="'.$orderId.'">
                                <div class="media">
                                    <div class="details">
                                        <h4 class="font-gilroy-bold fs-20 mb-1">'.abreviaNomeDisplay($customerName).' <small class="fs-20 ml-2 text-dark">#'.$displayId.'</small></h4>
                                        '.$desc.'
                                    </div>
                                    <div class="media-footer status-pedido">
                                        <h4
                                            class="mb-0 font-gilroy-extrabold text-terceiro fs-22 badge-entrega">
                                            <span class="badge badge-success light">PRONTO</span>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';
                elseif($status == 'DSP'):
                    $sql2 = "SELECT * FROM ifood_events WHERE orderId=:orderId AND code=:code";
                    $stmt2 = $conexao->prepare($sql2);
                    $stmt2->bindParam(':orderId', $orderId);	
                    $stmt2->bindParam(':code', $status);	
                    $stmt2->execute();
                    $conta2 = $stmt2->rowCount();
    
                    if($conta2 != 0):
                        $exibe2 = $stmt2->fetch(PDO::FETCH_OBJ);
                        $hourDelivered = $exibe2->createdAt;
                        $hourDelivered = date_format(date_sub(date_create($hourDelivered),date_interval_create_from_date_string("$fuso hours")),"YmdHis");
    
                        $firstDate  = new DateTime($hourDelivered);
                        $secondDate = new DateTime($dateAtual);
                        $dateInterval = $firstDate->diff($secondDate);
                        $diff = $dateInterval->i;
                    endif;

                    $minutes = (isset($diff)) ? $diff : "-" ;

                    $immediate = $immediate.'
                    <div class="col-12 mb-3">
                        <div class="card shadow  mb-0 d-block">
                            <div class="card-body cPointer pl-4 mb-0 bg-white rounded faixa-pedido '.$active.'" data-orderId="'.$orderId.'">
                                <div class="media">
                                    <div class="details">
                                        <h4 class="font-gilroy-bold fs-20 mb-1">'.abreviaNomeDisplay($customerName).' <small class="fs-20 ml-2 text-dark">#'.$displayId.'</small></h4>
                                        <span class=""><i class="fa-solid fa-motorcycle fs-16"></i></span>
                                        <span class="font-gilroy-medium fs-16 ml-2 position-absolute">Enviado á '.$minutes.' minutos</span>
                                    </div>
                                    <div class="media-footer status-pedido">
                                        <h4
                                            class="mb-0 font-gilroy-extrabold text-terceiro fs-22 badge-entrega">
                                            <span class="badge badge-success light">ENTREGA</span>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';
                elseif($status == 'CON'):
                    $sql2 = "SELECT * FROM ifood_events WHERE orderId=:orderId AND code=:code";
                    $stmt2 = $conexao->prepare($sql2);
                    $stmt2->bindParam(':orderId', $orderId);	
                    $stmt2->bindParam(':code', $status);	
                    $stmt2->execute();
                    $conta2 = $stmt2->rowCount();
    
                    if($conta2 != 0):
                        $exibe2 = $stmt2->fetch(PDO::FETCH_OBJ);
                        $dateFinish = $exibe2->createdAt;
                        $dateFinish = date_format(date_sub(date_create($dateFinish),date_interval_create_from_date_string("$fuso hours")),"YmdHis");
                        $horaFinish = date_format(date_create($dateFinish), 'H:i');
                    endif;

                    $horaFinish = (isset($horaFinish)) ? $horaFinish : "-" ;

                    $immediate = $immediate.'
                    <div class="col-12 mb-3">
                        <div class="card shadow  mb-0 d-block">
                            <div class="card-body cPointer pl-4 mb-0 bg-white rounded faixa-pedido '.$active.'" data-orderId="'.$orderId.'">
                                <div class="media">
                                    <div class="details">
                                        <h4 class="font-gilroy-bold fs-20 mb-1">'.abreviaNomeDisplay($customerName).' <small class="fs-20 ml-2 text-dark">#'.$displayId.'</small></h4>
                                        <span class=""><i class="fa-regular fa-circle-check fs-16"></i></span>
                                        <span class="font-gilroy-medium fs-16 ml-2 position-absolute">Concluido ás '.$horaFinish.'</span>
                                    </div>
                                    <div class="media-footer status-pedido">
                                        <h4 class="mb-0 font-gilroy-extrabold text-terceiro fs-22"><span class="badge badge-dark light">CONCLUIDO</span></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';
                elseif($status == 'CAN'):                    
                    $sql2 = "SELECT * FROM ifood_events WHERE orderId=:orderId AND code=:code";
                    $stmt2 = $conexao->prepare($sql2);
                    $stmt2->bindParam(':orderId', $orderId);	
                    $stmt2->bindParam(':code', $status);	
                    $stmt2->execute();
                    $conta2 = $stmt2->rowCount();
    
                    if($conta2 != 0):
                        $exibe2 = $stmt2->fetch(PDO::FETCH_OBJ);
                        $dateFinish = $exibe2->createdAt;
                        $dateFinish = date_format(date_sub(date_create($dateFinish),date_interval_create_from_date_string("$fuso hours")),"YmdHis");
                        $horaFinish = date_format(date_create($dateFinish), 'H:i');
                    endif;

                    $horaFinish = (isset($horaFinish)) ? $horaFinish : "-" ;

                    $immediate = $immediate.'
                    <div class="col-12 mb-3">
                        <div class="card shadow  mb-0 d-block">
                            <div class="card-body cPointer pl-4 mb-0 bg-white rounded faixa-pedido '.$active.'" data-orderId="'.$orderId.'">
                                <div class="media">
                                    <div class="details">
                                        <h4 class="font-gilroy-bold fs-20 mb-1">'.abreviaNomeDisplay($customerName).' <small class="fs-20 ml-2 text-dark">#'.$displayId.'</small></h4>
                                        <span class=""><i class="fa-solid fa-ban fs-16"></i></span>
                                        <span class="font-gilroy-medium fs-16 ml-2 position-absolute">Cancelado ás '.$horaFinish.'</span>
                                    </div>
                                    <div class="media-footer status-pedido">
                                        <h4 class="mb-0 font-gilroy-extrabold text-terceiro fs-22"><span class="badge badge-danger light">CANCELADO</span></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';
                endif;
            else: //APARECE EM AGENDADOS
                $scheduled = $scheduled.'Agendado!';
            endif;
        }
        $retorno['immediate'] = $immediate;
        $retorno['scheduled'] = $scheduled;
        $retorno['list'] = true;
        echo json_encode($retorno);
    else: // SEM PEDIDOS
        $retorno['list'] = false;
        echo json_encode($retorno);
    endif;
endif;

if(isset($_POST['orders_details_ifood']) && $_POST['orders_details_ifood'] == true) :
    require("conexao_hostgator.php");

    function numeroParaReal($n){
        $valor = number_format($n,2,",",".");
        $valor = "R$ ".$valor;
        return($valor);
    };

    $retorno = array();
    
    $orderId = (isset($_POST['orderId'])) ? $_POST['orderId'] : '' ;
    $type = (isset($_POST['type'])) ? $_POST['type'] : '' ;

    if(empty($orderId) || empty($type)):
        errorLog('error-orders_details_empty');
        $retorno['error']  = true;
        echo json_encode($retorno);
        exit();
    endif;

    $fuso = 3;
    $dateAtual = date_format(date_create(),"YmdHis");

    $sql = "SELECT * FROM ifood_orders WHERE orderId = :orderId";
    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':orderId', $orderId);	
    $stmt->execute();
    $contar = $stmt->rowCount();
    
    if($contar != 0):
        $exibe = $stmt->fetch(PDO::FETCH_OBJ);
        
        $orderType = $exibe->orderType;
        $statusCod = $exibe->statusCod;
        $orderTiming = $exibe->orderTiming;

        $dateCreated = date_format(date_sub(date_create($exibe->dateCreated),date_interval_create_from_date_string("$fuso hours")),"YmdHis");
        $hourCreated = date_format(date_create($dateCreated), 'H:i');

        if($type == 'IMMEDIATE'):
            $html_head = '
            <div class="col-xxl-12 col-11">
                <h4 class="fs-26 text-black mb-3 font-gilroy-semibold">Pedido #'.$exibe->displayId.'<span class="fs-18"><i class="fa-regular fa-clock fs-16 ml-3 mr-1"></i> Feito às '.$hourCreated.'h</span></h4>
            </div>';

            if($orderType == 'DELIVERY'):
                $sql2 = "SELECT * FROM ifood_delivery_anddress WHERE orderId = :orderId";
                $stmt2 = $conexao->prepare($sql2);
                $stmt2->bindParam(':orderId', $orderId);	
                $stmt2->execute();
                $contar2 = $stmt2->rowCount();
    
                if($contar2 != 0):
                    $exibe2 = $stmt2->fetch(PDO::FETCH_OBJ);
                    
                    $address = '
                    <div class="col-xl-7">
                        <div class="media align-items-center">
                            <i class="fa-light fa-location-dot fs-30 text-black mr-2"></i>
                            <span class="text-black font-w500">'.$exibe2->formattedAddress.'</span>
                        </div>
                    </div>';
                endif;

                

                
    
    
    
            elseif($orderType == 'TAKEOUT'):

            elseif($orderType == 'INDOOR'):
                
            endif;

            $address = (isset($address)) ? $address : '' ;

            $col_info_1 = '
            <div class="col-xl-12">
                <div class="card border border-light shadow-sm">
                    <div class="card-body py-3">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row align-items-center">
                                    <div class="col-xl-2 text-center">
                                        <img class="order-details-origin-ifood w-75" src="images/logo_ifood_2.png" alt="ifood">
                                    </div>
                                    <div class="col-xl-10">
                                        <div class="row align-items-center justify-content-center">
                                            '.$address.'
                                            <div class="col-xl-5">
                                                <div class="media align-items-center">
                                                    <i class="fa-light fa-phone fs-30 text-black mr-2"></i>
                                                    <span class="text-black font-w500">'.$exibe->customerNumber.' <span class="text-secondary">: '.$exibe->customerLocalizer.'</span></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
            
            ///
            ///
            /// ITEMS
            ///
            ///

            $items = '';
            
            $sql3 = "SELECT * FROM ifood_order_items WHERE orderId = :orderId";
            $stmt3 = $conexao->prepare($sql3);
            $stmt3->bindParam(':orderId', $orderId);	
            $stmt3->execute();
            $contar3 = $stmt3->rowCount();

            if($contar3 != 0):

                while($exibe3 = $stmt3->fetch(PDO::FETCH_OBJ)){

                    $itemId = $exibe3->id;
                    $indexId = $exibe3->indexId;

                    $options = ''; // id, orderId, indexId, itemId, optionName, externalCode, ean, quantity, unit, unitPrice, addition, price

                    $sql4 = "SELECT * FROM ifood_items_options WHERE itemId = :itemId AND itemIndex = :itemIndex AND orderId = :orderId";
                    $stmt4 = $conexao->prepare($sql4);
                    $stmt4->bindParam(':itemId', $itemId);
                    $stmt4->bindParam(':itemIndex', $indexId);
                    $stmt4->bindParam(':orderId', $orderId);
                    $stmt4->execute();
                    $contar4 = $stmt4->rowCount();

                    if($contar4 != 0):
                        $count = 1;
                        while($exibe4 = $stmt4->fetch(PDO::FETCH_OBJ)){
                            if($contar4 == 1 || $count == 1):
                                $division = '';
                            else:
                                $division = '
                                <div class="media px-2">
                                    <div class="option-item-order media-body col px-0 align-self-center align-items-center">
                                        <hr>
                                    </div>
                                </div>';
                            endif;

                            $options = $options.$division.'
                            <div class="media px-2">
                                <div class="option-item-order media-body col-sm-6 col-xxl-5 px-0 align-self-center align-items-center">
                                    <h5 class="mt-0 mb-0 text-quinta fs-16">'.$exibe4->optionName.'</h5>
                                </div>
                                <div class="media-footer ml-auto col-sm-2 mt-sm-0 mt-3 px-0 d-flex align-self-center align-items-center justify-content-end">
                                    <h5 class="mb-0 font-w600 text-quinta fs-16">'.$exibe4->quantity.'x</h5>
                                </div>
                                <div class="media-footer ml-auto col-sm-2 mt-sm-0 mt-3 px-0 d-flex align-self-center align-items-center justify-content-end">
                                    <h3 class="mb-0 font-w500 text-quinta fs-16">'.numeroParaReal($exibe4->price).'</h3>
                                </div>
                            </div>';
                            $count++;
                        }
                    endif;
                    
                    // orderId, indexId, id, itemName, imageUrl, externalCode, ean, quantity, unit, unitPrice, addition, price, optionsPrice, totalPrice, observations

                    if($options != ''):
                        $pObs = 'pt-4';
                    else:
                        $pObs = '';
                    endif;

                    $observations = '';

                    if($exibe3->observations != ''):
                        $observations = '
                        <div class="media px-2 pb-2 '.$pObs.'">
                            <div class="observation-item-order media-body col-12 pr-0 align-self-center align-items-center bg-observation-order">
                                <div class="media py-3 pl-2 pr-3">
                                    <h5 class="mt-0 mb-0 text-black fs-16 mr-3"><i class="fa-solid fa-pen"></i></i></h5>
                                    <h5 class="mt-0 mb-0 text-black fs-16"><span>'.$exibe3->observations.'</span></h5>
                                </div>
                            </div>
                        </div>';
                    endif;

                    $items = $items.'
                    <div class="col-12">
                        <div class="media px-2 py-1 align-items-center">
                            <img class="img-fluid rounded mr-3" width="85"
                                src="./upload/cardapio/bb1ac07cff6d79e4b191911a43127cc6.png"
                                alt="">
                            <div class="media-body col-sm-6 col-xxl-5 px-0 align-self-center align-items-center">
                                <h5 class="mt-0 mb-0 text-black">'.$exibe3->itemName.'</h5>
                            </div>
                            <div class="media-footer ml-auto col-sm-2 mt-sm-0 mt-3 px-0 d-flex align-self-center align-items-center justify-content-end">                                                           
                                <h3 class="mb-0 font-w600 text-black fs-22">'.$exibe3->quantity.'x</h3>
                            </div>
                            <div class="media-footer ml-auto col-sm-2 mt-sm-0 mt-3 px-0 d-flex align-self-center align-items-center justify-content-end">
                                <h3 class="mb-0 font-w600 text-black fs-22">'.numeroParaReal($exibe3->totalPrice).'</h3>
                            </div>
                        </div>
                        '.$options.$observations.'
                    </div>
                    <div class="col-12 px-0">
                        <hr class="hr-full-16">
                    </div>';
                }
                $purchase = $items;
            endif;
            
            ///
            ///
            /// PAYMENTS
            ///
            ///

            
            $sql5 = "SELECT * FROM ifood_total_payments WHERE orderId = :orderId";
            $stmt5 = $conexao->prepare($sql5);
            $stmt5->bindParam(':orderId', $orderId);	
            $stmt5->execute();
            $contar5 = $stmt5->rowCount();
            
            if($contar5 != 0):
                $exibe5 = $stmt5->fetch(PDO::FETCH_OBJ);

                if($orderType == 'DELIVERY'):
                    $delivery = '
                    <div class="col-12">
                        <div class="media px-2 py-1 align-items-center">
                            <h5 class="mb-0 font-w600 fs-16 text-black"><i class="fa-solid fa-motorcycle fs-15 mr-2"></i>Taxa de entrega</h5>
                            <div class="media-footer ml-auto col-sm-2 mt-sm-0 mt-3 px-0 d-flex align-self-center align-items-center justify-content-end">
                                <h5 class="mb-0 font-w600 fs-18 text-black">'.numeroParaReal($exibe5->deliveryFee).'</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 px-0">
                        <hr class="hr-full-16">
                    </div>';
                endif;

                $additionalFees = $exibe5->additionalFees;

                if($additionalFees > 0):
                    $additionalFee = '
                    <div class="col-12">
                        <div class="media px-2 py-1 align-items-center">
                            <h5 class="mb-0 font-w600 fs-16 text-black"><i class="fa-regular fa-circle-exclamation fs-18 mr-2"></i>Taxa de serviço</h5>
                            <div class="media-footer ml-auto col-sm-2 mt-sm-0 mt-3 px-0 d-flex align-self-center align-items-center justify-content-end">
                                <h5 class="mb-0 font-w600 fs-18 text-black">'.numeroParaReal($additionalFees).'</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 px-0">
                        <hr class="hr-full-16">
                    </div>';
                endif;

                $total = '
                <div class="col-12">
                    <div class="media px-2 py-1 align-items-center">
                        <h5 class="mb-0 font-w600 fs-16 text-black ml-4 pl-1">Valor total do pedido</h5>
                        <div class="media-footer ml-auto col-sm-2 mt-sm-0 mt-3 px-0 d-flex align-self-center align-items-center justify-content-end">
                            <h5 class="mb-0 font-w600 fs-18 text-black">'.numeroParaReal($exibe5->subTotal + $exibe5->deliveryFee + $exibe5->additionalFees).'</h5>
                        </div>
                    </div>
                </div>';

                $delivery = (isset($delivery)) ? $delivery : '' ;
                $additionalFee = (isset($additionalFee)) ? $additionalFee : '' ;

                $values = $delivery.$additionalFee.$total;
            else:
                //erro
            endif;

            $sql6 = "SELECT * FROM ifood_benefits WHERE orderId = :orderId";
            $stmt6 = $conexao->prepare($sql6);
            $stmt6->bindParam(':orderId', $orderId);	
            $stmt6->execute();
            $contar6 = $stmt6->rowCount();
            
            if($contar6 != 0):
                if($contar6 > 1):
                    $division = '
                    <div class="col-12 px-0">
                        <hr class="hr-full-16 hr-price">
                    </div>';
                else:
                    $division = '';
                endif;

                while($exibe6 = $stmt6->fetch(PDO::FETCH_OBJ)){
                    if($exibe6->nameBenef == 'ifood'):
                        $benefIfood = '
                        <div class="col-12">
                            <div class="media px-2 py-1 align-items-center">
                                <h5 class="mb-0 font-w400 fs-16 text-black"><i class="fa-regular fa-tag fs-18 mr-3"></i>Incentivos oferecido pelo ifood</h5>
                                <div class="media-footer ml-auto col-sm-2 mt-sm-0 mt-3 px-0 d-flex align-self-center align-items-center justify-content-end">
                                    <h5 class="mb-0 font-w600 fs-18 text-black">-'.$exibe6->valueBenef.'</h5>
                                </div>
                            </div>
                        </div>';
                    elseif($exibe6->nameBenef == 'loja'):
                        $benefLoja = '
                        <div class="col-12">
                            <div class="media px-2 py-1 align-items-center">
                                <h5 class="mb-0 font-w400 fs-16 text-black"><i class="fa-regular fa-tag fs-18 mr-3"></i>Incentivos oferecido pela sua loja</h5>
                                <div class="media-footer ml-auto col-sm-2 mt-sm-0 mt-3 px-0 d-flex align-self-center align-items-center justify-content-end">
                                    <h5 class="mb-0 font-w600 fs-18 text-black">-'.$exibe6->valueBenef.'</h5>
                                </div>
                            </div>
                        </div>';
                    endif;
                };

                $benefIfood = (isset($benefIfood)) ? $benefIfood : '' ;
                $benefLoja = (isset($benefLoja)) ? $benefLoja : '' ;

                $benefits = $benefIfood.$division.$benefLoja;
            endif;

            $benefits = (isset($benefits)) ? $benefits : '' ;

            if($benefits != ''):
                $values = $values.'
                <div class="col-12 px-0 up-hr">
                    <hr class="hr-full-16 hr-price">
                </div>';
            endif;










            $alert = '';






            $col_left = '
            <div class="col-xxl-12 col-xl-8">
                <div class="row">
                    '.$col_info_1.'
                    <div class="col-xl-12">
                        <div class="card border border-light shadow-sm">
                            '.$alert.'
                            <div class="card-body py-3">
                                <div class="row">
                                    '.$purchase.'
                                    <div class="col-12 px-0 up-hr">
                                        <hr class="hr-full-16 hr-price">
                                    </div>
                                    '.$values.$benefits.'
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';

            $col_right = '
            <div class="customer-xxl col-3">
                <div class="row row-customer">
                '.$col_right_01.'
                </div>
            </div>';

            $retorno['error']  = false;
            $retorno['details'] = $html_head.$col_left.$col_right;


        elseif($type == 'SCHEDULED'):


        endif;

        echo json_encode($retorno);
    else:
        errorLog('error-orders_details_contar');
        $retorno['error']  = true;
        echo json_encode($retorno);
        exit();
    endif;
endif;