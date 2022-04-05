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
                        <h3 class="text-black mb-0 pt-2 mt-1">Receitas</h3>
                        <p class="mb-0">Aqui estão as receitas cadastradas.</p>
                    </div>
                    <div class="col-3 bg-white btn py-2 shadow-sm">
                        <div class="row">
                            <div class="col-2 text-center pt-2">
                                <i class="far fa-search fs-30 mt-1 text-primary"></i>
                            </div>
                            <div class="col-10 px-2">
                                <div class="form-group m-0">
                                    <input name="pesquesa" type="text" class="form-control p-0 border-0 text-dark placeholder-dark" style="font-size: 16px; font-weight: 600;" placeholder="Procure aqui" id="inputBusca">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-primary d-flex noBorderFocus h-100 pt-3 shadow-sm" data-target="#addReceita" data-toggle="modal">
                            <span class="" style="font-size: 18px; padding-top: 7px;"><i class="fa fa-plus"></i> Adicionar</span>
                        </button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 config-col"> <!-- NAO MEXER - AQUI É CARREGADO AS RECEITAS -->
                        <div class="row box-receitas"></div>
                    </div>
                    <div class="col-6 d-none box-card-receita">
                        <div class="card card-edit card-edit-receita-1 animate__bounceInRight animate__animated d-none"  style="height: auto !important;" data-cod="">
                            <div class="card-header d-flex p-0">
                                <div class="dg-01 div-img-custon-02 rounded-top">
                                    <div class="div-img-custon-02-int rounded-top text-center"></div>
                                    <button class="position-absolute sharp btn btn-white tp-btn border border-white noBorderFocus close-card" style="top: 12px; right: 15px; transform: rotate(90deg); border-width: 2px !important;" type="button">
                                        <i class="fas fa-times text-white"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-header pb-4 d-block">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="product-detail-content">
                                            <!--Product details-->
                                            <div class="pr text-center">
                                                <h2 class="mb-1"><span></span></h2>
                                            </div>
                                        </div>
                                    </div>  
                                </div>
                                
                            </div>
                            <div class="card-body pt-1 pb-0">
                                <div class="row revealing">
                                    <div class="col-12">
                                        <div class="table-responsive table-animate text-center">
                                            <table class="table table-responsive-md table-produtos mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>INGREDIENTE</th>
                                                        <th>QTD</th>                                               
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-center">
                            <button type="button" class="btn btn-rounded btn-dark light" data-toggle="modal" data-target="#addIngrediente"><span class="btn-icon-left text-dark"><i class="fa fa-plus"></i></span>Adicionar</button>
                            </div>
                        </div>
                        <div class="card card-edit card-edit-receita-2 animate__bounceInRight animate__animated d-none" style="height: auto !important;" data-cod="">
                            <div class="card-header d-flex p-0">
                                <div class="dg-01 div-img-custon-02 rounded-top">
                                    <div class="div-img-custon-02-int rounded-top text-center"></div>
                                    <button class="position-absolute sharp btn btn-white tp-btn border border-white noBorderFocus close-card" style="top: 12px; right: 15px; transform: rotate(90deg); border-width: 2px !important;" type="button">
                                        <i class="fas fa-times text-white"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-header pb-4 d-block">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="product-detail-content">
                                            <!--Product details-->
                                            <div class="pr text-center">
                                                <h2 class="mb-1"><span></span></h2>
                                            </div>
                                        </div>
                                    </div>  
                                </div>
                                
                            </div>
                            <div class="card-body pt-1 pb-0">
                                <div class="row revealing">
                                    <div class="col-12">
                                        <div class="table-responsive table-animate text-center">
                                            <table class="table table-responsive-md table-produtos mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>INGREDIENTE</th>
                                                        <th>QTD</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-center">
                            <button type="button" class="btn btn-rounded btn-dark light" data-toggle="modal" data-target="#addIngrediente"><span class="btn-icon-left text-dark"><i class="fa fa-plus"></i></span>Adicionar</button>
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

        <?php include("include/modal_receitas.php"); ?>
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
    $(function() {
		$("#input-upload-img").change(function (){
		var path = $(this).val();
		var fileName = path.replace(/^.*\\/, "");
		$("#fake-input-upload").val(fileName);
		});
	});


    $(".select-produto").select2();

    var nSelect = 0;
    $('.addLinha').click(function (event) {
        nSelect = nSelect+1;
        $(".tbody-receitas").children("tr.item").last().after('<tr class="item animate__animated animate__fadeInDown"><td style="width: 70%;" class="pr-2"><div class="form-group"><select class="select-valida select-produto-'+nSelect+'" name="select-produto"><option class="py-3 px-2 border border-light" value="">Escolha o ingrediente</option>' + <?php            
                                                                $sql = 'SELECT * FROM estoque';
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
                                                                ?>'</select></div></td><td style="width: 30%;" class="pl-2"><div class="form-group"><input name="qtd" class="qtd form-control input-border" type="text" disabled></div></td><td style="width: 5%; vertical-align: initial;"><div class="d-flex pt-3"><a class="btn btn-primary light shadow btn-xs sharp delete"><i class="fa fa-trash"></i></a></div></td></tr>');

        $(".select-produto-"+nSelect).select2();
        $('.qtd-new').mask('00000000000000000000');
    });
    </script>

    <script src="./js/receitas.js"></script>
</body>

</html>