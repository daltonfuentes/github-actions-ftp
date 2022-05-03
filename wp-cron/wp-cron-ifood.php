<?php
ob_start();
session_start();

require("./conexao/functions.php");

$retorno = array();

$outToken = accessToken();
$accessToken = $outToken['accessToken'];

$outState = merchantStatus($accessToken);

if($outState['code'] != 200):
    errorLog('Cron efetuado corretamente: <hr>Code: '.$outState['code'].'-'.$outState['mensagem']);
    exit();
endif;

$state = $outState['state'];
$title  = $outState['title'];
$subtitle  = $outState['subtitle'];

errorLog('Cron efetuado corretamente: <hr>Code: '.$outState['code'].'<br>State: '.$state.'<br> Title: '.$title.'<br> Subtitle: '.$subtitle);
exit();