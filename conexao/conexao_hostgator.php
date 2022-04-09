<?php

$conexao = new PDO(   'mysql:host=br376.hostgator.com.br; dbname=volca246_dashboard_confeitaria', 'volca246_dalton', '2008caix', 
            array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
                PDO::ATTR_PERSISTENT => false,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
            )
        );

date_default_timezone_set('America/Sao_Paulo');

$merchantApiHost = 'https://merchant-api.ifood.com.br';