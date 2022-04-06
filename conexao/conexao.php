<?php
$conexaoAdmin = new PDO(   'mysql:host=localhost; dbname=sweetconfetty', 'root', '', 
            array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
                PDO::ATTR_PERSISTENT => false,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
            )
        );

//$conexao = new PDO(   'mysql:host=br376.hostgator.com.br; dbname=volca246_visualine2', 'volca246_dalton', '3105caix', 
//            array(
//                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
//                PDO::ATTR_PERSISTENT => false,
//                PDO::ATTR_EMULATE_PREPARES => false,
//                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
//            )
//        );

class database extends PDO
{
    public function __construct($dsn='mysql:host=localhost;dbname=sweetconfetty',
                                $user='root',
                                $pass='')
    {
        parent::__construct($dsn, $user, $pass);
    }
}

date_default_timezone_set('America/Sao_Paulo');

function reaisParaSql($money) {
    $money = str_replace("R$", "", $money);
    $money = str_replace(".", "", $money);
    $money = str_replace(",", ".", $money);
    $money = floatval($money);
    return($money);
}

function dataParaSql($date) {
    list($dia, $mes, $ano) = explode("/", $date);
    $newDate = $ano.$mes.$dia;
    return($newDate);
}

function numeroParaReal($n){
    $valor = number_format($n,2,",",".");
    $valor = "R$ ".$valor;
    return($valor);
};

function calculaRecebivel($venda, $taxa) {
    $recebivel = $venda-($venda*($taxa/100));
    return($recebivel);
};


function comparaDatas01($date){
    $hAtual = date("Y-m-d H:i:s");
    $data_inicio = new DateTime($date);
    $hoje = new DateTime($hAtual);
    $dateInterval = $data_inicio->diff($hoje);
    $difSeg = $dateInterval->s; $difMin = $dateInterval->i; $difHor = $dateInterval->h; $difDia = $dateInterval->d; $difMes = $dateInterval->m; $difAno = $dateInterval->y;
    if($difAno != 0):
        if($difAno == 1):
            $diferenca = $difAno." Ano"; 
        else:
            $diferenca = $difAno." Anos";
        endif;
    elseif($difMes != 0):
        if($difMes == 1):
            $diferenca = $difMes." Mês"; 
        else:
            $diferenca = $difMes." Meses";
        endif;
    elseif($difDia != 0):
        if($difDia == 1):
            $diferenca = $difDia." Dia"; 
        else:
            $diferenca = $difDia." Dias";
        endif;
    elseif($difHor != 0):
        if($difHor == 1):
            $diferenca = $difHor." Hora"; 
        else:
            $diferenca = $difHor." Horas";
        endif;
    elseif($difMin != 0): 
        $diferenca = $difMin." Min";
    elseif($difSeg != 0): 
        $diferenca = $difSeg." Seg";
    else:
        $diferenca = 'ERRO';
    endif;
    return($diferenca);
};

function numeroParaMes($n){
    if($n == '01'):
        $nome = 'Janeiro';
    elseif($n == '02'):
        $nome = 'Fevereiro';
    elseif($n == '03'):
        $nome = 'Março';
    elseif($n == '04'):
        $nome = 'Abril';
    elseif($n == '05'):
        $nome = 'Maio';
    elseif($n == '06'):
        $nome = 'Junho';
    elseif($n == '07'):
        $nome = 'Julho';
    elseif($n == '08'):
        $nome = 'Agosto';
    elseif($n == '09'):
        $nome = 'Setembro';
    elseif($n == '10'):
        $nome = 'Outubro';
    elseif($n == '11'):
        $nome = 'Novembro';
    elseif($n == '12'):
        $nome = 'Dezembro';
    endif;
    return($nome);
};

define("URL", "https://painel.sweetconfetty.com.br");

?>