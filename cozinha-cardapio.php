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
        <div class="content-body">
            <div class="container-fluid">
                <div class="row mb-5">
                    <div class="col-auto mr-auto">
                        <h3 class="text-black mb-0 pt-2 mt-1">Cardapio</h3>
                        <p class="mb-0">Itens do cardapio e suas variações.</p>
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
                    
                    <div class="dropdown col-auto">
                        <button type="button" class="btn btn-primary d-flex noBorderFocus h-100 pt-3 shadow-sm" data-toggle="dropdown">
                            <span class="" style="font-size: 18px; padding-top: 7px;"><i class="fa fa-plus"></i> Adicionar</span>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item cPointer py-4 fs-16" data-target="#addCategoria" data-toggle="modal">Categoria</a>
                            <a class="dropdown-item cPointer py-4 fs-16" data-target="#addCardapioCozinha" data-toggle="modal">Item</a>
                        </div>
                    </div>
                </div>
                <div class="row box-cardapio">
                    <?php            
                    $sql = 'SELECT * FROM cozinha_cardapio WHERE ativo="true" GROUP BY cod ORDER BY nome ASC';
                    $resultado = $conexaoAdmin->prepare($sql);
                    $resultado->execute();
                    $contar = $resultado->rowCount();

                    if($contar > 0):

                        while($exibe = $resultado->fetch(PDO::FETCH_OBJ)){
                            $cod_categoria = $exibe->categoria;
                                        
                            $sql2 = "SELECT categoria FROM categoria_cardapio WHERE cod='$cod_categoria' GROUP BY cod";
                            $resultado2 = $conexaoAdmin->prepare($sql2);
                            $resultado2->execute();
                            $contar2 = $resultado2->rowCount();

                            if($contar2 > 0):
                                $exibe2 = $resultado2->fetch(PDO::FETCH_OBJ);
                            endif;
                    ?>
                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 cardapio-item animate__fadeIn animate__animated">
                                <span class="d-none palavras-chaves"><?php echo $exibe2->categoria.' '.$exibe->nome; ?></span>
                                <div class="card cPointer cardapio-item-body shadow-sm" data-cod="<?php echo $exibe->cod; ?>">
                                    <div class="card-body d-flex p-0">
                                        <div class="div-img-quadrado rounded-top" style="background-image: url('upload/cardapio/<?php echo $exibe->img; ?>');"></div>
                                    </div>
                                    <div class="card-footer p-0">
                                        <div class="">
                                            <div class="new-arrival-content text-center mt-2 px-3 py-3">
                                                <h4 class="fs-18"><a><?php echo $exibe->nome; ?></a></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php
                        }//While
                    else:
                    ?>
                        <div class="col-12">
                            <span class="">Sem itens cadastrados.</span>
                        </div>
                    <?php
                    endif;
                    
                    ?>
                </div>


            </div>
        </div>
        <!--**********************************
            Content body end
        ***********************************-->

        <!-- Add Order -->
        <?php include("include/modal_order.php"); ?>

        <?php include("include/modal_cozinha-cardapio.php"); ?>
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
    var nSelect = 0;
    $(document).on("click", ".addLinha", function (event) {
        nSelect = nSelect+1;
        $(this).closest('.table-responsive').find(".tbody-cozinha-cardapio").children("tr.item").last().after('<tr class="item animate__animated animate__fadeInDown"><td style="width: 60%;"><div class="form-group mb-0"><select class="select-valida select-produto-'+nSelect+'" name="select-produto"><option class="py-3 px-2 border border-light" value="">Escolha o ingrediente</option>' + 
        <?php            
        $sql = 'SELECT * FROM producao GROUP BY cod_receita';
        $resultado = $conexaoAdmin->prepare($sql);
        $resultado->execute();
        $contar = $resultado->rowCount();

        if($contar > 0):

            while($exibe = $resultado->fetch(PDO::FETCH_OBJ)){
                $cod = $exibe->cod_receita;

                $sql2 = "SELECT * FROM receitas WHERE cod='$cod'";
                $resultado2 = $conexaoAdmin->prepare($sql2);
                $resultado2->execute();
                $contar2 = $resultado2->rowCount();
                
                if($contar2 > 0):
                    $exibe2 = $resultado2->fetch(PDO::FETCH_OBJ);
                
        ?>
                    '<option class="py-3 px-2 border border-light" data-unidade-medida="g" data-tipo="1" value="<?php echo $cod; ?>"><?php echo $exibe2->receita; ?></option>' +
        <?php
                endif;
            }//While
        else:
            //Informar que não existem parceiros cadastrados - ERRO-M
        endif;

        $sql = 'SELECT * FROM estoque';
        try{
            $resultado = $conexaoAdmin->prepare($sql);
            $resultado->execute();
            $contar = $resultado->rowCount();

            if($contar > 0){

                while($exibe = $resultado->fetch(PDO::FETCH_OBJ)){
        ?>
        '<option class="py-3 px-2 border border-light" data-unidade-medida="<?php echo $exibe->unidade; ?>" data-tipo="2" value="<?php echo $exibe->cod; ?>"><?php echo $exibe->produto; ?></option>' +
        <?php
                }//While
            }else{
            //Informar que não existem parceiros cadastrados - ERRO-M
            }
        }catch(PDOException $erro){
        echo $erro;
        }
        ?> '</select></div></td><td style="width: 25%;"><div class="form-group mb-0"><input name="qtd" class="form-control input-border" disabled type="text"></div></td><td style="width: 5%; vertical-align: initial;"><div class="d-flex pt-3"><a class="btn btn-primary light shadow btn-xs sharp delete"><i class="fa fa-trash"></i></a></div></td></tr>');

        $(".select-produto-"+nSelect).select2();

        var altura = $("#editVariacao .modal-body form").height();
        $("#editVariacao .modal-body").animate({ scrollTop: altura }, 800);
    });

    $(".select-produto").select2();

    $(document).on("click", ".variacao .editar", function (event) {
        var origin = $(this).closest('div.modal');

        var variacao = $(this).closest('div.variacao').attr('data-variacao');
        var nome_variacao = $(this).closest('div.variacao').find('.nome-variacao').text();
        $('#editVariacao').attr("data-variacao", variacao);

        $('#editVariacao .tbody-cozinha-cardapio .item').remove();
        $('#editVariacao').find('input[name="nome"]').val('');

        var linhas = $(this).closest('div.variacao').find('.item').length;

        if(linhas == 0){
            $('#editVariacao .tbody-cozinha-cardapio').html('<tr class="item animate__animated animate__fadeInDown"><td style="width: 60%;"><div class="form-group mb-0"><select class="select-produto select-valida" name="select-produto"><option class="py-3 px-2 border border-light" value="">Escolha o ingrediente</option>' + 
            <?php            
            $sql = 'SELECT * FROM producao GROUP BY cod_receita';
            $resultado = $conexaoAdmin->prepare($sql);
            $resultado->execute();
            $contar = $resultado->rowCount();

            if($contar > 0):

                while($exibe = $resultado->fetch(PDO::FETCH_OBJ)){
                    $cod = $exibe->cod_receita;

                    $sql2 = "SELECT * FROM receitas WHERE cod='$cod'";
                    $resultado2 = $conexaoAdmin->prepare($sql2);
                    $resultado2->execute();
                    $contar2 = $resultado2->rowCount();
                    
                    if($contar2 > 0):
                        $exibe2 = $resultado2->fetch(PDO::FETCH_OBJ);
                    
            ?>
                        '<option class="py-3 px-2 border border-light" data-unidade-medida="g" data-tipo="1" value="<?php echo $cod; ?>"><?php echo $exibe2->receita; ?></option>' +
            <?php
                    endif;
                }//While
            else:
                //Informar que não existem parceiros cadastrados - ERRO-M
            endif;

            $sql = 'SELECT * FROM estoque';
            try{
                $resultado = $conexaoAdmin->prepare($sql);
                $resultado->execute();
                $contar = $resultado->rowCount();

                if($contar > 0){

                    while($exibe = $resultado->fetch(PDO::FETCH_OBJ)){
            ?>
            '<option class="py-3 px-2 border border-light" data-unidade-medida="<?php echo $exibe->unidade; ?>" data-tipo="2" value="<?php echo $exibe->cod; ?>"><?php echo $exibe->produto; ?></option>' +
            <?php
                    }//While
                }else{
                //Informar que não existem parceiros cadastrados - ERRO-M
                }
            }catch(PDOException $erro){
            echo $erro;
            }
            ?> '</select></div></td><td style="width: 25%;"><div class="form-group mb-0"><input name="qtd" class="form-control input-border" disabled type="text"></div></td></tr>');
        }else{
            $('#editVariacao').find('input[name="nome"]').val(nome_variacao);
            var i = 1;
            $(this).closest('div.variacao').find('.item').each(function () {
                var cod = $(this).attr('data-cod');
                var tipo = $(this).attr('data-tipo');
                var nome = $(this).find('.ingrediente').text();
                var qtd = $(this).find('.quantidade').attr('data-qtd');
                var un = $(this).find('.quantidade').attr('data-un');
                var qtdF = $(this).find('.quantidade').text();

                var result = qtd.includes('.');
                if(result == false && un == 'g'){
                    qtd = qtd+'.0';
                }

                if (un == 'un') {
                    qtdF = qtd+' '+un;
                }else if(un == 'g' || un == 'ml') {
                    qtdF = qtd+un;
                }

                if(i == 1){
                    $('#editVariacao .tbody-cozinha-cardapio').html('<tr class="item animate__animated animate__fadeInDown"><td style="width: 60%;"><div class="form-group mb-0"><select class="select-produto select-valida" name="select-produto"><option class="py-3 px-2 border border-light" data-unidade-medida="'+un+'" data-tipo="'+tipo+'" value="'+cod+'">'+nome+'</option>' + 
                    <?php            
                    $sql = 'SELECT * FROM producao GROUP BY cod_receita';
                    $resultado = $conexaoAdmin->prepare($sql);
                    $resultado->execute();
                    $contar = $resultado->rowCount();
        
                    if($contar > 0):
        
                        while($exibe = $resultado->fetch(PDO::FETCH_OBJ)){
                            $cod = $exibe->cod_receita;
        
                            $sql2 = "SELECT * FROM receitas WHERE cod='$cod'";
                            $resultado2 = $conexaoAdmin->prepare($sql2);
                            $resultado2->execute();
                            $contar2 = $resultado2->rowCount();
                            
                            if($contar2 > 0):
                                $exibe2 = $resultado2->fetch(PDO::FETCH_OBJ);
                            
                    ?>
                                '<option class="py-3 px-2 border border-light" data-unidade-medida="g" data-tipo="1" value="<?php echo $cod; ?>"><?php echo $exibe2->receita; ?></option>' +
                    <?php
                            endif;
                        }//While
                    else:
                        //Informar que não existem parceiros cadastrados - ERRO-M
                    endif;

                    $sql = 'SELECT * FROM estoque';
                    try{
                        $resultado = $conexaoAdmin->prepare($sql);
                        $resultado->execute();
                        $contar = $resultado->rowCount();

                        if($contar > 0){

                            while($exibe = $resultado->fetch(PDO::FETCH_OBJ)){
                    ?>
                    '<option class="py-3 px-2 border border-light" data-unidade-medida="<?php echo $exibe->unidade; ?>" data-tipo="2" value="<?php echo $exibe->cod; ?>"><?php echo $exibe->produto; ?></option>' +
                    <?php
                            }//While
                        }else{
                        //Informar que não existem parceiros cadastrados - ERRO-M
                        }
                    }catch(PDOException $erro){
                    echo $erro;
                    }
                    ?> '</select></div></td><td style="width: 25%;"><div class="form-group mb-0"><input name="qtd" class="form-control input-border qtd'+un+'" value="'+qtdF+'" type="text"></div></td></tr>');
                }else{
                    $('#editVariacao .tbody-cozinha-cardapio').children("tr.item").last().after('<tr class="item animate__animated animate__fadeInDown"><td style="width: 60%;"><div class="form-group mb-0"><select class="select-valida select-x-'+i+'" name="select-produto"><option class="py-3 px-2 border border-light" data-unidade-medida="'+un+'" data-tipo="'+tipo+'" value="'+cod+'">'+nome+'</option>' + 
                    <?php            
                    $sql = 'SELECT * FROM producao GROUP BY cod_receita';
                    $resultado = $conexaoAdmin->prepare($sql);
                    $resultado->execute();
                    $contar = $resultado->rowCount();
        
                    if($contar > 0):
        
                        while($exibe = $resultado->fetch(PDO::FETCH_OBJ)){
                            $cod = $exibe->cod_receita;
        
                            $sql2 = "SELECT * FROM receitas WHERE cod='$cod'";
                            $resultado2 = $conexaoAdmin->prepare($sql2);
                            $resultado2->execute();
                            $contar2 = $resultado2->rowCount();
                            
                            if($contar2 > 0):
                                $exibe2 = $resultado2->fetch(PDO::FETCH_OBJ);
                            
                    ?>
                                '<option class="py-3 px-2 border border-light" data-unidade-medida="g" data-tipo="1" value="<?php echo $cod; ?>"><?php echo $exibe2->receita; ?></option>' +
                    <?php
                            endif;
                        }//While
                    else:
                        //Informar que não existem parceiros cadastrados - ERRO-M
                    endif;

                    $sql = 'SELECT * FROM estoque';
                    try{
                        $resultado = $conexaoAdmin->prepare($sql);
                        $resultado->execute();
                        $contar = $resultado->rowCount();

                        if($contar > 0){

                            while($exibe = $resultado->fetch(PDO::FETCH_OBJ)){
                    ?>
                    '<option class="py-3 px-2 border border-light" data-unidade-medida="<?php echo $exibe->unidade; ?>" data-tipo="2" value="<?php echo $exibe->cod; ?>"><?php echo $exibe->produto; ?></option>' +
                    <?php
                            }//While
                        }else{
                        //Informar que não existem parceiros cadastrados - ERRO-M
                        }
                    }catch(PDOException $erro){
                    echo $erro;
                    }
                    ?> '</select></div></td><td style="width: 25%;"><div class="form-group mb-0"><input name="qtd" class="form-control input-border qtd'+un+'" value="'+qtdF+'" type="text"></div></td><td style="width: 5%; vertical-align: initial;"><div class="d-flex pt-3"><a class="btn btn-primary light shadow btn-xs sharp delete"><i class="fa fa-trash"></i></a></div></td></tr>');

                    $(".select-x-"+i).select2();
                }
                ++i;
            });
        }

        $('#editVariacao .qtdun').mask("#0.000 un", { reverse: true });
        $('#editVariacao .qtdg').mask("#0.0g", { reverse: true });
        $('#editVariacao .qtdml').mask("#00ml", { reverse: true });

        $("#editVariacao .select-produto").select2();

        $('#editVariacao').modal('show');
    });

    
    </script>

    <script src="./js/cozinha-cardapio.js"></script>
</body>

</html>