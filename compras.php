<?php
ob_start();
session_start();

require_once("conexao/conexao.php");

function paraMoeda($valor){
	$recebido = $valor;
	$transformado = str_replace(".",",",$recebido);
	$transformado = "R$ ".$transformado;
	return ($transformado);
}
?>



<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Sego - Restaurant Bootstrap Admin Dashboard</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">

    <?php include("include/css.php"); ?>

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

        <?php include("include/nav_header.php"); ?>
        <?php include("include/header.php"); ?>
        <?php include("include/sidebar.php"); ?>

        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <div class="container-fluid">
                <div class="d-sm-flex d-block">
                    <div class="mr-auto mb-sm-4 mb-3">
                        <h3 class="text-black mb-0 pt-2">Compras</h3>
                        <p class="mb-0">Aqui esta o historico de compras realizadas.</p>
                    </div>
                    <div class="mr-auto mb-sm-4 mb-3 d-none">
                        <button type="button" class="btn btn-lg btn-rounded btn-outline-primary light d-flex noBorderFocus input-modi-1" data-target="#addCompra" data-toggle="modal"><span class="btn-icon-left text-primary"><i class="fa fa-plus"></i></span>Adicionar</button>
                    </div>
					<div class="dropdown custom-dropdown mb-sm-4 mb-3">
						<div class="btn btn-sm btn-primary light d-flex align-items-center svg-btn" role="button" data-toggle="dropdown" aria-expanded="false">
							<svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg"><g><path d="M22.4281 2.856H21.8681V1.428C21.8681 0.56 21.2801 0 20.4401 0C19.6001 0 19.0121 0.56 19.0121 1.428V2.856H9.71606V1.428C9.71606 0.56 9.15606 0 8.28806 0C7.42006 0 6.86006 0.56 6.86006 1.428V2.856H5.57206C2.85606 2.856 0.560059 5.152 0.560059 7.868V23.016C0.560059 25.732 2.85606 28.028 5.57206 28.028H22.4281C25.1441 28.028 27.4401 25.732 27.4401 23.016V7.868C27.4401 5.152 25.1441 2.856 22.4281 2.856ZM5.57206 5.712H22.4281C23.5761 5.712 24.5841 6.72 24.5841 7.868V9.856H3.41606V7.868C3.41606 6.72 4.42406 5.712 5.57206 5.712ZM22.4281 25.144H5.57206C4.42406 25.144 3.41606 24.136 3.41606 22.988V12.712H24.5561V22.988C24.5841 24.136 23.5761 25.144 22.4281 25.144Z" fill="#2F4CDD"></path></g></svg>
							<div class="text-left ml-3">
								<span class="d-block fs-16">Filtrar Periodo</span>
								<small class="d-block fs-13 where-data" data-where="30">Últimos 30 dias</small>
							</div>
							<i class="fa fa-angle-down scale5 ml-3"></i>
						</div>
						<div class="dropdown-menu dropdown-menu-right">
							<a class="dropdown-item cPointer py-3 condicao-data" data-where='30'>Últimos 30 dias</a>
							<a class="dropdown-item cPointer py-3 condicao-data" data-where='60'>Últimos 60 dias</a>
                            <a class="dropdown-item cPointer py-3 condicao-data" data-where='90'>Últimos 90 dias</a>
                            <a class="dropdown-item cPointer py-3 condicao-data" data-where='all'>Todo o periodo</a>
						</div>
					</div>
                    <div class="d-block ml-3">
                        <button type="button" class="btn btn-lg btn-primary noBorderFocus" data-target="#addCompra" data-toggle="modal">
                            <span class="" style="font-size: 20px;"><i class="fa fa-plus"></i> Adicionar</span>
                        </button>
                    </div>
                    
				</div>
                <div class="row">
                    <div class="col-lg-8">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body tab-content pb-0">
                                        <div class="tab-pane fade show active">
                                            <div class="loadmore-content" id="sellingItemsContent">
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer border-0">
                                        <nav class="">
                                            <ul class="pagination style-1 mb-0">
                                                <li class="page-item page-indicator">
                                                    <a class="page-link page-link-previous" href="javascript:void(0)">
                                                        <i class="la la-angle-left"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <ul class="ul-pagination">
                                                    </ul>
                                                </li>
                                                <li class="page-item page-indicator">
                                                    <a class="page-link page-link-next" href="javascript:void(0)">
                                                        <i class="la la-angle-right"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </nav>
                                    </div>
                                    <div id="pagination-container" class="d-none"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="row">
                            <div class="col-12">
                                <div class="widget-stat card bg-primary">
                                    <div class="card-body p-4">
                                        <div class="media">
                                            <span class="mr-3">
                                                <i class="fas fa-dollar-sign"></i>
                                            </span>
                                            <div class="media-body text-white text-right">
                                                <p class="mb-1" style="line-height: 1;">Valor Gasto <br><small class="desc_valor_total_compras">Ultimos 30 dias</small></p>
                                                <h3 class="text-white valor_total_compras"></h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body pb-0">
                                        <div class="row">
                                            <div class="col">
                                                <h5 class="text-primary">Mês atual</h5>
                                                <span class="text-primary"><small><strong>x</strong> mês anterior</small></span>
                                            </div>
                                            <div class="col text-right">
                                                <h4 class="text-primary"><span class="dif_chart_i"></span> <span class="valor_mes_atual_chart">R$ 0,00</span></h4>
                                                <span class="text-primary"><span class="dif_chart"></span>(<span class="per_chart"></span>)</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="chart-wrapper pt-4">
                                        <canvas id="chart_compras_1"></canvas>
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

        <?php include("include/modal_compras.php"); ?>
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
    <?php include("include/js.php"); ?>

    <script>

        var hoje = "<?php $diaAtual = date('d/m/Y'); echo($diaAtual); ?>";

        

        var nSelect = 0;

        $('.addLinha').click(function (event) {
            nSelect = nSelect+1;
            $(".tbody-fornecedor").children("tr.item").last().after('<tr class="item animate__animated animate__fadeInDown"><td style="width: 30%;"><div class="form-group"><select class="select-valida select-produto-'+nSelect+'" name="select-produto"><option class="py-3 px-2 border border-light" value="">Escolha o produto</option>' + <?php            
                                                                    $sql = 'SELECT * FROM estoque WHERE visible = "1" ';
                                                                    try{
                                                                        $resultado = $conexaoAdmin->prepare($sql);
                                                                        $resultado->execute();
                                                                        $contar = $resultado->rowCount();

                                                                        if($contar > 0){

                                                                            while($exibe = $resultado->fetch(PDO::FETCH_OBJ)){
                                                                    ?>
                                                                            '<option class="py-3 px-2 border border-light" data-un="<?php echo $exibe->unidade; ?>" value="<?php echo $exibe->cod; ?>"><?php echo $exibe->produto; ?></option>' +
                                                                    <?php
                                                                            }//While
                                                                        }else{
                                                                        //Informar que não existem parceiros cadastrados - ERRO-M
                                                                        }
                                                                    }catch(PDOException $erro){
                                                                    echo $erro;
                                                                    }
                                                                    ?>'</select></div></td><td style="width: 25%;"><div class="form-group"><input name="marca" class="form-control input-border" type="text"></div></td><td style="width: 15%;"><div class="form-group"><input name="qtd" class="form-control input-border" disabled type="text"></div><td style="width: 15%;"><div class="form-group"><input name="validade" class="date-new form-control input-border"></div></td><td style="width: 15%;"><div class="form-group"><input name="preco" class="form-control input-border maskMoney"></div></td><td style="width: 5%; vertical-align: initial;"><div class="d-flex pt-3"><a class="btn btn-primary light shadow btn-xs sharp delete"><i class="fa fa-trash"></i></a></div></td></tr>');

            $(".select-produto-"+nSelect).select2();
            $('.maskMoney').maskMoney({
                thousands: '.',
                decimal: ',',
                prefix: 'R$ '
            });
            $('.date-new').mask('00/00/0000');
            $('.qtd-new').mask('00000000000000000000');

            if(jQuery('.default-select').length > 0 ){
			jQuery('.default-select').selectpicker();
		}
        });
        $(".select-produto").select2();
        $(".select-fornecedor").select2();
    </script>

    <script src="./js/estoque.js"></script>
</body>

</html>