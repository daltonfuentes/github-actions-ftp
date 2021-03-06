<?php
ob_start();
session_start();

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Sweet Confetty - Painel de Pedidos</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">

    <?php include("include/css.php"); ?>

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&family=Roboto:wght@100;300;400;500;700;900&display=swap"
        rel="stylesheet">

    <style>
        .deznav {
            background-color: #22222e;
        }

        .nav-header {
            background-color: #22222e;
        }
    </style>
</head>

<body class="">
    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->

    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper" class="show menu-toggle">

        <!--**********************************
        Nav header start
        ***********************************-->
        <div class="nav-header">
            <a href="home" class="brand-logo">
                <img class="logo-abbr" src="./images/logo.png" alt="">
                <img class="logo-compact" src="./images/logo-full.png" alt="">
                <img class="brand-title" src="./images/logo-full.png" alt="">
            </a>

            <div class="nav-control d-none">
                <div class="hamburger is-active">
                    <span class="line"></span><span class="line"></span><span class="line"></span>
                </div>
            </div>
        </div>
        <!--**********************************
            Nav header end
        ***********************************-->

        <?php include("include/sidebar_painel.php"); ?>

        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body p-0">
            <div class="container-fluid py-0 px-3 bg-dark-panel">
                <div class="row">
                    <div class="pl-4 pt-0 bg-body-painel rounded-left-lg pb-5 vh-100" style="width: 409px;">
                        <div class="row mb-4 mt-5 pr-3 justify-content-center" id="row-filter" style="width: 400px;">
                            <div class="btn-group btn-group-type btn-block px-3">
                                <button class="btn rounded-left btn-custom-2 fs-14 ativo border-2" data-type="immediate" type="button">AGORA<span class="badge badge-danger ml-2 d-none">4</span></button>
                                <button class="btn rounded-right btn-outline-custom-2 fs-14 border-2" data-type="scheduled" type="button">AGENDADO<span class="badge badge-danger ml-2 d-none">4</span></button>
                            </div>
                        </div>
                        <div class="row pr-2 pt-2" id="row-list-orders-immediate" style="width: 400px;">
                            
                        </div>
                        <div class="row pr-2 pt-1 d-none" id="row-list-orders-scheduled" style="width: 400px;">
                            <div class="col-12">
                                <h5 class="ml-2"><i class="fa-solid fa-calendar-days mr-2"></i>Hoje</h5>
                            </div>
                            <div class="col-12 mb-3">
                                <div class="card shadow mb-0 d-block">
                                    <div class="card-body cPointer pl-4 mb-0 bg-white rounded faixa-pedido">
                                        <div class="media">
                                            <div class="details">
                                                <h4 class="font-gilroy-bold fs-20 mb-1">Antonio M. <small
                                                        class="fs-20 ml-2 text-dark">#4020</small></h4>
                                                <span class=""><i class="fa-regular fa-clock fs-16"></i></span>
                                                <span class="font-gilroy-medium fs-16 ml-2 position-absolute">Entregar entre 11:00 - 11:30</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <h5 class="ml-2"><i class="fa-solid fa-calendar-days mr-2"></i>Amanh??</h5>
                            </div>
                            <div class="col-12 mb-3">
                                <div class="card shadow mb-0 d-block">
                                    <div class="card-body cPointer pl-4 mb-0 bg-white rounded faixa-pedido">
                                        <div class="media">
                                            <div class="details">
                                                <h4 class="font-gilroy-bold fs-20 mb-1">Antonio M. <small
                                                        class="fs-20 ml-2 text-dark">#4120</small></h4>
                                                <span class=""><i class="fa-regular fa-clock fs-16"></i></span>
                                                <span class="font-gilroy-medium fs-16 ml-2 position-absolute">Entregar entre 15:00 - 15:30</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row pr-2 pt-1" style="width: 400px; position: absolute; bottom: 15px;">
                            <div class="col-12">
                                <hr>
                            </div>
                            <div class="col-12 text-center">
                                <button type="button" class="btn btn-custom-2 btn-sm"><span class="fs-14">Solicitar Entrega F??cil</span></button>              
                            </div>
                        </div>
                    </div>
                    <div class="shadow-sm bg-order-details-02 pt-5 pb-4 px-5 vh-100 overflow-auto" style="width: calc(100% - 409px);">
                        <div id="order_details" class="row justify-content-center">
                            <div class="col-xxl-12 col-11 mb-4">
                                <h4 class="fs-26 text-black mb-0 font-gilroy-semibold">Pedido #5231<span class="fs-18"><i class="fa-regular fa-clock fs-16 ml-3 mr-1"></i> Feito ??s 14:17h</span></h4>
                            </div>
                            <div class="customer-xl col-12">
                                


                            </div>
                            <div class="col-xxl-12 col-xl-8">
                                <div class="row">
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
                                                                    <div class="col-12">
                                                                        <div class="media align-items-center">
                                                                            <i class="fa-light fa-location-dot fs-30 text-black mr-3"></i>
                                                                            <span class="text-black font-w500">Rua dos Jasmins, 1237 - Jardim das Flores - Foz do Igua??u ??? CEP 858551<br>Apartamento 302 ??? Chamar por Itamara</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-12">
                                        <div class="card">
                                            <div class="card-body rounded px-5" style="background:#3f4953;">
                                                <div class="row mx-0 align-items-center">
                                                    <div class="media align-items-center col px-0 mb-3 mb-md-0">
                                                        <img class="mr-3 img-fluid rounded-circle avatar_delivery_ifood" src="https://s3.amazonaws.com/nv-logistics-data.ifood.com.br/attributes/IMAGE/WORKER/entity-uuid-44fe0e7a-de99-4aa3-aedd-8d0c8debda93/attribute-key-WORKER_PHOTO/sampledriverslicense.jpg?X-Amz-Security-Token=IQoJb3JpZ2luX2VjEKf%2F%2F%2F%2F%2F%2F%2F%2F%2F%2FwEaCXVzLWVhc3QtMSJGMEQCIFyzBwNQhRcurADhSk1dz8QQSPIqjmoApX6YaR9wJ1e6AiAIc%2FoeMjvpm4bC%2BI223UsDVBVcRD5DAIrayUDHBBWeRCrbBAiQ%2F%2F%2F%2F%2F%2F%2F%2F%2F%2F8BEAAaDDM3ODQ5NzAyOTcwOSIMSHxudYDDKemJn5qOKq8E%2Fo42YzkUqLg6J18kkqOEpEMGjpUeU%2B1LVa0DOb5J8yNbZKfhgCqZnIB%2FSZonFFu%2B6t5mK%2FzpgkJr854NIuI%2FVvXYeILGoso%2BO1QUOQgbr2VPwlWu9yYx%2B6zXCR%2BXC3E6iORn35xJitCL9TS9A2MYc6KcRpCAs5%2FiAfD3qPSWZrxrcnf%2B0E0BfdLBogQOeSagM18exfguW8eIkeocJT5Uh1AJFmi%2BTlSLuy5jZ9T%2FMr0O3yMAnzDUg3xGauTfOr%2BDUms18Ml80dNt4pHPegiExoX7VRdOyx%2FTGv7b50t%2FwdWSDXoeiVnYzGrtFeqwmqZiyIoP3tm6YvHKx5zWC4Khn%2F0HOebZ8s6QX3uBr3nKRjnevV4rLuNwqFS3aH4Ozwk6dva79lQlxSrJ3jGmx%2B4YUw3MUn1iS0ku649a2hrvNKZUDbTY446YwrBmSH1HFuTUGin%2BwOgT3t9Wngo9sztS5VuDFUVVQyq4NkBLRp1%2BanZt6OP%2BGuuwPVSz8mY1TbsJV%2BR79eE9HyQKzfUwD9kyaAgsj2OkL2nOvBstlV6xFWgUvmUbSM61JDUGGgWi11myOKK%2FQYFcf7YkHkjH6RIdz0VAnzDStMwfuPXZR4%2Fl0ICWUqBOaU1cbME6T5QQtR3riSUzcE95SuBxlu6z%2BeCX0uDNKArx7HTJrcwJFQ1qpg3LqrvjQd3XV5f4IYrZ%2FGOr4IQ1dpft2zKCqHiKsBs%2FswW%2ByAgtAma3Y3Q6U4yxCjCrgLmUBjqqAVcc1IpJmFSOiTkU55%2B0LNCY672l%2Bdvk1DY2WCs4KQLm%2FQEPnRNZg935bpPpuejwjq%2BL9ngLZUr2m4AE8%2B54%2Bya%2FyLmEYV%2FZydtIGCAQ869Y4NE4JXKJ3lMEjBBHu4eAsotLK4yxKVuwSq4KatclbM2CXfQp5NvY3CbKDcxb%2FydTzSCsRpGAValEQAkz6FPdVOFRfsvvTWN7Xj42%2FlqhX6s86Z05P%2FFVSRe%2B&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Date=20220525T161200Z&X-Amz-SignedHeaders=host&X-Amz-Expires=86400&X-Amz-Credential=ASIAVQIBOKZGYLRTUQHG%2F20220525%2Fus-east-1%2Fs3%2Faws4_request&X-Amz-Signature=92e4ca27ac1e9a74a66bc3f42f7e9d92ce305f3babecbacef1535af4b2d1a39e" alt="DexignZone">
                                                        <div class="media-body">
                                                            <h3 class="fs-14 font-w600 mb-0 text-white">Entregador</h3>
                                                            <h3 class="fs-20 font-w400 mb-0 text-white">Rainold Hawkins</h3>
                                                            <small class="text-white">Moto</small>
                                                        </div>
                                                        <div class="media-footer rounded bg-body-painel text-right d-inline-block py-2 px-4">
                                                            <h4 class="fs-16 font-w600 mb-0">
                                                                <span class="align-middle">
                                                                    <i class="fa-light fa-phone fs-30 text-black mr-2"></i>
                                                                </span>
                                                                <span>+45 99900-1122</span>
                                                            </h4>
                                                        </div>
                                                    </div> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    

                                    <div class="col-xl-12">
                                        <div class="card border border-light shadow-sm">
                                            <div class="card-body rounded-top faixa-aviso-order-details preparo py-3 d-none">
                                                <h4 class="fs-16 font-w600 mb-0">Em preparo <span class="fs-14 font-w400">h?? 25 minutos</span></h4>
                                            </div>
                                            <div class="card-body  faixa-aviso-order-details atraso py-3 d-none">
                                                <h4 class="fs-16 font-w600 mb-0">Atraso h?? 6 minuto</h4>
                                                <h4 class="fs-14 font-w400 mb-0">N??o esque??a de despachar este pedido, j?? est?? em preparo h?? mais de 40 min.</h4>
                                            </div>
                                            <div class="card-body rounded-top faixa-aviso-order-details pendente py-3 d-none">
                                                <h4 class="fs-16 font-w600 mb-0">Pendente</h4>
                                                <h4 class="fs-14 font-w400 mb-0">5 minutos para aceitar</h4>
                                            </div>
                                            <div class="card-body rounded-top faixa-aviso-order-details entrega py-3 d-none">
                                                <h4 class="fs-16 font-w600 mb-0">Saiu para entrega</h4>
                                                <h4 class="fs-14 font-w400 mb-0">h?? 3 minutos</h4>
                                            </div>

                                            <div class="card-body rounded-top faixa-aviso-order-details entrega py-3 d-none">
                                                <div class="media">
                                                    <div class="media-body">
                                                        <h4 class="fs-16 font-w600 mb-0">Entregador a caminho</h4>
                                                        <h4 class="fs-14 font-w400 mb-0">Fernando Rocha chega ao restaurante em 3min</h4>
                                                    </div>
                                                    <div class="media-footer text-right">
                                                        <h4 class="fs-14 font-w600 mb-0">Fernando Rocha</h4>
                                                        <h4 class="fs-14 font-w400 mb-0">+45 99900-1122<span class="font-w600 px-2">&bull;</i></span>Moto</h4>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card-body rounded-top faixa-aviso-order-details entrega py-3">
                                                <div class="media">
                                                    <div class="media-body">
                                                        <h4 class="fs-16 font-w600 mb-0">Entregador a caminho</h4>
                                                        <h4 class="fs-14 font-w400 mb-0">Chega ao restaurante em 3 minutos</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="card-body rounded-top faixa-aviso-order-details concluido py-3 d-none">
                                                <h4 class="fs-16 font-w600 mb-0">Conclu??do <span class="fs-14 font-w400">h?? 3 horas</span></h4>
                                            </div>
                                            <div class="card-body rounded-top faixa-aviso-order-details cancelado py-3 d-none">
                                                <h4 class="fs-16 font-w600 mb-0">Pedido cancelado</h4>
                                            </div>
                                            <div class="card-body rounded-top faixa-aviso-order-details cancelado py-3 d-none">
                                                <h4 class="fs-16 font-w600 mb-0">Pedido cancelado pelo restaurante <span class="fs-14 font-w400">h?? 1 minutos</span></h4>
                                                <h4 class="fs-14 font-w400 mb-0">Motivo: A loja est?? passando por dificuldades internas</h4>
                                            </div>
                                            <div class="card-body py-3">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="media px-2 py-1 align-items-center">
                                                            <img class="img-fluid rounded mr-3" width="85"
                                                                src="./upload/cardapio/bb1ac07cff6d79e4b191911a43127cc6.png"
                                                                alt="">
                                                            <div class="media-body col-sm-6 col-xxl-5 px-0 align-self-center align-items-center">
                                                                <h5 class="mt-0 mb-0 text-black">Duplo brigadeiro</h5>
                                                            </div>
                                                            <div class="media-footer ml-auto col-sm-2 mt-sm-0 mt-3 px-0 d-flex align-self-center align-items-center justify-content-end">                                                           
                                                                <h3 class="mb-0 font-w600 text-black fs-22">1x</h3>
                                                            </div>
                                                            <div class="media-footer ml-auto col-sm-2 mt-sm-0 mt-3 px-0 d-flex align-self-center align-items-center justify-content-end">
                                                                <h3 class="mb-0 font-w600 text-black fs-22">R$ 34,00</h3>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 px-0">
                                                        <hr class="hr-full-16">
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="media px-2 py-1 align-items-center">
                                                            <img class="img-fluid rounded mr-3" width="85"
                                                                src="./upload/cardapio/no-image.png"
                                                                alt="">
                                                            <div class="media-body col-sm-6 col-xxl-5 px-0 align-self-center align-items-center">
                                                                <h5 class="mt-0 mb-0 text-black">Morango com suspiro</h5>
                                                            </div>
                                                            <div class="media-footer ml-auto col-sm-2 mt-sm-0 mt-3 px-0 d-flex align-self-center align-items-center justify-content-end">
                                                                <h3 class="mb-0 font-w600 text-black fs-22">1x</h3>
                                                            </div>
                                                            <div class="media-footer ml-auto col-sm-2 mt-sm-0 mt-3 px-0 d-flex align-self-center align-items-center justify-content-end">
                                                                <h3 class="mb-0 font-w600 text-black fs-22">R$ 17,00</h3>
                                                            </div>
                                                        </div>
                                                        <div class="media px-2">
                                                            <div class="option-item-order media-body col-sm-6 col-xxl-5 px-0 align-self-center align-items-center">
                                                                <h5 class="mt-0 mb-0 text-quinta fs-16">(P) - 210 ml</h5>
                                                            </div>
                                                            <div class="media-footer ml-auto col-sm-2 mt-sm-0 mt-3 px-0 d-flex align-self-center align-items-center justify-content-end">
                                                                <h5 class="mb-0 font-w600 text-quinta fs-16">1x</h5>
                                                            </div>
                                                            <div class="media-footer ml-auto col-sm-2 mt-sm-0 mt-3 px-0 d-flex align-self-center align-items-center justify-content-end">
                                                                <h3 class="mb-0 font-w500 text-quinta fs-16">R$ 15,00</h3>
                                                            </div>
                                                        </div>
                                                        <div class="media px-2">
                                                            <div class="option-item-order media-body col px-0 align-self-center align-items-center">
                                                                <hr>
                                                            </div>
                                                        </div>
                                                        <div class="media px-2">
                                                            <div class="option-item-order media-body col-sm-6 col-xxl-5 px-0 align-self-center align-items-center">
                                                                <h5 class="mt-0 mb-0 text-quinta fs-16">(P) - 210 ml</h5>
                                                            </div>
                                                            <div class="media-footer ml-auto col-sm-2 mt-sm-0 mt-3 px-0 d-flex align-self-center align-items-center justify-content-end">
                                                                <h5 class="mb-0 font-w600 text-quinta fs-16">1x</h5>
                                                            </div>
                                                            <div class="media-footer ml-auto col-sm-2 mt-sm-0 mt-3 px-0 d-flex align-self-center align-items-center justify-content-end">
                                                                <h3 class="mb-0 font-w500 text-quinta fs-16">R$ 15,00</h3>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 px-0">
                                                        <hr class="hr-full-16">
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="media px-2 py-1 align-items-center">
                                                            <img class="img-fluid rounded mr-3" width="85"
                                                                src="./upload/cardapio/a03d6b81dcd33eca2f6ac1cafb01a06a.png"
                                                                alt="">
                                                            <div class="media-body col-sm-6 col-xxl-5 px-0 align-self-center align-items-center">
                                                                <h5 class="mt-0 mb-0 text-black">Supremo Raffaello</h5>
                                                            </div>
                                                            <div class="media-footer ml-auto col-sm-2 mt-sm-0 mt-3 px-0 d-flex align-self-center align-items-center justify-content-end">
                                                                <h3 class="mb-0 font-w600 text-black fs-22">1x</h3>
                                                            </div>
                                                            <div class="media-footer ml-auto col-sm-2 mt-sm-0 mt-3 px-0 d-flex align-self-center align-items-center justify-content-end">
                                                                <h3 class="mb-0 font-w600 text-black fs-22">R$ 5,00</h3>
                                                            </div>
                                                        </div>
                                                        <div class="media px-2 pb-2">
                                                            <div class="option-item-order media-body col-sm-6 col-xxl-5 px-0 align-self-center align-items-center">
                                                                <h5 class="mt-0 mb-0 text-quinta fs-16">(M) 380 ml</h5>
                                                            </div>
                                                            <div class="media-footer ml-auto col-sm-2 mt-sm-0 mt-3 px-0 d-flex align-self-center align-items-center justify-content-end">
                                                                <h5 class="mb-0 font-w600 text-quinta fs-16">1x</h5>
                                                            </div>
                                                            <div class="media-footer ml-auto col-sm-2 mt-sm-0 mt-3 px-0 d-flex align-self-center align-items-center justify-content-end">
                                                                <h3 class="mb-0 font-w500 text-quinta fs-16">R$ 15,00</h3>
                                                            </div>
                                                        </div>
                                                        <div class="media px-2 pb-2">
                                                            <div class="observation-item-order media-body col-12 pr-0 align-self-center align-items-center bg-observation-order">
                                                                <div class="media py-3 pl-2 pr-3">
                                                                    <h5 class="mt-0 mb-0 text-black fs-16 mr-3"><i class="fa-solid fa-pen"></i></i></h5>
                                                                    <h5 class="mt-0 mb-0 text-black fs-16"><span>Tirar cebola e maionese.</span></h5>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 px-0">
                                                        <hr class="hr-full-16">
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="media px-2 py-1 align-items-center">
                                                            <img class="img-fluid rounded mr-3" width="85"
                                                                src="./upload/cardapio/bb1ac07cff6d79e4b191911a43127cc6.png"
                                                                alt="">
                                                            <div class="media-body col-sm-6 col-xxl-5 px-0 align-self-center align-items-center">
                                                                <h5 class="mt-0 mb-0 text-black">Brownie recheado c/ Nutella</h5>
                                                            </div>
                                                            <div class="media-footer ml-auto col-sm-2 mt-sm-0 mt-3 px-0 d-flex align-self-center align-items-center justify-content-end">
                                                                <h3 class="mb-0 font-w600 text-black fs-22">1x</h3>
                                                            </div>
                                                            <div class="media-footer ml-auto col-sm-2 mt-sm-0 mt-3 px-0 d-flex align-self-center align-items-center justify-content-end">
                                                                <h3 class="mb-0 font-w600 text-black fs-22">R$ 17,00</h3>
                                                            </div>
                                                        </div>
                                                        <div class="media px-2 pb-2 ">
                                                            <div class="observation-item-order media-body col-12 pr-0 align-self-center align-items-center bg-observation-order">
                                                                <div class="media py-3 pl-2 pr-3">
                                                                    <h5 class="mt-0 mb-0 text-black fs-16 mr-3"><i class="fa-solid fa-pen"></i></i></h5>
                                                                    <h5 class="mt-0 mb-0 text-black fs-16"><span>Tirar cebola e maionese.</span></h5>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 px-0">
                                                        <hr class="hr-full-16">
                                                    </div>
                                                    <div class="col-12 px-0 up-hr">
                                                        <hr class="hr-full-16 hr-price">
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="media px-2 py-1 align-items-center">
                                                            <h5 class="mb-0 font-w600 fs-16 text-black"><i class="fa-solid fa-motorcycle fs-15 mr-2"></i>Taxa de entrega</h5>
                                                            <div class="media-footer ml-auto col-sm-2 mt-sm-0 mt-3 px-0 d-flex align-self-center align-items-center justify-content-end">
                                                                <h5 class="mb-0 font-w600 fs-18 text-black">R$ 10,00</h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 px-0">
                                                        <hr class="hr-full-16">
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="media px-2 py-1 align-items-center">
                                                            <h5 class="mb-0 font-w600 fs-16 text-black"><i class="fa-regular fa-circle-exclamation fs-18 mr-2"></i>Taxa de servi??o</h5>
                                                            <div class="media-footer ml-auto col-sm-2 mt-sm-0 mt-3 px-0 d-flex align-self-center align-items-center justify-content-end">
                                                                <h5 class="mb-0 font-w600 fs-18 text-black">R$ 1,50</h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 px-0">
                                                        <hr class="hr-full-16">
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="media px-2 py-1 align-items-center">
                                                            <h5 class="mb-0 font-w600 fs-16 text-black ml-4 pl-1">Valor total do pedido</h5>
                                                            <div class="media-footer ml-auto col-sm-2 mt-sm-0 mt-3 px-0 d-flex align-self-center align-items-center justify-content-end">
                                                                <h5 class="mb-0 font-w600 fs-18 text-black">R$ 19,90</h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 px-0">
                                                        <hr class="hr-full-16 hr-price">
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="media px-2 py-1 align-items-center">
                                                            <h5 class="mb-0 font-w400 fs-16 text-black"><i class="fa-regular fa-tag fs-18 mr-3"></i>Incentivos oferecido pelo ifood</h5>
                                                            <div class="media-footer ml-auto col-sm-2 mt-sm-0 mt-3 px-0 d-flex align-self-center align-items-center justify-content-end">
                                                                <h5 class="mb-0 font-w600 fs-18 text-black">-R$ 1,50</h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 px-0">
                                                        <hr class="hr-full-16">
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="media px-2 py-1 align-items-center">
                                                            <h5 class="mb-0 font-w400 fs-16 text-black"><i class="fa-regular fa-tag fs-18 mr-3"></i>Incentivos oferecido pela sua loja</h5>
                                                            <div class="media-footer ml-auto col-sm-2 mt-sm-0 mt-3 px-0 d-flex align-self-center align-items-center justify-content-end">
                                                                <h5 class="mb-0 font-w600 fs-18 text-black">-R$ 3,50</h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="customer-xxl col-3">
                                <div class="row row-customer">
                                    <div class="col-xl-12 col-sm-6">
                                        <div class="card card-mb-20">
                                            <button type="button" class="btn btn-success btn-lg py-4 btnOrderCfm">ACEITAR</button>
                                        </div>
                                    </div>
                                    <div class="col-xl-12 col-sm-6">
                                        <div class="card card-mb-20">
                                            <button type="button" class="btn btn-danger btn-lg btnOrderRej"><i class="fa-solid fa-ban fs-16 text-white"></i><span class="ml-2 fs-16">RECUSAR</span></button>
                                        </div>
                                    </div>
                                    <div class="col-xl-12 col-sm-6">
                                        <div class="card card-mb-20">
                                            <button type="button" class="btn btn-success btn-lg py-4 disabled"><i class="fa-duotone fa-spinner-third fs-18 fa-spin"></i></button>
                                        </div>
                                    </div>
                                    <div class="col-xl-12 col-sm-6">
                                        <div class="card card-mb-20">
                                            <button type="button" class="btn btn-success btn-lg btnOrderRtp"><span class="fs-16">AVISAR PEDIDO PRONTO</span></button>
                                        </div> 
                                    </div>
                                    <div class="col-xl-12 col-sm-6">
                                        <div class="card card-mb-20">
                                            <button type="button" class="btn btn-success btn-lg"><i class="fa-solid fa-motorcycle fs-16"></i> <span class="ml-2 fs-16">DESPACHAR</span></button>
                                        </div> 
                                    </div>
                                    <div class="col-xl-12 col-sm-6">
                                        <div class="card card-mb-20">
                                            <button type="button" class="btn btn-outline-success btn-lg bg-order-details-02 px-2"> <span class="ml-2 fs-14">SOLICITAR ENTREGADOR R$ 24,99</span></button>
                                        </div> 
                                    </div>
                                    <div class="col-xl-12 col-sm-6">
                                        <div class="card card-mb-20 bg-grey-2">
                                            <div class="card-header d-block border-0">
                                                <h4 class="mb-0 fs-16 text-center text-sub-grey-2 font-w600">SOLICITAR ENTREGADOR</h4>
                                            </div>
                                            <hr class="hr-full-16 hr-price m-0">
                                            <div class="card-body py-4">
                                                <h4 class="fs-16 text-sub-grey-2 font-w600">Indispon??vel para este pedido</h4>
                                                <p class="text-justify fs-14 mb-0 text-sub-grey-2">Fora do hor??rio de atendimento dos entregadores parceiros do iFood</p>
                                            </div>
                                        </div> 
                                    </div>

                                    <div class="col-xl-12 col-sm-6">
                                        <div class="card border border-light shadow-sm card-mb-20">
                                            <div class="card-body text-center pb-3">
                                                <img src="images/avatar/man_3.png" alt="" width="120"
                                                    class="rounded-circle mb-4">
                                                <h3 class="fs-22 text-black font-w600 mb-0">Jos?? Nascimento</h3>
                                            </div>
                                            <hr class="my-0">
                                            <div class="card-body py-3 px-4">
                                                <div class="media align-items-center">
                                                    <i class="fa-light fa-phone fs-28 mr-3"></i>
                                                    <div class="media-body">
                                                    <span class="text-black font-w500">0800 242 8347 <span class="text-secondary">: 99527477</span></span>
                                                    </div>
                                                </div>  
                                            </div>
                                            <div class="card-body bg-dark-panel rounded-top rounded-bottom py-4 ">
                                                <div class="media align-items-center text-center justify-content-center">
                                                    <i class="fa-solid fa-star fs-14 mr-2 text-star"></i>
                                                    <h4 class="fs-16 font-w600 mb-0 text-star">Super Cliente (9)</h4>
                                                    <i class="fa-solid fa-star fs-14 ml-2 text-star"></i>
                                                </div> 
                                            </div>
                                            <div class="card-body bg-dark-panel rounded-top rounded-bottom py-4 d-none">
                                                <div class="media align-items-center text-center justify-content-center">
                                                    <i class="fa-duotone fa-circle-star fs-12 mr-2 text-star"></i>
                                                    <h4 class="fs-16 font-w600 mb-0 text-star">Terceiro pedido</h4>
                                                    <i class="fa-duotone fa-circle-star fs-12 ml-2 text-star"></i>
                                                </div> 
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-12 col-sm-6">
                                        <div class="card border border-light shadow-sm card-mb-20">
                                            <div class="card-body pb-0 py-4">
                                                <div class="media align-items-center">
                                                    <i class="fa-regular fa-clock fs-18 mr-2"></i>
                                                    <div class="media-body ">
                                                        <h4 class="fs-16 font-w600 mb-0">Entrega prevista: </h4>
                                                    </div>
                                                    <h4 class="fs-16 font-w700 mb-0">15:17h</h4>
                                                </div>                                             
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-xl-12 col-sm-6">
                                        <div class="card border border-light shadow-sm card-mb-20">
                                            <div class="card-body pb-0 py-3">
                                                <div class="media align-items-center">
                                                    <div class="media-body ">
                                                        <h4 class="fs-18 font-w600 mb-0">Cobrar cliente <br><span class="fs-14">Mastercard | Cr??dito</span></h4>
                                                    </div>
                                                    <img class="" width="45" src="images/payments/mastercard.png"
                                                        alt="mastercard">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-12 col-sm-6">
                                        <div class="card border border-light shadow-sm card-mb-20">
                                            <div class="card-body pb-0 py-3">
                                                <div class="media align-items-center">
                                                    <div class="media-body ">
                                                        <h4 class="fs-18 font-w600 mb-0">Cobrar cliente <br><span class="fs-14">Dinheiro</span></h4>
                                                    </div>
                                                    <img class="" width="45" src="images/payments/dinheiro.png"
                                                        alt="mastercard">
                                                </div>
                                                <hr>
                                                <div class="media align-items-center">
                                                    <div class="media-body ">
                                                        <h4 class="fs-16 font-w600 mb-0">Valor a receber: </h4>
                                                    </div>
                                                    <h4 class="fs-16 font-w600 mb-0">R$ 50,00</h4>
                                                </div>
                                                <div class="media align-items-center mt-2">
                                                    <div class="media-body ">
                                                        <h4 class="fs-16 font-w600 mb-0">Levar de troco: </h4>
                                                    </div>
                                                    <h4 class="fs-16 font-w600 mb-0">R$ 19,45</h4>
                                                </div>                                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-12 col-sm-6">
                                        <div class="card border border-light shadow-sm card-mb-20">
                                            <div class="card-body pb-0 py-4">
                                                <div class="media align-items-center">
                                                    <div class="media-body ">
                                                        <h4 class="fs-18 font-w600 mb-0">Pago online</h4>
                                                    </div>
                                                    <img class="" width="45" src="images/payments/logo_ifood_3.png"
                                                        alt="mastercard">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-12 col-sm-6">
                                        <div class="card border border-light shadow-sm card-mb-20">
                                            <div class="card-body pb-0 py-3">
                                                <div class="media align-items-center">
                                                    <div class="media-body ">
                                                        <h4 class="fs-18 font-w600 mb-0">Cobrar cliente <br><span class="fs-14">Dinheiro</span></h4>
                                                    </div>
                                                    <img class="" width="45" src="images/payments/dinheiro.png"
                                                        alt="mastercard">
                                                </div>
                                                <hr>
                                                <div class="media align-items-center">
                                                    <div class="media-body ">
                                                        <h4 class="fs-16 font-w600 mb-0">Valor a receber: </h4>
                                                    </div>
                                                    <h4 class="fs-16 font-w600 mb-0">R$ 50,00</h4>
                                                </div>
                                                <div class="align-items-center text-center mt-2">
                                                    <small class="fs-16 font-w600 mb-0">N??o levar troco</small>
                                                </div>                                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-12 col-sm-6">
                                        <div class="card card-mb-20">
                                            <button type="button" class="btn btn-danger btn-lg btnOrderCan"><i class="fa-solid fa-ban fs-16 text-white"></i><span class="ml-2 fs-16">CANCELAR</span></button>
                                        </div>
                                    </div>

                                    


                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center d-none">
                            <div class="col-xxl-12 col-11">
                                <h4 class="fs-26 text-black mb-3 font-gilroy-semibold">Pedido #5231<span class="fs-18"><i class="fa-regular fa-clock fs-16 ml-3 mr-1"></i> Entregar entre 11:00 - 11:30</span></h4>
                            </div>
                            <div class="col-xxl-12 col-xl-8">
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="card border border-light shadow-sm">
                                            <div class="card-body py-2">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="row align-items-center">
                                                            <div class="col-xl-2 text-center">
                                                                <img class="order-details-origin-ifood w-75"
                                                                    src="images/logo_ifood_2.png" alt="ifood">
                                                            </div>
                                                            <div class="col-xl-10">
                                                                <div class="row align-items-center">
                                                                    <div class="col-xl-7">
                                                                        <div class="row align-items-center">
                                                                            <div class="col-xl-1">
                                                                            <i class="fa-light fa-location-dot fs-30 text-black"></i>
                                                                            </div>
                                                                            <div class="col-xl-11">
                                                                                <span class="text-black font-w500">Rua
                                                                                    Padre Cicero, 567 - Jardim
                                                                                    Guaira</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xl-5">
                                                                        <div class="row align-items-center">
                                                                            <div class="col-xl-2">
                                                                            <i class="fa-light fa-phone fs-30 text-black"></i>
                                                                            </div>
                                                                            <div class="col-xl-10 pl-2">
                                                                                <span class="text-black font-w500">0800 242 8347 <span class="text-secondary">: 99527477</span></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-12">
                                        <div class="card border border-light shadow-sm">
                                            <div class="card-body py-3">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="media px-2 py-1 align-items-center">
                                                            <img class="img-fluid rounded mr-3" width="85"
                                                                src="./upload/cardapio/bb1ac07cff6d79e4b191911a43127cc6.png"
                                                                alt="">
                                                            <div
                                                                class="media-body col-sm-6 col-xxl-5 px-0 align-self-center align-items-center">
                                                                <h5 class="mt-0 mb-0 text-black">Duplo brigadeiro</h5>
                                                            </div>
                                                            <div
                                                                class="media-footer ml-auto col-sm-2 mt-sm-0 mt-3 px-0 d-flex align-self-center align-items-center justify-content-end">
                                                                <div>
                                                                    <h3 class="mb-0 font-w600 text-black fs-22">1x</h3>
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="media-footer ml-auto col-sm-2 mt-sm-0 mt-3 px-0 d-flex align-self-center align-items-center justify-content-end">
                                                                <div>
                                                                    <h3 class="mb-0 font-w600 text-black fs-22">R$ 34,00</h3>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 px-0">
                                                        <hr>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="media px-2 py-1 align-items-center">
                                                            <img class="img-fluid rounded mr-3" width="85"
                                                                src="./upload/cardapio/bb1ac07cff6d79e4b191911a43127cc6.png"
                                                                alt="">
                                                            <div
                                                                class="media-body col-sm-6 col-xxl-5 px-0 align-self-center align-items-center">
                                                                <h5 class="mt-0 mb-0 text-black">Morango com suspiro</h5>
                                                            </div>
                                                            <div
                                                                class="media-footer ml-auto col-sm-2 mt-sm-0 mt-3 px-0 d-flex align-self-center align-items-center justify-content-end">
                                                                <div>
                                                                    <h3 class="mb-0 font-w600 text-black fs-22">1x</h3>
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="media-footer ml-auto col-sm-2 mt-sm-0 mt-3 px-0 d-flex align-self-center align-items-center justify-content-end">
                                                                <div>
                                                                    <h3 class="mb-0 font-w600 text-black fs-22">R$ 17,00</h3>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="media px-2 pb-2">
                                                            <div class="option-item-order media-body col-sm-6 col-xxl-5 px-0 align-self-center align-items-center">
                                                                <h5 class="mt-0 mb-0 text-quinta fs-16">(P) - 210 ml</h5>
                                                            </div>
                                                            <div
                                                                class="media-footer ml-auto col-sm-2 mt-sm-0 mt-3 px-0 d-flex align-self-center align-items-center justify-content-end">
                                                                <div>
                                                                    <h5 class="mb-0 font-w600 text-quinta fs-16">1x</h5>
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="media-footer ml-auto col-sm-2 mt-sm-0 mt-3 px-0 d-flex align-self-center align-items-center justify-content-end">
                                                                <div>
                                                                    <h3 class="mb-0 font-w500 text-quinta fs-16">R$ 15,00</h3>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 px-0">
                                                        <hr>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="media px-2 py-1 align-items-center">
                                                            <img class="img-fluid rounded mr-3" width="85"
                                                                src="./upload/cardapio/a03d6b81dcd33eca2f6ac1cafb01a06a.png"
                                                                alt="">
                                                            <div class="media-body col-sm-6 col-xxl-5 px-0 align-self-center align-items-center">
                                                                <h5 class="mt-0 mb-0 text-black">Supremo Raffaello</h5>
                                                            </div>
                                                            <div class="media-footer ml-auto col-sm-2 mt-sm-0 mt-3 px-0 d-flex align-self-center align-items-center justify-content-end">
                                                                <div>
                                                                    <h3 class="mb-0 font-w600 text-black fs-22">1x</h3>
                                                                </div>
                                                            </div>
                                                            <div class="media-footer ml-auto col-sm-2 mt-sm-0 mt-3 px-0 d-flex align-self-center align-items-center justify-content-end">
                                                                <div>
                                                                    <h3 class="mb-0 font-w600 text-black fs-22">R$ 5,00</h3>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="media px-2 pb-2">
                                                            <div class="option-item-order media-body col-sm-6 col-xxl-5 px-0 align-self-center align-items-center">
                                                                <h5 class="mt-0 mb-0 text-quinta fs-16">(M) 380 ml</h5>
                                                            </div>
                                                            <div
                                                                class="media-footer ml-auto col-sm-2 mt-sm-0 mt-3 px-0 d-flex align-self-center align-items-center justify-content-end">
                                                                <div>
                                                                    <h5 class="mb-0 font-w600 text-quinta fs-16">1x</h5>
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="media-footer ml-auto col-sm-2 mt-sm-0 mt-3 px-0 d-flex align-self-center align-items-center justify-content-end">
                                                                <div>
                                                                    <h3 class="mb-0 font-w500 text-quinta fs-16">R$ 15,00</h3>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="media px-2 pb-2 ">
                                                            <div class="observation-item-order media-body col-12 pr-0 align-self-center align-items-center bg-observation-order">
                                                                <h5 class="mt-0 mb-0 text-black fs-16 py-3 px-2"><i class="fa-solid fa-pen"></i></i><span class="pl-3">Tirar cebola e maionese.</span></h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 px-0">
                                                        <hr>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="media px-2 py-1 align-items-center">
                                                            <img class="img-fluid rounded mr-3" width="85"
                                                                src="./upload/cardapio/bb1ac07cff6d79e4b191911a43127cc6.png"
                                                                alt="">
                                                            <div class="media-body col-sm-6 col-xxl-5 px-0 align-self-center align-items-center">
                                                                <h5 class="mt-0 mb-0 text-black">Brownie recheado c/ Nutella</h5>
                                                            </div>
                                                            <div class="media-footer ml-auto col-sm-2 mt-sm-0 mt-3 px-0 d-flex align-self-center align-items-center justify-content-end">
                                                                <div>
                                                                    <h3 class="mb-0 font-w600 text-black fs-22">1x</h3>
                                                                </div>
                                                            </div>
                                                            <div class="media-footer ml-auto col-sm-2 mt-sm-0 mt-3 px-0 d-flex align-self-center align-items-center justify-content-end">
                                                                <div>
                                                                    <h3 class="mb-0 font-w600 text-black fs-22">R$ 17,00</h3>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="media px-2 pb-2 ">
                                                            <div class="observation-item-order media-body col-12 pr-0 align-self-center align-items-center bg-observation-order">
                                                                <h5 class="mt-0 mb-0 text-black fs-16 py-3 px-2"><i class="fa-solid fa-pen"></i></i><span class="pl-3">Tirar cebola e maionese.</span></h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 px-0">
                                                        <hr>
                                                    </div>

                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="customer-xxl col-3">
                                <div class="row row-customer">
                                    <div class="col-xl-12 col-sm-6">
                                        <div class="card border border-light shadow-sm card-mb-20">
                                            <div class="card-body text-center pb-3">
                                                <img src="images/avatar/man_3.png" alt="" width="120"
                                                    class="rounded-circle mb-4">
                                                <h3 class="fs-22 text-black font-w600 mb-0">Jos?? Nascimento</h3>
                                            </div>
                                            <div class="card-body bg-dark-panel rounded-top rounded-bottom py-4 d-none">
                                                <div class="media align-items-center text-center justify-content-center">
                                                    <i class="fa-solid fa-star fs-14 mr-2 text-star"></i>
                                                    <h4 class="fs-16 font-w600 mb-0 text-star">Super Cliente</h4>
                                                    <i class="fa-solid fa-star fs-14 ml-2 text-star"></i>
                                                </div> 
                                            </div>
                                            <div class="card-body bg-dark-panel rounded-top rounded-bottom py-4 ">
                                                <div class="media align-items-center text-center justify-content-center">
                                                    <i class="fa-duotone fa-circle-star fs-12 mr-2 text-star"></i>
                                                    <h4 class="fs-16 font-w600 mb-0 text-star">Terceiro pedido</h4>
                                                    <i class="fa-duotone fa-circle-star fs-12 ml-2 text-star"></i>
                                                </div> 
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-12 col-sm-6 d-none">
                                        <div class="card border border-light shadow-sm card-mb-20">
                                            <div class="card-body pb-0 py-3">
                                                <div class="media align-items-center">
                                                    <div class="media-body ">
                                                        <h4 class="fs-18 font-w600 mb-0">Cobrar cliente <br><span class="fs-14">Mastercard | Credito</span></h4>
                                                    </div>
                                                    <img class="" width="45" src="images/payments/mastercard.png"
                                                        alt="mastercard">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-12 col-sm-6">
                                        <div class="card border border-light shadow-sm card-mb-20">
                                            <div class="card-body pb-0 py-3">
                                                <div class="media align-items-center">
                                                    <div class="media-body ">
                                                        <h4 class="fs-18 font-w600 mb-0">Cobrar cliente <br><span class="fs-14">Dinheiro</span></h4>
                                                    </div>
                                                    <img class="" width="45" src="images/payments/dinheiro.png"
                                                        alt="mastercard">
                                                </div>
                                                <hr>
                                                <div class="media align-items-center">
                                                    <div class="media-body ">
                                                        <h4 class="fs-16 font-w600 mb-0">Valor a receber: </h4>
                                                    </div>
                                                    <h4 class="fs-16 font-w600 mb-0">R$ 50,00</h4>
                                                </div>
                                                <div class="media align-items-center mt-2">
                                                    <div class="media-body ">
                                                        <h4 class="fs-16 font-w600 mb-0">Levar de troco: </h4>
                                                    </div>
                                                    <h4 class="fs-16 font-w600 mb-0">R$ 19,45</h4>
                                                </div>                                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-12 col-sm-6 d-none">
                                        <div class="card border border-light shadow-sm card-mb-20">
                                            <div class="card-body pb-0 py-4">
                                                <div class="media align-items-center">
                                                    <div class="media-body ">
                                                        <h4 class="fs-18 font-w600 mb-0">Pago online</h4>
                                                    </div>
                                                    <img class="" width="45" src="images/payments/logo_ifood_3.png"
                                                        alt="mastercard">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-12 col-sm-6 d-none">
                                        <div class="card border border-light shadow-sm card-mb-20">
                                            <div class="card-body pb-0 py-3">
                                                <div class="media align-items-center">
                                                    <div class="media-body ">
                                                        <h4 class="fs-18 font-w600 mb-0">Cobrar cliente <br><span class="fs-14">Dinheiro</span></h4>
                                                    </div>
                                                    <img class="" width="45" src="images/payments/dinheiro.png"
                                                        alt="mastercard">
                                                </div>
                                                <hr>
                                                <div class="media align-items-center">
                                                    <div class="media-body ">
                                                        <h4 class="fs-16 font-w600 mb-0">Valor a receber: </h4>
                                                    </div>
                                                    <h4 class="fs-16 font-w600 mb-0">R$ 50,00</h4>
                                                </div>
                                                <div class="align-items-center text-center mt-2">
                                                    <small class="fs-16 font-w600 mb-0">N??o levar troco</small>
                                                </div>                                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-12 col-sm-6">
                                        <div class="card card-mb-20">
                                            <button type="button" class="btn btn-danger btn-lg"><i class="fa-solid fa-ban fs-16 text-white"></i><span class="ml-2 fs-16">RECUSAR</span></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--**********************************
            Content body end
        ***********************************-->

        <!-- Add Order -->
        <?php include("include/modal_order.php"); ?>
        <!--**********************************
            Footer start
        ***********************************-->
        <!--**********************************
            Footer end
        ***********************************-->

        <!--**********************************
           Support ticket button start
        ***********************************-->

        <!--**********************************
           Support ticket button end
        ***********************************-->


    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!--**********************************
		Scripts
	***********************************-->
    <?php include("include/js.php"); ?>

    <script type="text/javascript">
        
    </script>

    <script src="./js/painel-pedidos.js"></script>
</body>

</html>