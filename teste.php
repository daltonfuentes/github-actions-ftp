<?php
ob_start();
session_start();



require_once("./conexao/conexao_hostgator.php");


$dateAtual = date_format(date_create(),"YmdHis");

echo $dateAtualGmt.'<br>'.$dateAtual;

exit();


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
<body>

    <div class="content-body m-0 p-0">
        <div class="container-fluid" >
            <div>
                <h4 class="mb-0">TESTE POLLING <span class="badge light badge-warning ml-2 timer">00:00:<span class="segundos">30</span></span></h4>
                <hr>
            </div>
            <div class="row">
                <div id="box-retorno" class="col-6">
                    <p class="mb-0 subtitle text-success">Inicio!</p>
                </div>
                <div id="box-html" class="col-6">

                </div>
            </div>
            
        </div>
    </div>
    

    <?php include("include/js.php"); ?>

    <script type="text/javascript">
        function temporizador() {
            if(contador > 1){
                setTimeout(temporizador,1000);
            }else{
                window.onbeforeunload = null;
            }

            if(contador < 10){
                $('span.segundos').text('0'+contador);
            }else{
                $('span.segundos').text(contador);
            }
            contador--;
        };

        $(document).ready(function() {
            function fazPolling() {
                $.ajax({
                    type : 'POST',
                    url  : './conexao/ifood_api.php',
                    data : { polling: true },
                    dataType: 'json',
                    beforeSend: function() {
                        
                    },
                    success :  function(retorno){
                        var html = '<p class="mb-0 subtitle">'+retorno.code+' - '+retorno.mensagem+'</p>';
                        $("#box-retorno").prepend(html);
                    },
                    complete: function() {
                        contador = 30;
                        temporizador();
                        setTimeout(fazPolling, 30000);
                    }
                });
            };
            fazPolling();

            function refreshStatusIfood() {
                $.ajax({
                    type : 'POST',
                    url  : './conexao/ifood_api.php',
                    data : { status_ifood: true },
                    dataType: 'json',
                    beforeSend: function() {
                        
                    },
                    success :  function(retorno){
                        if(retorno.code == 200){
                            $("#box-html").html(retorno.html);
                            console.log(retorno.state+' / '+retorno.title+' / '+retorno.subtitle);
                        }else{
                            console.log(retorno.code);
                        }
                        
                    },
                    complete: function() {
                        setTimeout(refreshStatusIfood, 30000);
                    }
                });
            };
            refreshStatusIfood();
        });
    </script>
</body>
</html>
