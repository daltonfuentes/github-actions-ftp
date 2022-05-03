<?php
$a = 1+1;

exit();
require("../conexao/functions.php");

$in = '2022-05-01T05:35:00.000Z';

echo dateDisplay($in);
exit();

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