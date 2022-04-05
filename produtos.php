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
                        <button type="button" class="btn btn-lg btn-rounded btn-outline-primary light d-flex noBorderFocus input-modi-1" data-target="#addProduto" data-toggle="modal"><span class="btn-icon-left text-primary"><i class="fa fa-plus"></i></span>Adicionar</button>
                    </div>
				</div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table header-border verticle-middle">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Produto</th>
                                                <th scope="col">Estoque</th>                                                
                                            </tr>
                                        </thead>
                                        <tbody class="tbody-estoque-lista">
                                            <?php            
                                            $sql = 'SELECT * FROM estoque WHERE visible = "1" ORDER BY alert DESC, estoque_atual ASC, cod DESC';
                                            try{
                                                $resultado = $conexaoAdmin->prepare($sql);
                                                $resultado->execute();
                                                $contar = $resultado->rowCount();

                                                if($contar > 0){

                                                    while($exibe = $resultado->fetch(PDO::FETCH_OBJ)){
                                                        $ideal = floatval($exibe->estoque_ideal);
                                                        $atual = floatval($exibe->estoque_atual);
                                                        $status = round(100*($atual/$ideal));    
                                                        $min = floatval($exibe->estoque_min);
                                                        if($atual>= $ideal):
                                                            $bgl="bgl-primary";
                                                            $bg="bg-primary";
                                                            $badge="badge-primary";
                                                            $alerta = "";
                                                        elseif($atual < $ideal && $atual > $min):
                                                            $bgl="bgl-warning";
                                                            $bg="bg-warning";
                                                            $badge="badge-warning";
                                                            $alerta = '';
                                                        else:
                                                            $bgl="bgl-warning";
                                                            $bg="bg-warning";
                                                            $badge="badge-warning";
                                                            $alerta = '<i class="fas fa-exclamation-triangle text-warning animate__animated animate__flash animate__infinite animate__slow"></i>';
                                                        endif;

                                                        $unidade = $exibe->unidade;
                                                        if($unidade == 'un'):
                                                            $space = ' ';
                                                        else:
                                                            $space = '';
                                                        endif;

                                                        $popover = 'data-toggle="popover" data-placement="top" data-html="true" data-content="Estoque mínimo: '.$min.$space.$unidade.'<br /> Estoque ideal: '.$ideal.$space.$unidade.'<br /> Estoque atual: '.$atual.$space.$unidade.'"';
                                            ?>
                                            <tr class="estoque-item" data-cod="<?php echo $exibe->cod; ?>">
                                                <td class="clickavel cPointer" scope="row"><strong><?php echo $exibe->cod; ?></strong></td>
                                                <td class="clickavel cPointer" style="width: 40%;"><?php echo $exibe->produto; ?> <?php echo $alerta; ?></td>
                                                <td class="clickavel cPointer" style="width: 35%;" <?php echo $popover; ?>>
                                                    <div class="progress <?php echo $bgl; ?>" style="height: 10px;">
                                                        <div class="progress-bar progress-animated <?php echo $bg; ?>" style="width: <?php echo $status; ?>%;"
                                                            role="progressbar">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="clickavel cPointer">
                                                    <span class="badge <?php echo $badge; ?> light"><?php echo $status; ?>%</span>
                                                </td>
                                                <td style="width: 5%;">
                                                    <a class="btn btn-outline-secondary light sharp delete"><i class="fa fa-trash"></i></a>
                                                </td>
                                            </tr>
                                            <?php
                                                    }//While
                                                }else{
                                            ?>
                                            <tr>
                                                <td class="text-center pt-5" colspan="3">
                                                    Não existem produtos cadastrados.
                                                </td>
                                            </tr> 
                                            <?php
                                                }
                                            }catch(PDOException $erro){
                                            echo $erro;
                                            }
                                            ?>
                                        </tbody>
                                    </table>
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

        <?php include("include/modal_estoque.php"); ?>
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

    <script type="text/javascript">
        $(function () {
			$('[data-toggle="popover"]').popover({ trigger: "hover" });
		});
    </script>

    <script src="./js/estoque.js"></script>
</body>

</html>