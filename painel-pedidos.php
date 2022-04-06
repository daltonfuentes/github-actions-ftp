<?php
ob_start();
session_start();

require_once("conexao/conexao.php");
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
                <img class="logo-abbr" src="/images/logo.png" alt="">
                <img class="logo-compact" src="/images/logo-full.png" alt="">
                <img class="brand-title" src="/images/logo-full.png" alt="">
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

        <?php include("include/sidebar.php"); ?>

        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body p-0">
            <div class="container-fluid py-0 px-3 bg-dark-panel">
                <div class="row">
                    <div class="col-xl-3 col-lg-4 col-md-6 pl-4 pt-0 bg-body-painel rounded-left-lg pb-5 vh-100">
                        <div class="row pt-4 pr-3 justify-content-center" id="row-filter">
                            <div class="col-4">
                                <div class="btn btn-dark h-100 w-100 py-3 px-3 btn-status" data-platform="ifood" data-status="close">
                                    <media>
                                        <img class="platform animate__fadeIn animate__animated" height="24" src="images/logo_ifood_4.png" alt="ifood">
                                        <img class="d-none loading" height="24" src="images/loading.gif" alt="loading">
                                    </media>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="btn btn-dark h-100 w-100 py-3 px-3 btn-status" data-platform="web" data-status="close">
                                    <media>
                                        <img class="platform animate__fadeIn animate__animated" height="20" src="images/icon_shop.png" alt="shop">
                                        <img class="d-none loading" height="24" src="images/loading.gif" alt="loading">
                                    </media>
                                </div>
                            </div>
                            <div class="col-12 mt-4">
                                <div class="card shadow-sm">
                                    <div class="card-body">
                                        <div class="btn-group">
                                            <h4 class="cPointer mb-0" data-toggle="dropdown"><span
                                                    class="font-gilroy-bold fs-18">Todos os pedidos </span><i
                                                    class="fa-solid fa-caret-down fs-1"></i></h4>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="#">Novos</a>
                                                <a class="dropdown-item" href="#">Preparo</a>
                                                <a class="dropdown-item" href="#">Entrega</a>
                                                <a class="dropdown-item" href="#">Concluido</a>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-primary py-1"
                                            style="position: absolute; right: 30px; top: 24px;"><i
                                                class="fa-solid fa-plus fs-20"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row pr-2 pt-1" id="row-list-orders">
                            <div class="col-12 mb-3">
                                <div class="card shadow  mb-0 d-block">
                                    <div
                                        class="card-body cPointer pl-4 mb-0 bg-white rounded faixa-pedido animate__pulse animate__infinite alerta active">
                                        <div class="media">
                                            <div class="px-1">
                                                <h4 class="font-gilroy-bold fs-20 mb-1">Antonio M. <small
                                                        class="fs-20 ml-2 text-dark">#4020</small></h4>
                                                <span class=""><i class="fa-regular fa-clock fs-16"></i></span>
                                                <span class="font-gilroy-medium fs-16 ml-2 position-absolute">Enviar até
                                                    17:35</span>
                                            </div>
                                            <div class="media-footer valor-total-pedido">
                                                <h4 class="mb-0 font-gilroy-extrabold text-terceiro fs-22 badge-atraso">
                                                    <span class="badge badge-danger">ATRASO</span></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <div class="card shadow mb-0 d-block">
                                    <div class="card-body cPointer pl-4 mb-0 bg-white rounded faixa-pedido">
                                        <div class="media">
                                            <div class="px-1">
                                                <h4 class="font-gilroy-bold fs-20 mb-1">Antonio M. <small
                                                        class="fs-20 ml-2 text-dark">#4020</small></h4>
                                                <span class=""><i class="fa-regular fa-clock fs-16"></i></span>
                                                <span class="font-gilroy-medium fs-16 ml-2 position-absolute">Enviar até
                                                    17:35</span>
                                            </div>
                                            <div class="media-footer valor-total-pedido">
                                                <h4
                                                    class="mb-0 font-gilroy-extrabold text-terceiro fs-22 badge-preparo">
                                                    <span class="badge badge-warning">PREPARO</span></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <div class="card shadow  mb-0 d-block">
                                    <div class="card-body cPointer pl-4 mb-0 bg-white rounded faixa-pedido">
                                        <div class="media">
                                            <div class="px-1">
                                                <h4 class="font-gilroy-bold fs-20 mb-1">Antonio M. <small
                                                        class="fs-20 ml-2 text-dark">#4020</small></h4>
                                                <span class=""><i class="fa-regular fa-clock fs-16"></i></span>
                                                <span class="font-gilroy-medium fs-16 ml-2 position-absolute">Enviar até
                                                    17:35</span>
                                            </div>
                                            <div class="media-footer valor-total-pedido">
                                                <h4
                                                    class="mb-0 font-gilroy-extrabold text-terceiro fs-22 badge-entrega">
                                                    <span class="badge badge-info">ENTREGA</span></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <div class="card shadow  mb-0 d-block">
                                    <div class="card-body cPointer pl-4 mb-0 bg-white rounded faixa-pedido">
                                        <div class="media">
                                            <div class="px-1">
                                                <h4 class="font-gilroy-bold fs-20 mb-1">Antonio M. <small
                                                        class="fs-20 ml-2 text-dark">#4020</small></h4>
                                                <span class=""><i class="fa-regular fa-clock fs-16"></i></span>
                                                <span class="font-gilroy-medium fs-16 ml-2 position-absolute">Enviar até
                                                    17:35</span>
                                            </div>
                                            <div class="media-footer valor-total-pedido">
                                                <h4 class="mb-0 font-gilroy-extrabold text-terceiro fs-22"><span
                                                        class="badge badge-success">CONCLUIDO</span></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <div class="card shadow  mb-0 d-block">
                                    <div class="card-body cPointer pl-4 mb-0 bg-white rounded faixa-pedido">
                                        <div class="media">
                                            <div class="px-1">
                                                <h4 class="font-gilroy-bold fs-20 mb-1">Antonio M. <small
                                                        class="fs-20 ml-2 text-dark">#4020</small></h4>
                                                <span class=""><i class="fa-regular fa-clock fs-16"></i></span>
                                                <span class="font-gilroy-medium fs-16 ml-2 position-absolute">Enviar até
                                                    17:35</span>
                                            </div>
                                            <div class="media-footer valor-total-pedido">
                                                <h4 class="mb-0 font-gilroy-extrabold text-terceiro fs-22"><span
                                                        class="badge badge-success">CONCLUIDO</span></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <div class="card shadow  mb-0 d-block">
                                    <div class="card-body cPointer pl-4 mb-0 bg-white rounded faixa-pedido">
                                        <div class="media">
                                            <div class="px-1">
                                                <h4 class="font-gilroy-bold fs-20 mb-1">Antonio M. <small
                                                        class="fs-20 ml-2 text-dark">#4020</small></h4>
                                                <span class=""><i class="fa-regular fa-clock fs-16"></i></span>
                                                <span class="font-gilroy-medium fs-16 ml-2 position-absolute">Enviar até
                                                    17:35</span>
                                            </div>
                                            <div class="media-footer valor-total-pedido">
                                                <h4 class="mb-0 font-gilroy-extrabold text-terceiro fs-22"><span
                                                        class="badge badge-success">CONCLUIDO</span></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <div class="card shadow  mb-0 d-block">
                                    <div class="card-body cPointer pl-4 mb-0 bg-white rounded faixa-pedido">
                                        <div class="media">
                                            <div class="px-1">
                                                <h4 class="font-gilroy-bold fs-20 mb-1">Antonio M. <small
                                                        class="fs-20 ml-2 text-dark">#4020</small></h4>
                                                <span class=""><i class="fa-regular fa-clock fs-16"></i></span>
                                                <span class="font-gilroy-medium fs-16 ml-2 position-absolute">Enviar até
                                                    17:35</span>
                                            </div>
                                            <div class="media-footer valor-total-pedido">
                                                <h4 class="mb-0 font-gilroy-extrabold text-terceiro fs-22"><span
                                                        class="badge badge-success">CONCLUIDO</span></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-9 shadow-sm bg-white py-5 px-5 vh-100 overflow-auto">
                        <div class="row">
                            <div class="col-xl-9 col-xxl-8">
                                <div class="row">
                                    <div class="col-12">
                                        <h4 class="fs-26 text-black mb-4 font-gilroy-semibold">Pedido #5231</h4>
                                    </div>
                                    <div class="col-xl-12">
                                        <div class="card border border-light shadow-sm">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="row align-items-center">
                                                            <div class="media">
                                                                <img class="ml-4 pt-1" width="75"
                                                                    src="images/logo_ifood_2.png" alt="ifood">
                                                                <div class="media-body mt-4 ml-5">
                                                                    <svg class="mr-3 min-w32" width="32" height="32"
                                                                        viewBox="0 0 32 32" fill="none"
                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                        <path
                                                                            d="M28.0005 13.3335C28.0005 22.6668 16.0005 30.6668 16.0005 30.6668C16.0005 30.6668 4.00049 22.6668 4.00049 13.3335C4.00049 10.1509 5.26477 7.09865 7.51521 4.84821C9.76564 2.59778 12.8179 1.3335 16.0005 1.3335C19.1831 1.3335 22.2353 2.59778 24.4858 4.84821C26.7362 7.09865 28.0005 10.1509 28.0005 13.3335Z"
                                                                            stroke="#3E4954" stroke-width="2"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round" />
                                                                        <path
                                                                            d="M16.0005 17.3335C18.2096 17.3335 20.0005 15.5426 20.0005 13.3335C20.0005 11.1244 18.2096 9.3335 16.0005 9.3335C13.7913 9.3335 12.0005 11.1244 12.0005 13.3335C12.0005 15.5426 13.7913 17.3335 16.0005 17.3335Z"
                                                                            stroke="#3E4954" stroke-width="2"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round" />
                                                                    </svg>
                                                                    <span class="text-black font-w500">Rua Padre Cicero,
                                                                        567 - Jardim Guaira</span>
                                                                </div>
                                                                <div class="media-footer mt-4 ml-5 pt-1">
                                                                    <svg class="mr-3 min-w32" width="24" height="24"
                                                                        viewBox="0 0 24 24" fill="none"
                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                        <path
                                                                            d="M22.9993 17.4712V20.7831C23.0006 21.0906 22.9375 21.3949 22.814 21.6766C22.6906 21.9583 22.5096 22.2112 22.2826 22.419C22.0556 22.6269 21.7876 22.7851 21.4958 22.8836C21.2039 22.9821 20.8947 23.0187 20.5879 22.991C17.1841 22.6219 13.9145 21.4611 11.0418 19.6019C8.36914 17.9069 6.10319 15.6455 4.40487 12.9781C2.53545 10.0981 1.37207 6.81909 1.00898 3.40674C0.981336 3.10146 1.01769 2.79378 1.11572 2.50329C1.21376 2.2128 1.37132 1.94586 1.57839 1.71947C1.78546 1.49308 2.03749 1.31221 2.31843 1.18836C2.59938 1.06451 2.90309 1.0004 3.21023 1.00011H6.52869C7.06551 0.994834 7.58594 1.18456 7.99297 1.53391C8.4 1.88326 8.66586 2.36841 8.74099 2.89892C8.88106 3.9588 9.14081 4.99946 9.5153 6.00106C9.66413 6.39619 9.69634 6.82562 9.60812 7.23847C9.51989 7.65131 9.31494 8.03026 9.01753 8.33042L7.61272 9.73245C9.18739 12.4963 11.4803 14.7847 14.2496 16.3562L15.6545 14.9542C15.9552 14.6574 16.3349 14.4528 16.7486 14.3648C17.1622 14.2767 17.5925 14.3089 17.9884 14.4574C18.992 14.8312 20.0348 15.0904 21.0967 15.2302C21.6341 15.3058 22.1248 15.576 22.4756 15.9892C22.8264 16.4024 23.0128 16.9298 22.9993 17.4712Z"
                                                                            stroke="#566069" stroke-width="2"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round" />
                                                                    </svg>
                                                                    <span class="text-black font-w500">(45)
                                                                        99919-3908</span>
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
                                                        <div class="media px-2 py-2">
                                                            <img class="img-fluid rounded mr-3" width="85" src="./upload/cardapio/bb1ac07cff6d79e4b191911a43127cc6.png" alt="">
                                                            <div class="media-body col-sm-6 col-xxl-5 px-0 align-self-center align-items-center">
                                                                <h5 class="mt-0 mb-0 text-black">Duplo brigadeiro c/ Morango e Brownie</h5>
                                                            </div>
                                                            <div class="media-footer ml-auto col-sm-2 mt-sm-0 mt-3 px-0 d-flex align-self-center align-items-center justify-content-end">
                                                                <div>
                                                                    <h3 class="mb-0 font-w600 text-black">1x</h3>
                                                                </div>
                                                            </div>
                                                            <div class="media-footer ml-auto col-sm-2 mt-sm-0 mt-3 px-0 d-flex align-self-center align-items-center justify-content-end">
                                                                <div>
                                                                    <h3 class="mb-0 font-w600 text-black">R$ 17,00</h3>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="media px-2 py-2 adicional-product">
                                                            <div class="media-body col-sm-6 col-xxl-5 px-0 align-self-center align-items-center">
                                                                <h5 class="mt-0 mb-0 text-black text-quinta fs-16">(P) - 210 ml</h5>
                                                            </div>
                                                            <div class="media-footer ml-auto col-sm-2 mt-sm-0 mt-3 px-0 d-flex align-self-center align-items-center justify-content-end">
                                                                <div>
                                                                    <h5 class="mb-0 font-w600 text-black text-quinta fs-16">1x</h5>
                                                                </div>
                                                            </div>
                                                            <div class="media-footer ml-auto col-sm-2 mt-sm-0 mt-3 px-0 d-flex align-self-center align-items-center justify-content-end">
                                                                <div>
                                                                    <h3 class="mb-0 font-w500 text-quinta fs-18">R$ 15,00</h3>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="media px-2 py-2">
                                                            <img class="img-fluid rounded mr-3" width="85" src="./upload/cardapio/bb1ac07cff6d79e4b191911a43127cc6.png" alt="">
                                                            <div class="media-body col-sm-6 col-xxl-5 px-0 align-self-center align-items-center">
                                                                <h5 class="mt-0 mb-0 text-black">Duplo brigadeiro</h5>
                                                            </div>
                                                            <div class="media-footer ml-auto col-sm-2 mt-sm-0 mt-3 px-0 d-flex align-self-center align-items-center justify-content-end">
                                                                <div>
                                                                    <h3 class="mb-0 font-w600 text-black">12x</h3>
                                                                </div>
                                                            </div>
                                                            <div class="media-footer ml-auto col-sm-2 mt-sm-0 mt-3 px-0 d-flex align-self-center align-items-center justify-content-end">
                                                                <div>
                                                                    <h3 class="mb-0 font-w600 text-black">R$ 75,00</h3>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="media px-2 py-2">
                                                            <img class="img-fluid rounded mr-3" width="85" src="./upload/cardapio/bb1ac07cff6d79e4b191911a43127cc6.png" alt="">
                                                            <div class="media-body col-sm-6 col-xxl-5 px-0 align-self-center align-items-center">
                                                                <h5 class="mt-0 mb-0 text-black">Duplo brigadeiro</h5>
                                                            </div>
                                                            <div class="media-footer ml-auto col-sm-2 mt-sm-0 mt-3 px-0 d-flex align-self-center align-items-center justify-content-end">
                                                                <div>
                                                                    <h3 class="mb-0 font-w600 text-black">1x</h3>
                                                                </div>
                                                            </div>
                                                            <div class="media-footer ml-auto col-sm-2 mt-sm-0 mt-3 px-0 d-flex align-self-center align-items-center justify-content-end">
                                                                <div>
                                                                    <h3 class="mb-0 font-w600 text-black">R$ 34,00</h3>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>



                                                <div class="table-responsive order-list card-table d-none">
                                                    <table class="table table-responsive-md mb-0">
                                                        <tbody>
                                                            <tr class="border-0">
                                                                <td class="pl-0 border-0">
                                                                    <div class="media">
                                                                        <img class="mr-3 img-fluid rounded" width="85" src="./upload/cardapio/bb1ac07cff6d79e4b191911a43127cc6.png" alt="DexignZone">
                                                                        <div class="media-body">
                                                                            <h5 class="mt-4 pt-2 text-black">Copo dos sonhos Ninho</h5>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td class="pr-5 border-0">
                                                                    <h4 class="my-0 text-black font-w600">1x</h4>
                                                                </td>
                                                                <td class="border-0 text-right">
                                                                    <h4 class="my-0 text-black font-w600">R$ 34,00</h4>
                                                                </td>
                                                            </tr>
                                                            <tr class="border-0">
                                                                <td class="pl-0 border-0">
                                                                    <div class="media">
                                                                        <img class="mr-3 img-fluid rounded" width="85" src="./upload/cardapio/bb1ac07cff6d79e4b191911a43127cc6.png" alt="DexignZone">
                                                                        <div class="media-body">
                                                                            <h5 class="mt-4 pt-2 text-black">Duplo brigadeiro</h5>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td class="pr-5 border-0">
                                                                    <h4 class="my-0 text-black font-w600">3x</h4>
                                                                </td>
                                                                <td class="border-0 text-right">
                                                                    <h4 class="my-0 text-black font-w600">R$ 58,00</h4>
                                                                </td>
                                                            </tr>
                                                            <tr class="border-0">
                                                                <td class="pl-0 border-0">
                                                                    <div class="media">
                                                                        <img class="mr-3 img-fluid rounded" width="85" src="./upload/cardapio/bb1ac07cff6d79e4b191911a43127cc6.png" alt="DexignZone">
                                                                        <div class="media-body">
                                                                            <h5 class="mt-4 pt-2 text-black">Morango com suspiro</h5>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td class="pr-5 border-0">
                                                                    <h4 class="my-0 text-black font-w600">1x</h4>
                                                                </td>
                                                                <td class="border-0 text-right">
                                                                    <h4 class="my-0 text-black font-w600">R$ 119,00</h4>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-xxl-4">
                                <div class="row">
                                    <div class="col-xl-12 col-sm-6">
                                        <div class="card border border-light shadow-sm">
                                            <div class="card-body text-center">
                                                <img src="images/avatar/man_3.png" alt="" width="130"
                                                    class="rounded-circle mb-4">
                                                <h3 class="fs-22 text-black font-w600 mb-3">Antonio M.</h3>
                                                <h4 class="font-gilroy-bold text-terceiro fs-18"><span
                                                        class="badge badge-primary light py-2 px-3">3º Pedido</span>
                                                </h4>
                                            </div>
                                            <div class="card-body bg-light rounded-top rounded-bottom">
                                                <h3 class="fs-18 text-black font-w600">Observação</h3>
                                                <p class="fs-14">Lorem ipsum dolor sit amet, consectetur adipiscing
                                                    elit, sed do eiusmod tempor incididunt ut labore et dolore magna
                                                    aliqua.</p>
                                            </div>
                                            <div class="card-body pb-0 py-2">
                                                <div class="media">
                                                    <div class="media-body">
                                                        <h4 class="fs-18 font-w600 pt-4">Pago via loja</h4>
                                                    </div>
                                                    <img class="ml-3 pt-1" width="75" src="images/icon_maquininha.png"
                                                        alt="ifood">
                                                    <img class="ml-3 pt-1 d-none" width="75"
                                                        src="images/icon_maestro.png" alt="ifood">
                                                    <img class="ml-3 pt-1 d-none" width="75"
                                                        src="images/icon_mastercard.png" alt="ifood">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-12 col-sm-6 d-none">
                                        <div class="card border border-light shadow-sm">
                                            <div class="card-header border-0 pb-0 d-none">
                                                <h4 class="fs-20 font-w600">Pagamento</h4>
                                            </div>
                                            <div class="card-body pb-0 pt-2">
                                                <div class="media">
                                                    <div class="media-body">
                                                        <h4 class="fs-18 font-w600 pt-4">Pago via</h4>
                                                    </div>
                                                    <img class="ml-3 pt-1" width="75" src="images/logo_ifood_2.png"
                                                        alt="ifood">
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