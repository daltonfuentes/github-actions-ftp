<?php
ob_start();
session_start();

require_once("../conexao/conexao.php");

$url = $_SERVER['REQUEST_URI'];
$url = explode("/", $url);
$cod_cardapio = $url[count($url)-1];

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

    <?php include("./../include/css.php"); ?>

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

        <?php include("./../include/nav_header.php"); ?>
        <?php include("./../include/header.php"); ?>
        <?php include("./../include/sidebar.php"); ?>

        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <div class="container-fluid">
          
      
            </div>
        </div>
        <!--**********************************
            Content body end
        ***********************************-->

        <!-- Add Order -->
        <?php include("./../include/modal_order.php"); ?>


        
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
    <?php include("./../include/js.php"); ?>

    <script>
        $(document).ready(function () {
            
        });        
    </script>

    <script src="<?php echo URL; ?>/js/cozinha-cardapio-profile.js"></script>
</body>

</html>