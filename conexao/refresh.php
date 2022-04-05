<?php
ob_start();
session_start();

if(isset($_GET['refresh_estoque']) && $_GET['refresh_estoque'] == '1'):
    require_once("conexao.php");
    
    $sql = "SELECT produto, sum(qtd) qtd FROM compras GROUP BY produto";
    $resultado = $conexaoAdmin->prepare($sql);	
    $resultado->execute();
    $contar = $resultado->rowCount();

    if($contar > 0):
        $erro = 0;
        
        while($exibe = $resultado->fetch(PDO::FETCH_OBJ)){
            $contSaida = 0;
            
            $produto = $exibe->produto;       
            $qtd_entrada = $exibe->qtd;

            // APARTIR DAQUI, FAZ O TRAJETO PARA PROCURAR SAIDAS EM PRODUÇÃO
            $sql2 = "SELECT cod_receita, sum(qtd_producao) qtd_producao FROM producao GROUP BY cod_receita"; // CONSULTA TODAS AS RECEITAS QUE FORAM PRODUZIDAS E A QUANTIDADE TOTAL DE CADA RECEITA
            $resultado2 = $conexaoAdmin->prepare($sql2);	
            $resultado2->execute();
            $contar2 = $resultado2->rowCount();

            if($contar2 > 0): // TENDO ALGUMA RECEITA PRODUZIDA, PROCURA SE ALGUMAS DAS RECEITAS PRODUZIDA UTILIZA O INGREDIENTE X
                while($exibe2 = $resultado2->fetch(PDO::FETCH_OBJ)){ // FAZ WHILE POR CADA RECEITA
                    $receita = $exibe2->cod_receita;
                    $qtd_producao = $exibe2->qtd_producao;

                    $sql3 = "SELECT sum(qtd) qtd_receita FROM receitas WHERE cod='$receita' && produto='$produto' GROUP BY cod"; // TENTA SABER SE DENTRO DA RECEITA EXISTE PRODUTO X, E SUA QUANTIDADE NA RECEITA
                    $resultado3 = $conexaoAdmin->prepare($sql3);	
                    $resultado3->execute();
                    $contar3 = $resultado3->rowCount();

                    if($contar3 > 0): // EXISTINDO QUER DIZER QUE EXISTE SAIDA DO PRODUTO X
                        while($exibe3 = $resultado3->fetch(PDO::FETCH_OBJ)){
                            $qtd_receita = $exibe3->qtd_receita;
                            $qtd_saida_1 = $qtd_receita*$qtd_producao;
                        }
                    else:
                        // NÃO TEM PRODUÇÕES CADASTRADAS
                        $qtd_saida_1 = 0;
                    endif;

                    $contSaida = $contSaida+$qtd_saida_1; 
                }
            else:
                // NÃO TEM PRODUÇÕES CADASTRADAS
                $qtd_saida_1 = 0;
            endif;

            $estoque_atual = $qtd_entrada-$contSaida;

            if($estoque_atual<0): $estoque_atual=0; endif;

            $sql1 = 'UPDATE estoque SET estoque_atual=:estoque_atual WHERE cod=:cod';
            $stmt1 = $conexaoAdmin->prepare($sql1);
            $stmt1->bindParam(':cod', $produto);
            $stmt1->bindParam(':estoque_atual', $estoque_atual);
            $resposta1 = $stmt1->execute();

            if(!$resposta1):
                $erro++;
            endif;
        }

        if($erro != 0):
            $retorno['erro'] = 'RFEx2';
            echo json_encode($retorno);
            exit();
        else:
            $retorno['erro'] = '0';
            echo json_encode($retorno);
            exit();
        endif;
    else:
        $retorno['erro'] = 'RFEx1';
        echo json_encode($retorno);
        exit();
    endif;
endif;