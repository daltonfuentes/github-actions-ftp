<?php

$_POST['new_session'] = true;

if (isset($_POST['new_session']) && $_POST['new_session'] == true) :
    require_once("./conexao/conexao_hostgator.php");
    require_once("./conexao/functions.php");
    $usuario = 1;

    $retorno = array();

    $outToken = accessToken($usuario);

    if(!$outToken['accessToken']):
        $retorno['erro'] = $outToken['erro'];
        $retorno['mensagem']  = $outToken['mensagem'];
        echo json_encode($retorno);
        exit();
    else:
        $accessToken = $outToken['accessToken'];
    endif;

    echo $accessToken;
endif;
