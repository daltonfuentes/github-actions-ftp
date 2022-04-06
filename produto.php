<?php
ob_start();
session_start();

require_once("conexao/conexao.php");

$url = $_SERVER['REQUEST_URI'];
$url = explode("/", $url);
$codProduto = $url[count($url)-1];

$sql = "SELECT * FROM estoque WHERE cod='$codProduto'";
$resultado = $conexaoAdmin->prepare($sql);	
$resultado->execute();
$contar = $resultado->rowCount();

if($contar == 0):
    header("Location: ../compras");
    exit();
else:
    $exibe = $resultado->fetch(PDO::FETCH_OBJ);
endif;

$unidade = $exibe->unidade;
$unidadeCru = $exibe->unidade;

if($unidade == 'un'):
    $unidade = ' '.$unidade;
endif;

$ideal = floatval($exibe->estoque_ideal);
$atual = floatval($exibe->estoque_atual);
$status = round(100*($atual/$ideal));    
$min = floatval($exibe->estoque_min);
if($atual>= $ideal):
    $bgl="bgl-primary";
    $bg="bg-primary";
    $alerta = "";
elseif($atual < $ideal && $atual > $min):
    $bgl="bgl-warning";
    $bg="bg-warning";
    $alerta = '';
else:
    $bgl="bgl-warning";
    $bg="bg-warning";
    $alerta = '<i class="fas fa-exclamation-triangle text-warning animate__animated animate__flash animate__infinite animate__slower animate__delay-2s mt-4" style="font-size: 300%;"></i>';
endif;

$nomeProduto = $exibe->produto;

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Sego - Restaurant Bootstrap Admin Dashboard</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo URL; ?>/images/favicon.png">

    <?php include("./include/css.php"); ?>

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&family=Roboto:wght@100;300;400;500;700;900&display=swap"
        rel="stylesheet">
</head>

<body>

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
    <div id="main-wrapper">

        <?php include("./include/nav_header.php"); ?>
        <?php include("./include/header.php"); ?>
        <?php include("./include/sidebar.php"); ?>

        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <div class="container-fluid">
                <div class="page-titles">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo URL; ?>/produtos">Produtos</a></li>
                        <li class="breadcrumb-item active" id="info-produto" data-cod="<?php echo $codProduto; ?>" data-produto="<?php echo $nomeProduto; ?>" data-estoque-min="<?php echo $min; ?>" data-estoque-ideal="<?php echo $ideal; ?>" data-unidade="<?php echo $unidadeCru; ?>" data-estoque-atual="<?php echo $atual; ?>" ><a>Nº <span id="cod-produto"><?php echo $codProduto; ?></span></a></li>
                    </ol>
                </div>
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="d-flex align-items-center mb-md-0 mb-4 mt-2">
                                            <div>
                                                <h4 class="fs-20 text-black font-w600 estoque-produto"><?php echo $exibe->produto; ?></h4>
                                                <span class="fs-14">ID: <?php echo $codProduto; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col">
                                                <span>Estoque Min.</span>
                                                <h3 class="m-b-0 estoque-min"><?php echo $min.$unidade; ?></h3>
                                            </div>
                                            <div class="col">
                                                <span>Estoque Ideal</span>
                                                <h3 class="m-b-0 estoque-ideal"><?php echo $ideal.$unidade; ?></h3>
                                            </div>
                                            <div class="col">
                                                <span>Estoque Atual</span>
                                                <h3 class="m-b-0 estoque-atual"><?php echo $atual.$unidade; ?></h3>
                                            </div>
                                            <div class="col-md-12 mt-3 estoque-status">
                                                <div class="progress <?php echo $bgl; ?>" style="height: 10px;">
                                                    <div class="progress-bar progress-animated <?php echo $bg; ?>"
                                                        style="width: <?php echo $status; ?>%;" role="progressbar">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="text-center estoque-alerta">
                                            <?php echo $alerta; ?>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="dropdown mt-3 mr-3 text-right">
                                            <button type="button" class="btn btn-primary sharp light"
                                                data-toggle="dropdown"
                                                style="height: auto; border-radius: 50px; padding: .7rem;">
                                                <svg width="30px" height="30px" viewBox="0 0 24 24" version="1.1">
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <rect x="0" y="0" width="24" height="24"></rect>
                                                        <circle fill="#000000" cx="5" cy="12" r="2"></circle>
                                                        <circle fill="#000000" cx="12" cy="12" r="2"></circle>
                                                        <circle fill="#000000" cx="19" cy="12" r="2"></circle>
                                                    </g>
                                                </svg>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item py-4 cPointer" data-toggle="modal" data-target="#editProduto">Editar</a>
                                                <a class="dropdown-item py-4 cPointer deleteProduto">Excluir</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header border-0 pb-0">
										<h4 class="fs-20 font-w600">Historico de compras</h4>
									</div>
                                    <div class="card-body">
                                        <div id="timeline" class="widget-timeline dz-scroll height600">
                                            <ul class="timeline">
                                                <?php         
                                                $conta = 0;

                                                $sql = 'SELECT * FROM compras GROUP BY cod ORDER BY dataId DESC';
                                                $resultado = $conexaoAdmin->prepare($sql);
                                                $resultado->execute();
                                                $contar = $resultado->rowCount();

                                                if($contar > 0):
                                                    $color = 'dark';
                                                    while($exibe = $resultado->fetch(PDO::FETCH_OBJ)){
                                                        $fornecedor = $exibe->fornecedor;

                                                        $sqlF = "SELECT * FROM fornecedor WHERE id='$fornecedor'";
                                                        $resultadoF = $conexaoAdmin->prepare($sqlF);	
                                                        $resultadoF->execute();
                                                        $resultadoF->rowCount();
                                                        $exibeF = $resultadoF->fetch(PDO::FETCH_OBJ);

                                                        $codCompra = $exibe->cod;

                                                        $sql2 = 'SELECT * FROM compras WHERE cod="'.$codCompra.'" AND produto="'.$codProduto.'"';
                                                        $resultado2 = $conexaoAdmin->prepare($sql2);
                                                        $resultado2->execute();
                                                        $contar2 = $resultado2->rowCount();

                                                        if($contar2 > 0):
                                                            $conta++;

                                                            if($color == 'dark'):
                                                                $color = 'primary';
                                                                $text = 'text-primary';
                                                            elseif($color == 'primary'):
                                                                $color = 'dark';
                                                                $text = 'text-secondary';
                                                            endif;
                                                            ?>
                                                            <li class="mb-4">
                                                                <div class="timeline-badge <?php echo $color; ?>"></div>
                                                                <a class="timeline-panel text-muted" href="<?php echo URL.'/compra/'.$codCompra; ?>">
                                                                    <h5 class="mb-3"><strong><?php echo $exibeF->fornecedor; ?></strong> <small>#<?php echo $codCompra; ?></small></h5>
                                                            <?php
                                                            while($exibe2 = $resultado2->fetch(PDO::FETCH_OBJ)){
                                                                $marca = $exibe2->marca;
                                                                $qtd = $exibe2->qtd;
                                                                ?>
                                                                    <h6 class="mb-1 ml-3"><strong class="<?php echo $text; ?>">x<?php echo $exibe2->qtd.$unidade; ?></strong>. <?php echo $nomeProduto.' '.$exibe2->marca; ?> = <strong class="<?php echo $text; ?>"><?php echo numeroParaReal($exibe2->valor_total); ?></strong></h6>
                                                                <?php
                                                            }
                                                            ?>
                                                                    <span class="mt-3 mb-0"><?php echo $exibe->data_compra; ?></span>
                                                                </a>
                                                            </li>
                                                            <?php
                                                        endif;
                                                    }//While
                                                    if($conta == 0):
                                                        ?>
                                                        <div class="text-center">
                                                            Não encontramos registros de compra deste produto.
                                                        </div>
                                                        <?php
                                                    endif;
                                                else:
                                                ?>
                                                <div class="text-center">
                                                    Não encontramos registros de compra deste produto.
                                                </div>
                                                <?php
                                                endif;
                                                ?>
                                            </ul>
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
        <?php include("./include/modal_order.php"); ?>

        <?php include("./include/modal_produto.php"); ?>

        <!--**********************************
            Footer start
        ***********************************-->
        <div class="footer">
            <div class="copyright">
                <p>Copyright © Designed &amp; Developed by <a href="http://dexignzone.com/"
                        target="_blank">DexignZone</a> 2021</p>
            </div>
        </div>
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
    <?php include("./include/js.php"); ?>

    <script>
        var hoje = "<?php $diaAtual = date('d/m/Y'); echo($diaAtual); ?>";

        
    </script>

    <script src="<?php echo URL; ?>/js/produto.js"></script>
</body>

</html>