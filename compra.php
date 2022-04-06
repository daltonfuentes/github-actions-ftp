<?php
ob_start();
session_start();

require_once("conexao/conexao.php");

$url = $_SERVER['REQUEST_URI'];
$url = explode("/", $url);
$codCompra = $url[count($url)-1];

$sqlValida = "SELECT * FROM compras WHERE cod='$codCompra'";
$resultadoValida = $conexaoAdmin->prepare($sqlValida);	
$resultadoValida->execute();
$contarValida = $resultadoValida->rowCount();

if($contarValida == 0):
    header("Location: ../compras");
    exit();
endif;


function paraMoeda($valor){
	$recebido = $valor;
	$transformado = str_replace(".",",",$recebido);
	$transformado = "R$ ".$transformado;
	return ($transformado);
}

$sql2 = "SELECT *, count(id) total, sum(valor_total) soma FROM compras WHERE cod='$codCompra'";
$resultado2 = $conexaoAdmin->prepare($sql2);	
$resultado2->execute();
$resultado2->rowCount();
$exibe2 = $resultado2->fetch(PDO::FETCH_OBJ);

$dataAdd = $exibe2->dataAdd;
$fornecedor = $exibe2->fornecedor;


$sql1 = "SELECT * FROM fornecedor WHERE id='$fornecedor'";
$resultado1 = $conexaoAdmin->prepare($sql1);	
$resultado1->execute();
$resultado1->rowCount();
$exibe1 = $resultado1->fetch(PDO::FETCH_OBJ);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Sego - Restaurant Bootstrap Admin Dashboard</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon.png">

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
						<li class="breadcrumb-item"><a href="../compras">Compras</a></li>
						<li class="breadcrumb-item active" id="info-compra" data-cod="<?php echo $codCompra; ?>" data-fornecedor="<?php echo $exibe2->fornecedor; ?>" data-data-compra="<?php echo $exibe2->data_compra; ?>" data-data-add="<?php echo $exibe2->dataAdd; ?>"><a>Nº <span id="cod-compra"><?php echo $codCompra; ?></span></a></li>
					</ol>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header pb-4">
                                <div class="row">
                                    <div class="col-xl-2 col-lg-2  col-md-4 ">
                                        <img class="img-fluid rounded mr-3 img-fornecedor" src="../upload/fornecedor/<?php echo $exibe1->img; ?>" alt="<?php echo $exibe1->fornecedor; ?>">
                                    </div>
                                    <!--Tab slider End-->
                                    <div class="col-xl-6 col-lg-6  col-md-6 col-xxl-7 col-sm-12">
                                        <div class="product-detail-content">
                                            <!--Product details-->
                                            <div class="new-arrival-content pr pt-4">
                                                <h2 class="nome-fornecedor"><?php echo $exibe1->fornecedor; ?></h2>
												<div class="d-table mb-2">
													<p class="price float-left d-block soma-compra"><?php echo numeroParaReal($exibe2->soma); ?></p>
                                                </div>
                                                <p>Data da compra: <span class="item data-compra"> <?php echo $exibe2->data_compra; ?></span></p>
                                                <p>Adicionado á: <span class="item"><?php echo comparaDatas01($dataAdd); ?></span></p>
                                                <p>Itens: <span class="item qtd-itens"><?php echo $exibe2->total; ?></span></p>
                                            </div>
                
                                        </div>
                                    </div>  
                                </div>
                                <!--<button type="button" class="btn btn-rounded btn-dark light d-flex" data-toggle="modal" data-target="#addItem"><span class="btn-icon-left text-dark"><i class="fa fa-plus"></i></span>Adicionar</button>s-->

                                <div class="dropdown ml-3 d-flex">
                                    <button type="button" class="btn btn-primary sharp light" data-toggle="dropdown" style="height: auto; border-radius: 50px; padding: .7rem;">
                                        <svg width="30px" height="30px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item py-4 cPointer editarCompra" data-toggle="modal" data-target="#editCompra">Editar</a>
                                        <a class="dropdown-item py-4 cPointer" data-toggle="modal" data-target="#addItem">Adicionar</a>
                                        <a class="dropdown-item py-4 cPointer deleteCompra">Excluir</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-3">
                                <div class="row revealing">
                                    <div class="col-12">
                                        <div class="table-responsive table-animate">
                                            <table class="table table-responsive-md table-produtos">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>PRODUTO</th>
                                                        <th>MARCA</th>
                                                        <th>VALIDADE</th>
                                                        <th>QTD</th>
                                                        <th>VALOR UN.</th>
                                                        <th>VALOR TOTAL</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php            
                                                    $sql = "SELECT * FROM compras WHERE cod='$codCompra' ORDER BY id DESC"; 
                                                    try{
                                                        $resultado = $conexaoAdmin->prepare($sql);
                                                        $resultado->execute();
                                                        $contar = $resultado->rowCount();

                                                        if($contar > 0){

                                                            $i=0;
                                                            while($exibe = $resultado->fetch(PDO::FETCH_OBJ)){
                                                                $produto = $exibe->produto;
                                                                $sqlF = "SELECT * FROM estoque WHERE cod='$produto'";
                                                                $resultadoF = $conexaoAdmin->prepare($sqlF);	
                                                                $resultadoF->execute();
                                                                $resultadoF->rowCount();
                                                                $exibeF = $resultadoF->fetch(PDO::FETCH_OBJ);

                                                                if($i < 10):
                                                                    $class = "";
                                                                else:
                                                                    $class = "td-oculto";
                                                                endif;
                                                    ?>
                                                    <tr class="<?php echo $class; ?> item-compra animate__animated" data-id="<?php echo $exibe->id; ?>">
                                                        <td><?php echo $exibe->id; ?></td>
                                                        <td class="produto"><?php echo $exibeF->produto; ?></td>
                                                        <td class="marca"><?php echo $exibe->marca; ?></td>
                                                        <td class="validade"><?php echo $exibe->validade; ?></td>
                                                        <td class="qtdItem" data-un="<?php echo $exibeF->unidade; ?>" data-info="<?php echo $exibe->qtd; ?>"><?php echo $exibe->qtd." ".$exibeF->unidade; ?></td>
                                                        <td class="valor-un"><?php echo numeroParaReal($exibe->valor_un); ?></td>
                                                        <td class="valor-total"><?php echo numeroParaReal($exibe->valor_total); ?></td>
                                                        <td class="w-10">
                                                            <div class="d-flex">
                                                                <a class="btn btn-primary light shadow sharp mr-3 editItem"><i class="fa fa-pencil"></i></a>
                                                                <a class="btn btn-danger light shadow sharp apagaItem"><i class="fa fa-trash"></i></a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                            $i++;
                                                            }//While
                                                        }else{
                                                        //Informar que não existem parceiros cadastrados - ERRO-M
                                                        }
                                                    }catch(PDOException $erro){
                                                    echo $erro;
                                                    }
                                                    ?>
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-12 final-rolagem">
                                        <div class="text-center bg-white pt-3">
                                            <a data-class="td-oculto" class="btn-link revelador cPointer">View more <i class="fa fa-angle-down ml-2 scale-2"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
					<!-- review -->

                </div>
            </div>
        </div>
        <!--**********************************
            Content body end
        ***********************************-->

        <!-- Add Order -->
        <?php include("./include/modal_order.php"); ?>

        <div class="modal fade modal-fullscreen fullscreen-lg" id="editItem">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Item - #<span class="id-item"></span> - <span class="produto-item"></span></h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="basic-form">
                            <form>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Marca</label>
                                        <input name="marca" type="text" class="form-control new-color-input input-border">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Validade</label>
                                        <input name="validade" type="text" class="form-control new-color-input input-border date">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Quantidade</label>
                                        <input name="qtd" type="text" data-un="" class="form-control new-color-input input-border qtd">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Valor Total</label>
                                        <input name="valorTotal" type="text" class="form-control new-color-input input-border maskMoney">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-primary salvar">Salvar Mudanças</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade modal-fullscreen fullscreen-lg" id="addItem">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Adicionar Produto</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="basic-form">
                            <form>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label>Produto</label>
                                        <select class="select-produto" name="produto">
                                            <option class="py-3 px-2 border border-light" value="">Escolha o produto</option>
                                            <?php            
                                            $sql3 = 'SELECT * FROM estoque';
                                            try{
                                                $resultado3 = $conexaoAdmin->prepare($sql3);
                                                $resultado3->execute();
                                                $contar3 = $resultado3->rowCount();

                                                if($contar3 > 0){

                                                    while($exibe3 = $resultado3->fetch(PDO::FETCH_OBJ)){
                                            ?>
                                                    <option class="py-3 px-2 border border-light" data-un="<?php echo $exibe3->unidade; ?>" value="<?php echo $exibe3->cod; ?>"><?php echo $exibe3->produto; ?></option>
                                            <?php
                                                    }//While
                                                }else{
                                                //Informar que não existem parceiros cadastrados - ERRO-M
                                                }
                                            }catch(PDOException $erro){
                                            echo $erro;
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Marca</label>
                                        <input name="marca" type="text" class="form-control new-color-input input-border">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Validade</label>
                                        <input name="validade" type="text" class="form-control new-color-input input-border date">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Quantidade</label>
                                        <input name="qtd" type="text" class="form-control new-color-input input-border" disabled>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Valor Total</label>
                                        <input name="valorTotal" type="text" class="form-control new-color-input input-border maskMoney">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-primary salvar">Salvar Mudanças</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade modal-fullscreen fullscreen-md" id="editCompra">
            <div class="modal-dialog modal modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar compra - Nº <span class="id-compra"><?php echo $codCompra; ?></span></h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="basic-form">
                            <form>
                                <div class="form-row">
                                    <div class="form-group col-md-8">
                                        <label>Fornecedor</label>
                                        <select class="select-fornecedor" name="fornecedor">
                                            <option class="py-3 px-2 border border-light" selected value="<?php echo $fornecedor; ?>"><?php echo $exibe1->fornecedor; ?></option>
                                            <?php            
                                            $sql = 'SELECT * FROM fornecedor WHERE id != "'.$fornecedor.'"';
                                            try{
                                                $resultado = $conexaoAdmin->prepare($sql);
                                                $resultado->execute();
                                                $contar = $resultado->rowCount();

                                                if($contar > 0):

                                                    while($exibe = $resultado->fetch(PDO::FETCH_OBJ)){
                                            ?>
                                                    <option class="py-3 px-2 border border-light" value="<?php echo $exibe->id; ?>"><?php echo $exibe->fornecedor; ?></option>
                                            <?php
                                                    }//While
                                                else:
                                                //Informar que não existem parceiros cadastrados - ERRO-M
                                                endif;
                                            }catch(PDOException $erro){
                                            echo $erro;
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Data da compra</label>
                                        <input name="data" type="text" class="form-control new-color-input input-border date" value="<?php echo $exibe2->data_compra; ?>">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-primary salvar">Salvar Mudanças</button>
                    </div>
                </div>
            </div>
        </div>
        
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

        $(document).ready(function () {
            
        });

        $(".select-produto").select2();
        $(".select-fornecedor").select2();
        
    </script>

    <script src="../js/compra.js"></script>
</body>

</html>