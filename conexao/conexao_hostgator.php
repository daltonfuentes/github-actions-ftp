<?php

$dbHost = 'br376.hostgator.com.br'; // host
$db     = 'volca246_dashboard_confeitaria'; // nome do banco
$dbUser = 'volca246_dalton'; // usuário
$dbPass = '2008caix'; // criada aqui a variável para a senha, atribua o valor

$conexao = new PDO("mysql:host=$dbHost;dbname=$db", $dbUser, $dbPass);

date_default_timezone_set('America/Sao_Paulo');

$merchantApiHost = 'https://merchant-api.ifood.com.br';
