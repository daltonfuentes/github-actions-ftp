<?php

$tipo_conexao = $_SERVER['HTTP_HOST'];
 
if (($tipo_conexao == 'localhost') || ($tipo_conexao == '192.168.100.4')):
	// para uso local
	$dbHost = 'localhost'; // host
    $db     = 'sweetconfetty'; // nome do banco
    $dbUser = 'root'; // usuário
    $dbPass = ''; // criada aqui a variável para a senha, atribua o valor
else:
	// para uso externo
	$dbHost = 'br376.hostgator.com.br'; // host
    $db     = 'volca246_dashboard_confeitaria'; // nome do banco
    $dbUser = 'volca246_dalton'; // usuário
    $dbPass = '2008caix'; // criada aqui a variável para a senha, atribua o valor
endif;

$conexao = new PDO("mysql:host=$dbHost;dbname=$db", $dbUser, $dbPass);

date_default_timezone_set("Etc/GMT+0");
$dateAtualGmt = date_format(date_create(),"YmdHis");

date_default_timezone_set('America/Sao_Paulo');

$merchantApiHost = 'https://merchant-api.ifood.com.br';