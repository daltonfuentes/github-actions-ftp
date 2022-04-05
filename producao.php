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
        <div class="content-body" style="position: relative;">
            <div class="container-fluid loarder-new animate__fadeInNew animate__animated d-none">
                <div class="loader">Loading...</div>
            </div>
            <div class="container-fluid">
                <div class="row mb-5">
                    <div class="col-auto mr-auto">
                        <h3 class="text-black mb-0 pt-2 mt-1">Procução</h3>
                        <p class="mb-0">Aqui estão as produções das receitas.</p>
                    </div>
                    <div class="col-3 bg-white btn py-2 shadow-sm">
                        <div class="row">
                            <div class="col-2 text-center pt-2">
                                <i class="far fa-search fs-30 mt-1 text-primary"></i>
                            </div>
                            <div class="col-10 px-2">
                                <div class="form-group m-0">
                                    <input name="pesquisa" type="text" class="form-control p-0 border-0 text-dark placeholder-dark" style="font-size: 16px; font-weight: 600;" placeholder="Procure aqui" id="inputBusca">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-primary d-flex noBorderFocus h-100 pt-3 shadow-sm" data-target="#addProducao" data-toggle="modal">
                            <span class="" style="font-size: 18px; padding-top: 7px;"><i class="fa fa-plus"></i> Adicionar</span>
                        </button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-9">
                        <div class="row">
                            <div class="col-12">
                                <div class="card border-0 pb-0">
                                    <div class="card-body tab-content pb-0">
                                        <div class="tab-pane fade show active">
                                            <div class="loadmore-content widget-media" id="sellingItemsContent">
                                                <ul class="timeline">

                                                </ul>
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
                    <div class="col-lg-3">
                        <div class="row">
                        <div class="col-12">
						<div class="card trending-menus">
							<div class="card-header d-sm-flex d-block pb-0 border-0">
								<div>
									<h4 class="text-black fs-20">Mais produzidos</h4>
								</div>
							</div>
							<div class="card-body" id="dailyMenus">
								
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

        <div class="modal fade modal-fullscreen fullscreen-md" id="addProducao">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Informar produção de receita</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="basic-form">
                            <form id="form_add_producao" class="form-new-color"> 
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label>Receita</label>
                                        <select class="select-produto select-valida" name="receita">
                                            <option class="py-3 px-2 border border-light" value="">Escolha a receita</option>
                                            <?php            
                                            $sql = 'SELECT * FROM receitas GROUP BY cod ORDER BY receita ASC';
                                            try{
                                                $resultado = $conexaoAdmin->prepare($sql);
                                                $resultado->execute();
                                                $contar = $resultado->rowCount();

                                                if($contar > 0){

                                                    while($exibe = $resultado->fetch(PDO::FETCH_OBJ)){
                                            ?>
                                            <option class="py-3 px-2 border border-light" value="<?php echo $exibe->cod; ?>"> <?php echo $exibe->receita; ?></option>
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
                                        <label>Data</label>
                                        <input name="data" type="text" class="date form-control input-border">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Quantidade</label>
                                        <select name="qtd" class="form-control default-select">
                                            <option class="py-4" value="1">1x</option>
                                            <option class="py-4" value="2">2x</option>
                                            <option class="py-4" value="3">3x</option>
                                            <option class="py-4" value="4">4x</option>
                                            <option class="py-4" value="5">5x</option>
                                            <option class="py-4" value="6">6x</option>
                                            <option class="py-4" value="7">7x</option>
                                            <option class="py-4" value="8">8x</option>
                                            <option class="py-4" value="9">9x</option>
                                            <option class="py-4" value="10">10x</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Rendimento (g)</label>
                                        <input name="rendimento" type="text" class="peso form-control input-border">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Valor Total</label>
                                        <input name="valorTotal" type="text" class="border-0 form-control" value="R$ 0,00" disabled>
                                    </div>
                                    
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-primary salvar">Salvar</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade modal-fullscreen fullscreen-md" id="editProducao">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar produção #<span class="idReceita"></span></h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="basic-form">
                            <form id="form_edit_producao" class="form-new-color"> 
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label>Receita</label>
                                        <input name="receita" type="text" class="form-control input-border bg-light cNoDrop" disabled>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Data</label>
                                        <input name="data" type="text" class="date form-control input-border">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Quantidade</label>
                                        <input name="qtd" type="text" class="form-control input-border bg-light cNoDrop" disabled>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Rendimento (g)</label>
                                        <input name="rendimento" type="text" class="peso form-control input-border">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Valor Total</label>
                                        <input name="valor" type="text" class="form-control input-border bg-light cNoDrop" disabled>
                                    </div>
                                    
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-primary salvar">Salvar</button>
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
    <?php include("include/js.php"); ?>

    <script>
    $(".select-produto").select2();
    </script>

    <script src="./js/producao.js"></script>
</body>

</html>