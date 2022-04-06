<?php

$_POST['new_session'] = true;

if (isset($_POST['new_session']) && $_POST['new_session'] == true) :
    require_once("./conexao/functions.php");
    $usuario = 1;

    $retorno = array();
 
    $outToken = accessToken($usuario);

    if(empty($outToken['accessToken'])):
        $retorno['erro'] = $outToken['erro'];
        $retorno['mensagem']  = $outToken['mensagem'];
        echo json_encode($retorno);
        exit();
    else:
        $accessToken = $outToken['accessToken'];
    endif;

    $outState = merchantStatus($accessToken);

    if(!empty($outState['erro'])):
        $retorno['erro'] = $outState['erro'];
        $retorno['mensagem']  = $outState['mensagem'];
        echo json_encode($retorno);
        exit();
    else:
        $state = $outState['state'];
    endif;

    echo $state;

endif;
