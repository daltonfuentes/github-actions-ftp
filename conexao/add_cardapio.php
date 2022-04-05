<?php
ob_start();
session_start();


if(isset($_POST['monta_modal_cardapio']) && $_POST['monta_modal_cardapio'] == '1'):
    require_once("conexao.php");

    $retorno = array();
    $custos = array();
    $embalagens = array();
    $taxas = array();

    $cod  = (isset($_POST['cod'])) ? $_POST['cod'] : '' ;

    if(empty($cod)):
        $retorno['erro']     = '1';
        $retorno['mensagem'] = 'Tivemos um erro interno e nao foi possivel prosseguir!';
        echo json_encode($retorno);
        exit();
    endif;

    $sql = "SELECT * FROM cozinha_cardapio WHERE ativo='true' AND cod='$cod' AND valor_ifood IS NULL AND valor_whats IS NULL GROUP BY variacao";
    $resultado = $conexaoAdmin->prepare($sql);
    $resultado->execute();
    $contar = $resultado->rowCount();

    if($contar > 0): // NUMERO DE VARIACOES DESSE PRODUTO
        
        $retorno['variacoes'] = $contar;

        $sql9 = "SELECT * FROM embalagem_delivery WHERE visible='true'";
        $resultado9 = $conexaoAdmin->prepare($sql9);
        $resultado9->execute();
        $contar9 = $resultado9->rowCount();

        if($contar9 > 0):
            $custos['embalagem_delivery'] = 0;
            while($exibe9 = $resultado9->fetch(PDO::FETCH_OBJ)){
                $sql10 = "SELECT *, sum(valor_un) somaValorUn FROM (SELECT valor_un FROM compras WHERE produto='$cod_ingrediente' ORDER BY id DESC LIMIT 2) AS subquery";
                $resultado10 = $conexaoAdmin->prepare($sql10);
                $resultado10->execute();
                $contar10 = $resultado10->rowCount();

                if($contar10 > 0):
                    $sql11 = "SELECT * FROM compras WHERE produto='$cod_ingrediente' ORDER BY id DESC LIMIT 2";
                    $resultado11 = $conexaoAdmin->prepare($sql11);
                    $resultado11->execute();
                    $contar11 = $resultado11->rowCount();

                    $exibe10 = $resultado10->fetch(PDO::FETCH_OBJ);

                    $custoGrama = $exibe10->somaValorUn/$contar11;

                    $cutoIngrediente = $qtd_ingrediente*$custoGrama;

                    $custos['embalagem_delivery'] = $custos['embalagem_delivery']+$cutoIngrediente;
                else:
                    $retorno['erro']     = '1';
                    $retorno['mensagem'] = 'Tivemos um erro interno e nao foi possivel prosseguir!';
                    echo json_encode($retorno);
                    exit();
                endif;
            }
        else:
            $custos['embalagem_delivery'] = 0;
        endif;


        $sql12 = "SELECT * FROM taxas_plataforma WHERE plataforma='ifood'";
        $resultado12 = $conexaoAdmin->prepare($sql12);
        $resultado12->execute();
        $contar12 = $resultado12->rowCount();

        if($contar12 > 0):
            $exibe12 = $resultado12->fetch(PDO::FETCH_OBJ);

            $ifood_comissao = $exibe12->comissao;
            $ifood_antecipacao = $exibe12->antecipacao;
            $ifood_pagamento_online = $exibe12->visa_master_credito;

            $taxa_ifood_online = $ifood_comissao+$ifood_antecipacao+$ifood_pagamento_online;
            $taxa_ifood_loja = $ifood_comissao;
        else:
            $retorno['erro']     = '1';
            $retorno['mensagem'] = 'Tivemos um erro interno e nao foi possivel prosseguir!';
            echo json_encode($retorno);
            exit();
        endif;

        $sql13 = "SELECT * FROM taxas_plataforma WHERE plataforma='internet'";
        $resultado13 = $conexaoAdmin->prepare($sql13);
        $resultado13->execute();
        $contar13 = $resultado13->rowCount();

        if($contar13 > 0):
            $exibe13 = $resultado13->fetch(PDO::FETCH_OBJ);

            $taxa_loja_credito = $exibe13->visa_master_credito;
            $taxa_loja_debito = $exibe13->visa_master_debito;
        else:
            $retorno['erro']     = '1';
            $retorno['mensagem'] = 'Tivemos um erro interno e nao foi possivel prosseguir!';
            echo json_encode($retorno);
            exit();
        endif;

        $retorno['itens'] = '';

        while($exibe = $resultado->fetch(PDO::FETCH_OBJ)){ //FAZ WHILE POR CADA VARIACAO

            $categoria = $exibe->categoria;

            $sql5 = "SELECT * FROM categoria_cardapio WHERE cod='$categoria' AND tipo_ingrediente IS NOT NULL AND cod_ingrediente IS NOT NULL AND qtd_ingrediente IS NOT NULL";
            $resultado5 = $conexaoAdmin->prepare($sql5);
            $resultado5->execute();
            $contar5 = $resultado5->rowCount();

            if($contar5 > 0):
                $custos['embalagens'] = 0;

                while($exibe5 = $resultado5->fetch(PDO::FETCH_OBJ)){
                    $cod_ingrediente = $exibe5->cod_ingrediente;
                    $tipo_ingrediente = $exibe5->tipo_ingrediente;
                    $qtd_ingrediente = $exibe5->qtd_ingrediente;

                    // -------------------------------- //
                    // PEGA O CUSTO DE CADA INGREDIENTE //
                    // -------------------------------- //

                    if($tipo_ingrediente == 1): // CASO SEJA RECEITA/PRODUCAO
                        $sql6 = "SELECT *, sum(rendimento) rendimentoTotal, sum(valor_total) valorTotal FROM (SELECT rendimento, valor_total FROM producao WHERE cod_receita='$cod_ingrediente' ORDER BY id DESC LIMIT 2) AS subquery";
                        $resultado6 = $conexaoAdmin->prepare($sql6);
                        $resultado6->execute();
                        $contar6 = $resultado6->rowCount();

                        if($contar6 > 0):
                            $exibe6 = $resultado6->fetch(PDO::FETCH_OBJ);

                            $custoGrama = $exibe6->valorTotal/$exibe6->rendimentoTotal;

                            $cutoIngrediente = $qtd_ingrediente*$custoGrama;

                            $custos['embalagens'] = $custos['embalagens']+$cutoIngrediente;
                        else:
                            $retorno['erro']     = '1';
                            $retorno['mensagem'] = 'Tivemos um erro interno e nao foi possivel prosseguir!';
                            echo json_encode($retorno);
                            exit();
                        endif;
                    elseif($tipo_ingrediente == 2): // CASO SEJA PRODUTO

                        $sql7 = "SELECT *, sum(valor_un) somaValorUn FROM (SELECT valor_un FROM compras WHERE produto='$cod_ingrediente' ORDER BY id DESC LIMIT 2) AS subquery";
                        $resultado7 = $conexaoAdmin->prepare($sql7);
                        $resultado7->execute();
                        $contar7 = $resultado7->rowCount();

                        if($contar7 > 0):
                            $sql8 = "SELECT * FROM compras WHERE produto='$cod_ingrediente' ORDER BY id DESC LIMIT 2";
                            $resultado8 = $conexaoAdmin->prepare($sql8);
                            $resultado8->execute();
                            $contar8 = $resultado8->rowCount();

                            $exibe7 = $resultado7->fetch(PDO::FETCH_OBJ);

                            $custoGrama = $exibe7->somaValorUn/$contar8;

                            $cutoIngrediente = $qtd_ingrediente*$custoGrama;

                            $custos['embalagens'] = $custos['embalagens']+$cutoIngrediente;
                        else:
                            $retorno['erro']     = '1';
                            $retorno['mensagem'] = 'Tivemos um erro interno e nao foi possivel prosseguir!';
                            echo json_encode($retorno);
                            exit();
                        endif;
                    else:
                        $retorno['erro']     = '1';
                        $retorno['mensagem'] = 'Tivemos um erro interno e nao foi possivel prosseguir!';
                        echo json_encode($retorno);
                        exit();
                    endif;
                }
            else:
                $custos['embalagens'] = 0;
            endif;

            $variacao = $exibe->variacao;

            $custos[$variacao] = 0;

            $sql2 = "SELECT * FROM cozinha_cardapio WHERE ativo='true' AND cod='$cod' AND variacao='$variacao' AND valor_ifood IS NULL AND valor_whats IS NULL"; //PEGA TODOS OS INGREDIENTES PARA TAL VARIACAO
            $resultado2 = $conexaoAdmin->prepare($sql2);
            $resultado2->execute();
            $contar2 = $resultado2->rowCount();

            while($exibe2 = $resultado2->fetch(PDO::FETCH_OBJ)){
            
                $cod_ingrediente = $exibe2->cod_ingrediente;
                $tipo_ingrediente = $exibe2->tipo_ingrediente;
                $qtd_ingrediente = $exibe2->qtd_ingrediente;

                // -------------------------------- //
                // PEGA O CUSTO DE CADA INGREDIENTE //
                // -------------------------------- //

                if($tipo_ingrediente == 1): // CASO SEJA RECEITA/PRODUCAO
                    $sql3 = "SELECT *, sum(rendimento) rendimentoTotal, sum(valor_total) valorTotal FROM (SELECT rendimento, valor_total FROM producao WHERE cod_receita='$cod_ingrediente' ORDER BY id DESC LIMIT 2) AS subquery";
                    $resultado3 = $conexaoAdmin->prepare($sql3);
                    $resultado3->execute();
                    $contar3 = $resultado3->rowCount();

                    if($contar3 > 0):
                        $exibe3 = $resultado3->fetch(PDO::FETCH_OBJ);

                        $custoGrama = $exibe3->valorTotal/$exibe3->rendimentoTotal;

                        $cutoIngrediente = $qtd_ingrediente*$custoGrama;

                        $custos[$variacao] = $custos[$variacao]+$cutoIngrediente;
                    else:
                        $retorno['erro']     = '1';
                        $retorno['mensagem'] = 'Tivemos um erro interno e nao foi possivel prosseguir!';
                        echo json_encode($retorno);
                        exit();
                    endif;
                elseif($tipo_ingrediente == 2): // CASO SEJA PRODUTO

                    $sql3 = "SELECT *, sum(valor_un) somaValorUn FROM (SELECT valor_un FROM compras WHERE produto='$cod_ingrediente' ORDER BY id DESC LIMIT 2) AS subquery";
                    $resultado3 = $conexaoAdmin->prepare($sql3);
                    $resultado3->execute();
                    $contar3 = $resultado3->rowCount();

                    if($contar3 > 0):
                        $sql4 = "SELECT * FROM compras WHERE produto='$cod_ingrediente' ORDER BY id DESC LIMIT 2";
                        $resultado4 = $conexaoAdmin->prepare($sql4);
                        $resultado4->execute();
                        $contar4 = $resultado4->rowCount();

                        $exibe3 = $resultado3->fetch(PDO::FETCH_OBJ);

                        $custoGrama = $exibe3->somaValorUn/$contar4;

                        $cutoIngrediente = $qtd_ingrediente*$custoGrama;

                        $custos[$variacao] = $custos[$variacao]+$cutoIngrediente;
                    else:
                        $retorno['erro']     = '1';
                        $retorno['mensagem'] = 'Tivemos um erro interno e nao foi possivel prosseguir!';
                        echo json_encode($retorno);
                        exit();
                    endif;
                else:
                    $retorno['erro']     = '1';
                    $retorno['mensagem'] = 'Tivemos um erro interno e nao foi possivel prosseguir!';
                    echo json_encode($retorno);
                    exit();
                endif;
            }
            
            $item = $exibe->nome;
            $nome_variacao = $exibe->nome_variacao;

            $valor_embalagens = $custos['embalagem_delivery']+$custos['embalagens'];

            $lucro = 0-($custos[$variacao]+$valor_embalagens);

            $custo_total = $custos[$variacao]+$valor_embalagens;
            $custo_total = number_format($custo_total,2);

            $retorno['itens'] = $retorno['itens'].'<div class="col-12 my-2 variacao animate__animated animate__fadeIn animate__slower" data-variacao="'.$variacao.'">
                <div class="card shadow">
                    <div class="card-header border-0 px-4 pt-3 pb-1">
                        <p class="font-gilroy-semibold text-terceiro f-17 mb-0">'.$item.'</p>
                        <p class="font-gilroy-bold text-quarta f-17 mb-0">'.$nome_variacao.'</p>
                    </div>
                    <div class="card-body faixa-valores valores-ifood bg-white px-4 py-3">
                        <div class="media">
                            <img class="img-fluid mr-2 mt-1" width="50" src="./images/logo_ifood.png" alt="ifood">
                            <div class="px-1">
                                <small class="font-gilroy-medium desc_valor">PRODUTO</small>
                                <h5 class="mb-0 font-gilroy-semibold valor_modal_cardapio text-terceiro">'.numeroParaReal($custos[$variacao]).'</h5>
                            </div>
                            <h5 class="px-1 mt-4">...........</h5>
                            <div class="px-1">
                                <small class="font-gilroy-medium desc_valor">EMBALAGENS</small>
                                <h5 class="mb-0 font-gilroy-semibold valor_modal_cardapio text-terceiro">'.numeroParaReal($valor_embalagens).'</h5>
                            </div>
                            <div class="media-footer media-footer-valor-variacao">
                                <small class="font-gilroy-medium desc_valor">VENDA</small>
                                <h5 class="mb-0" style="margin-top: -6px;">
                                    <input type="text" placeholder="R$ 0,00" data-taxa-online="'.$taxa_ifood_online.'" data-taxa-dinheiro="'.$taxa_ifood_loja.'" data-custo-total="'.$custo_total.'" class="maskMoney font-gilroy-bold input_valor_modal_cardapio text-quinta border-0 text-right ifood" style="width: 84.531px;" value="R$ 0,00">
                                </h5> 
                            </div>
                        </div>
                    </div>
                    <div class="card-body faixa-lucros lucros-ifood bg-faixa-footer px-4 py-2">
                        <div class="media">
                            <div class="pl-1 pr-4">
                                <img class="img-fluid mr-1" width="26" src="./images/icon_ifood.png" alt="mastercard">
                                <small class="font-gilroy-semibold footer-valor text-quinta recebivel-ifood">R$ 0,00</small>
                            </div>
                            <div class="pr-3">
                                <img class="img-fluid mr-1" width="26" src="./images/icon_pix.png" alt="maestro">
                                <small class="font-gilroy-semibold footer-valor text-quinta recebivel-pix">R$ 0,00</small>
                            </div>
                            <div class="">
                                <img class="img-fluid" width="26" src="./images/icon_dinheiro.png" alt="dinheiro">
                                <small class="font-gilroy-semibold footer-valor text-quinta recebivel-dinheiro">R$ 0,00</small>
                            </div>
                            <div class="media-footer media-footer-valor-variacao">
                                <small class="font-gilroy-medium footer-valor-2 text-quinta porcentagem-lucro"><span class="valor-porcentagem">0%</span> | <span class="valor-lucro">R$ 0,00 </span></small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body faixa-valores valores-whatsapp bg-white px-4 py-3">
                        <div class="media">
                            <img class="img-fluid mr-2 mt-1" width="50" src="./images/logo_whatsapp.png" alt="whatsapp">
                            <div class="px-1">
                                <small class="font-gilroy-medium desc_valor">PRODUTO</small>
                                <h5 class="mb-0 font-gilroy-semibold valor_modal_cardapio text-terceiro">'.numeroParaReal($custos[$variacao]).'</h5>
                            </div>
                            <h5 class="px-1 mt-4">...........</h5>
                            <div class="px-1">
                                <small class="font-gilroy-medium desc_valor">EMBALAGENS</small>
                                <h5 class="mb-0 font-gilroy-semibold valor_modal_cardapio text-terceiro">'.numeroParaReal($valor_embalagens).'</h5>
                            </div>
                            <div class="media-footer media-footer-valor-variacao">
                                <small class="font-gilroy-medium desc_valor">VENDA</small>
                                <h5 class="mb-0" style="margin-top: -6px;">
                                    <input type="text" placeholder="R$ 0,00" data-taxa-credito="'.$taxa_loja_credito.'" data-taxa-debito="'.$taxa_loja_debito.'" data-custo-total="'.$custo_total.'" class="maskMoney font-gilroy-bold input_valor_modal_cardapio text-quinta border-0 text-right loja" style="width: 84.531px;" value="R$ 0,00">
                                </h5> 
                            </div>
                        </div>
                    </div>
                    <div class="card-body faixa-lucros lucros-loja bg-faixa-footer px-4 py-2">
                        <div class="media">
                            <div class="pl-1 pr-4">
                                <img class="img-fluid mr-1" width="26" src="./images/icon_mastercard.png" alt="mastercard">
                                <small class="font-gilroy-semibold footer-valor text-quinta recebivel-credito">R$ 0,00</small>
                            </div>
                            <div class="pr-3">
                                <img class="img-fluid mr-1" width="26" src="./images/icon_maestro.png" alt="maestro">
                                <small class="font-gilroy-semibold footer-valor text-quinta recebivel-debito">R$ 0,00</small>
                            </div>
                            <div class="">
                                <img class="img-fluid" width="26" src="./images/icon_dinheiro.png" alt="dinheiro">
                                <small class="font-gilroy-semibold footer-valor text-quinta recebivel-dinheiro">R$ 0,00</small>
                            </div>
                            <div class="media-footer media-footer-valor-variacao">
                                <small class="font-gilroy-medium footer-valor-2 text-quinta porcentagem-lucro"><span class="valor-porcentagem">0%</span> | <span class="valor-lucro">R$ 0,00 </span></small>
                            </div>
                        </div>
                    </div>
                </div>      
            </div>';
        }//While

        $retorno['erro'] = '0';
        echo json_encode($retorno);
        exit();
    else:
        $retorno['erro']     = '1';
        $retorno['mensagem'] = 'Tivemos um erro interno e nao foi possivel prosseguir!';
        echo json_encode($retorno);
        exit();
    endif;
endif;

if(isset($_POST['atualiza_cardapio_itens']) && $_POST['atualiza_cardapio_itens'] == '1'):
    require_once("conexao.php");

    $retorno = array();
    $retorno['html'] = '';
    

    $sql = "SELECT * FROM cozinha_cardapio WHERE ativo='true' AND valor_ifood IS NOT NULL AND valor_whats IS NOT NULL GROUP BY cod";
    $resultado = $conexaoAdmin->prepare($sql);
    $resultado->execute();
    $contar = $resultado->rowCount();
    

    if($contar > 0):
        while($exibe = $resultado->fetch(PDO::FETCH_OBJ)){
            $retorno['variacoes'] = '';
            $retorno['nome_variacoes'] = '';
            $cod = $exibe->cod;

            $sql2 = "SELECT * FROM cozinha_cardapio WHERE ativo='true' AND cod='$cod' AND valor_ifood IS NOT NULL AND valor_whats IS NOT NULL GROUP BY variacao";
            $resultado2 = $conexaoAdmin->prepare($sql2);
            $resultado2->execute();
            $contar2 = $resultado2->rowCount();

            if($contar2 > 0):
                while($exibe2 = $resultado2->fetch(PDO::FETCH_OBJ)){
                    $retorno['variacoes'] = $retorno['variacoes'].'<div class="col-4 text-center px-1"><div class="card bg-faixa-footer rounded-lg"><small class="fs-16 font-gilroy-bold text-quinta p-2">'.$exibe2->nome_variacao.'</small></div></div>';
                    $retorno['nome_variacoes'] = $retorno['nome_variacoes'].' '.$exibe2->nome_variacao;
                }
            else:
                $retorno['erro']     = '1';
                $retorno['mensagem'] = 'Tivemos um erro interno. Tente novamente mais tarde!';
                echo json_encode($retorno);
                exit();
            endif;

            $categoria = $exibe->categoria;

            $sql3 = "SELECT categoria FROM categoria_cardapio WHERE cod='$categoria'";
            $resultado3 = $conexaoAdmin->prepare($sql3);
            $resultado3->execute();
            $contar3 = $resultado3->rowCount();

            if($contar3 > 0):
                $exibe3 = $resultado3->fetch(PDO::FETCH_OBJ);
                    
                $nome_categoria = $exibe3->categoria;
            else:
                $retorno['erro']     = '1';
                $retorno['mensagem'] = 'Tivemos um erro interno. Tente novamente mais tarde!';
                echo json_encode($retorno);
                exit();
            endif;
        
            $retorno['html'] = $retorno['html'].'<div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 cardapio-item cardapio-loja animate__animated animate__fadeIn" data-cod="'.$exibe->cod.'">
            <span class="d-none palavras-chaves">'.$exibe->cod.' '.$nome_categoria.' '.$exibe->nome.' '.$retorno['nome_variacoes'].'</span>
            <div class="card cardapio-item-body shadow cPointer">
                <div class="card-body d-flex p-0">
                    <div class="div-img-quadrado" style="background-image: url(\'upload/cardapio/'.$exibe->img.'\');"></div>
                </div>
                <div class="card-body body-conteudo bg-white p-0 border-0">
                    <div class="">
                        <div class="new-arrival-content  px-4 pt-4">
                            <small class="fs-12 font-gilroy-semibold text-uppercase">'.$nome_categoria.'</small>
                            <h4 class="fs-18 text-terceiro font-gilroy-semibold mb-0" style="margin-top: -5px;">'.$exibe->nome.'</h4>
                        </div>
                    </div>
                    <div class="new-arrival-content px-4 pt-4 pb-0">
                        <small class="fs-14 font-gilroy-semibold">TAMANHOS</small>
                        <div class="row mt-2 px-2">
                            '.$retorno['variacoes'].'
                        </div>
                    </div>
                </div>
            </div>
        </div>';
        }

        $retorno['erro'] = '0';
        echo json_encode($retorno);
        exit();
    else:
        $retorno['html'] = '<div class="col-12">
                                <div class="alert alert-light alert-dismissible fade show">
                                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                                    Não encontramos itens com valor no cardapio.
                                </div>
                            </div>';

        $retorno['erro'] = '0';
        echo json_encode($retorno);
        exit();
    endif;
endif;

if(isset($_POST['edit_valores_cardapio']) && $_POST['edit_valores_cardapio'] == '1'):
    require_once("conexao.php");

    $retorno = array();

    $parar       = (isset($_POST['parar'])) ? $_POST['parar'] : '' ;

    $cod       = (isset($_POST['cod'])) ? $_POST['cod'] : '' ;
    $variacao   = (isset($_POST['variacao'])) ? $_POST['variacao'] : '' ;
    $ifood   = (isset($_POST['ifood'])) ? $_POST['ifood'] : '' ;
    $loja    = (isset($_POST['loja'])) ? $_POST['loja'] : '' ;

    $ifood = reaisParaSql($ifood);
    $loja = reaisParaSql($loja);

    // VERIFICACAO 1 - SE TEM ALGUM CAMPO VAZIO //
    if(empty($cod) || empty($variacao) || empty($ifood) || empty($loja) || empty($parar)):
        $retorno['erro']     = '1';
        $retorno['mensagem'] = 'Tivemos um erro interno e nao foi possivel prosseguir!';
        echo json_encode($retorno);
        exit();
    endif;

    $sql1 = 'UPDATE cozinha_cardapio SET valor_ifood=:ifood, valor_whats=:loja WHERE cod=:cod AND variacao=:variacao AND ativo="true"';
    $stmt1 = $conexaoAdmin->prepare($sql1);
    $stmt1->bindParam(':cod', $cod);
    $stmt1->bindParam(':variacao', $variacao);
    $stmt1->bindParam(':ifood', $ifood);
    $stmt1->bindParam(':loja', $loja);
    $resposta1 = $stmt1->execute();

    if(!$resposta1):
        $retorno['erro']     = '1';
        $retorno['mensagem'] = 'Tivemos um erro interno e nao foi possivel atualizar as informações!';
        echo json_encode($retorno);
        exit();
    elseif($resposta1 && $parar == 'true'):
        $retorno['erro']     = '0';
        echo json_encode($retorno);
        exit();
    endif;
endif;

if(isset($_POST['monta_modal_editar']) && $_POST['monta_modal_editar'] == '1'):
    require_once("conexao.php");

    $retorno = array();
    $custos = array();
    $embalagens = array();
    $taxas = array();

    $cod  = (isset($_POST['cod'])) ? $_POST['cod'] : '' ;

    if(empty($cod)):
        $retorno['erro']     = '1';
        $retorno['mensagem'] = 'Tivemos um erro interno e nao foi possivel prosseguir!';
        echo json_encode($retorno);
        exit();
    endif;

    $sql = "SELECT * FROM cozinha_cardapio WHERE ativo='true' AND cod='$cod' AND valor_ifood IS NOT NULL AND valor_whats IS NOT NULL GROUP BY variacao";
    $resultado = $conexaoAdmin->prepare($sql);
    $resultado->execute();
    $contar = $resultado->rowCount();

    if($contar > 0): // NUMERO DE VARIACOES DESSE PRODUTO
        
        $retorno['variacoes'] = $contar;

        $sql9 = "SELECT * FROM embalagem_delivery WHERE visible='true'";
        $resultado9 = $conexaoAdmin->prepare($sql9);
        $resultado9->execute();
        $contar9 = $resultado9->rowCount();

        if($contar9 > 0):
            $custos['embalagem_delivery'] = 0;
            while($exibe9 = $resultado9->fetch(PDO::FETCH_OBJ)){
                $sql10 = "SELECT *, sum(valor_un) somaValorUn FROM (SELECT valor_un FROM compras WHERE produto='$cod_ingrediente' ORDER BY id DESC LIMIT 2) AS subquery";
                $resultado10 = $conexaoAdmin->prepare($sql10);
                $resultado10->execute();
                $contar10 = $resultado10->rowCount();

                if($contar10 > 0):
                    $sql11 = "SELECT * FROM compras WHERE produto='$cod_ingrediente' ORDER BY id DESC LIMIT 2";
                    $resultado11 = $conexaoAdmin->prepare($sql11);
                    $resultado11->execute();
                    $contar11 = $resultado11->rowCount();

                    $exibe10 = $resultado10->fetch(PDO::FETCH_OBJ);

                    $custoGrama = $exibe10->somaValorUn/$contar11;

                    $cutoIngrediente = $qtd_ingrediente*$custoGrama;

                    $custos['embalagem_delivery'] = $custos['embalagem_delivery']+$cutoIngrediente;
                else:
                    $retorno['erro']     = '1';
                    $retorno['mensagem'] = 'Tivemos um erro interno e nao foi possivel prosseguir!';
                    echo json_encode($retorno);
                    exit();
                endif;
            }
        else:
            $custos['embalagem_delivery'] = 0;
        endif;


        $sql12 = "SELECT * FROM taxas_plataforma WHERE plataforma='ifood'";
        $resultado12 = $conexaoAdmin->prepare($sql12);
        $resultado12->execute();
        $contar12 = $resultado12->rowCount();

        if($contar12 > 0):
            $exibe12 = $resultado12->fetch(PDO::FETCH_OBJ);

            $ifood_comissao = $exibe12->comissao;
            $ifood_antecipacao = $exibe12->antecipacao;
            $ifood_pagamento_online = $exibe12->visa_master_credito;

            $taxa_ifood_online = $ifood_comissao+$ifood_antecipacao+$ifood_pagamento_online;
            $taxa_ifood_loja = $ifood_comissao;
        else:
            $retorno['erro']     = '1';
            $retorno['mensagem'] = 'Tivemos um erro interno e nao foi possivel prosseguir!';
            echo json_encode($retorno);
            exit();
        endif;

        $sql13 = "SELECT * FROM taxas_plataforma WHERE plataforma='internet'";
        $resultado13 = $conexaoAdmin->prepare($sql13);
        $resultado13->execute();
        $contar13 = $resultado13->rowCount();

        if($contar13 > 0):
            $exibe13 = $resultado13->fetch(PDO::FETCH_OBJ);

            $taxa_loja_credito = $exibe13->visa_master_credito;
            $taxa_loja_debito = $exibe13->visa_master_debito;
        else:
            $retorno['erro']     = '1';
            $retorno['mensagem'] = 'Tivemos um erro interno e nao foi possivel prosseguir!';
            echo json_encode($retorno);
            exit();
        endif;

        $retorno['itens'] = '';

        while($exibe = $resultado->fetch(PDO::FETCH_OBJ)){ //FAZ WHILE POR CADA VARIACAO

            $categoria = $exibe->categoria;

            $sql5 = "SELECT * FROM categoria_cardapio WHERE cod='$categoria' AND tipo_ingrediente IS NOT NULL AND cod_ingrediente IS NOT NULL AND qtd_ingrediente IS NOT NULL";
            $resultado5 = $conexaoAdmin->prepare($sql5);
            $resultado5->execute();
            $contar5 = $resultado5->rowCount();

            if($contar5 > 0):
                $custos['embalagens'] = 0;

                while($exibe5 = $resultado5->fetch(PDO::FETCH_OBJ)){
                    $cod_ingrediente = $exibe5->cod_ingrediente;
                    $tipo_ingrediente = $exibe5->tipo_ingrediente;
                    $qtd_ingrediente = $exibe5->qtd_ingrediente;

                    // -------------------------------- //
                    // PEGA O CUSTO DE CADA INGREDIENTE //
                    // -------------------------------- //

                    if($tipo_ingrediente == 1): // CASO SEJA RECEITA/PRODUCAO
                        $sql6 = "SELECT *, sum(rendimento) rendimentoTotal, sum(valor_total) valorTotal FROM (SELECT rendimento, valor_total FROM producao WHERE cod_receita='$cod_ingrediente' ORDER BY id DESC LIMIT 2) AS subquery";
                        $resultado6 = $conexaoAdmin->prepare($sql6);
                        $resultado6->execute();
                        $contar6 = $resultado6->rowCount();

                        if($contar6 > 0):
                            $exibe6 = $resultado6->fetch(PDO::FETCH_OBJ);

                            $custoGrama = $exibe6->valorTotal/$exibe6->rendimentoTotal;

                            $cutoIngrediente = $qtd_ingrediente*$custoGrama;

                            $custos['embalagens'] = $custos['embalagens']+$cutoIngrediente;
                        else:
                            $retorno['erro']     = '1';
                            $retorno['mensagem'] = 'Tivemos um erro interno e nao foi possivel prosseguir!';
                            echo json_encode($retorno);
                            exit();
                        endif;
                    elseif($tipo_ingrediente == 2): // CASO SEJA PRODUTO

                        $sql7 = "SELECT *, sum(valor_un) somaValorUn FROM (SELECT valor_un FROM compras WHERE produto='$cod_ingrediente' ORDER BY id DESC LIMIT 2) AS subquery";
                        $resultado7 = $conexaoAdmin->prepare($sql7);
                        $resultado7->execute();
                        $contar7 = $resultado7->rowCount();

                        if($contar7 > 0):
                            $sql8 = "SELECT * FROM compras WHERE produto='$cod_ingrediente' ORDER BY id DESC LIMIT 2";
                            $resultado8 = $conexaoAdmin->prepare($sql8);
                            $resultado8->execute();
                            $contar8 = $resultado8->rowCount();

                            $exibe7 = $resultado7->fetch(PDO::FETCH_OBJ);

                            $custoGrama = $exibe7->somaValorUn/$contar8;

                            $cutoIngrediente = $qtd_ingrediente*$custoGrama;

                            $custos['embalagens'] = $custos['embalagens']+$cutoIngrediente;
                        else:
                            $retorno['erro']     = '1';
                            $retorno['mensagem'] = 'Tivemos um erro interno e nao foi possivel prosseguir!';
                            echo json_encode($retorno);
                            exit();
                        endif;
                    else:
                        $retorno['erro']     = '1';
                        $retorno['mensagem'] = 'Tivemos um erro interno e nao foi possivel prosseguir!';
                        echo json_encode($retorno);
                        exit();
                    endif;
                }
            else:
                $custos['embalagens'] = 0;
            endif;

            $variacao = $exibe->variacao;

            $custos[$variacao] = 0;

            $sql2 = "SELECT * FROM cozinha_cardapio WHERE ativo='true' AND cod='$cod' AND variacao='$variacao' AND valor_ifood IS NOT NULL AND valor_whats IS NOT NULL"; //PEGA TODOS OS INGREDIENTES PARA TAL VARIACAO
            $resultado2 = $conexaoAdmin->prepare($sql2);
            $resultado2->execute();
            $contar2 = $resultado2->rowCount();

            while($exibe2 = $resultado2->fetch(PDO::FETCH_OBJ)){
            
                $cod_ingrediente = $exibe2->cod_ingrediente;
                $tipo_ingrediente = $exibe2->tipo_ingrediente;
                $qtd_ingrediente = $exibe2->qtd_ingrediente;

                // -------------------------------- //
                // PEGA O CUSTO DE CADA INGREDIENTE //
                // -------------------------------- //

                if($tipo_ingrediente == 1): // CASO SEJA RECEITA/PRODUCAO
                    $sql3 = "SELECT *, sum(rendimento) rendimentoTotal, sum(valor_total) valorTotal FROM (SELECT rendimento, valor_total FROM producao WHERE cod_receita='$cod_ingrediente' ORDER BY id DESC LIMIT 2) AS subquery";
                    $resultado3 = $conexaoAdmin->prepare($sql3);
                    $resultado3->execute();
                    $contar3 = $resultado3->rowCount();

                    if($contar3 > 0):
                        $exibe3 = $resultado3->fetch(PDO::FETCH_OBJ);

                        $custoGrama = $exibe3->valorTotal/$exibe3->rendimentoTotal;

                        $cutoIngrediente = $qtd_ingrediente*$custoGrama;

                        $custos[$variacao] = $custos[$variacao]+$cutoIngrediente;
                    else:
                        $retorno['erro']     = '1';
                        $retorno['mensagem'] = 'Tivemos um erro interno e nao foi possivel prosseguir!';
                        echo json_encode($retorno);
                        exit();
                    endif;
                elseif($tipo_ingrediente == 2): // CASO SEJA PRODUTO

                    $sql3 = "SELECT *, sum(valor_un) somaValorUn FROM (SELECT valor_un FROM compras WHERE produto='$cod_ingrediente' ORDER BY id DESC LIMIT 2) AS subquery";
                    $resultado3 = $conexaoAdmin->prepare($sql3);
                    $resultado3->execute();
                    $contar3 = $resultado3->rowCount();

                    if($contar3 > 0):
                        $sql4 = "SELECT * FROM compras WHERE produto='$cod_ingrediente' ORDER BY id DESC LIMIT 2";
                        $resultado4 = $conexaoAdmin->prepare($sql4);
                        $resultado4->execute();
                        $contar4 = $resultado4->rowCount();

                        $exibe3 = $resultado3->fetch(PDO::FETCH_OBJ);

                        $custoGrama = $exibe3->somaValorUn/$contar4;

                        $cutoIngrediente = $qtd_ingrediente*$custoGrama;

                        $custos[$variacao] = $custos[$variacao]+$cutoIngrediente;
                    else:
                        $retorno['erro']     = '1';
                        $retorno['mensagem'] = 'Tivemos um erro interno e nao foi possivel prosseguir!';
                        echo json_encode($retorno);
                        exit();
                    endif;
                else:
                    $retorno['erro']     = '1';
                    $retorno['mensagem'] = 'Tivemos um erro interno e nao foi possivel prosseguir!';
                    echo json_encode($retorno);
                    exit();
                endif;
            }
            
            $item = $exibe->nome;
            $nome_variacao = $exibe->nome_variacao;
            $valor_ifood = $exibe->valor_ifood;
            $valor_whats = $exibe->valor_whats;

            $img = $exibe->img;

            $valor_embalagens = $custos['embalagem_delivery']+$custos['embalagens'];

            $lucro = 0-($custos[$variacao]+$valor_embalagens);

            $custo_total = $custos[$variacao]+$valor_embalagens;
            $custo_total = number_format($custo_total,2);

            $lucro_ifood = calculaRecebivel($valor_ifood, $taxa_ifood_loja)-$custo_total;
            if($lucro_ifood < 0): $lucro_ifood = 0; endif;

            $lucro_whats = $valor_whats-$custo_total;
            if($lucro_whats < 0): $lucro_whats = 0; endif;

            if($contar > 1): $margem = 'my-4'; else: $margem = 'mt-4'; endif;

            $retorno['itens'] = $retorno['itens'].'<div class="col-12 '.$margem.' variacao animate__animated animate__fadeIn" data-variacao="'.$variacao.'">
                <div class="card shadow">
                    <div class="card-header border-0 px-4 pt-3 pb-1">
                        <p class="font-gilroy-semibold text-terceiro f-17 mb-0">'.$item.'</p>
                        <p class="font-gilroy-bold text-quarta f-17 mb-0">'.$nome_variacao.'</p>
                    </div>
                    <div class="card-body faixa-valores valores-ifood bg-white px-4 py-3">
                        <div class="media">
                            <img class="img-fluid mr-2 mt-1" width="50" src="./images/logo_ifood.png" alt="ifood">
                            <div class="px-1">
                                <small class="font-gilroy-medium desc_valor">PRODUTO</small>
                                <h5 class="mb-0 font-gilroy-semibold valor_modal_cardapio text-terceiro">'.numeroParaReal($custos[$variacao]).'</h5>
                            </div>
                            <h5 class="px-1 mt-4">...........</h5>
                            <div class="px-1">
                                <small class="font-gilroy-medium desc_valor">EMBALAGENS</small>
                                <h5 class="mb-0 font-gilroy-semibold valor_modal_cardapio text-terceiro">'.numeroParaReal($valor_embalagens).'</h5>
                            </div>
                            <div class="media-footer media-footer-valor-variacao">
                                <small class="font-gilroy-medium desc_valor">VENDA</small>
                                <h5 class="mb-0" style="margin-top: -6px;">
                                    <input type="text" placeholder="R$ 0,00" data-taxa-online="'.$taxa_ifood_online.'" data-taxa-dinheiro="'.$taxa_ifood_loja.'" data-custo-total="'.$custo_total.'" class="maskMoney font-gilroy-bold input_valor_modal_cardapio text-quinta border-0 text-right ifood" style="width: auto;" value="'.numeroParaReal($valor_ifood).'">
                                </h5> 
                            </div>
                        </div>
                    </div>
                    <div class="card-body faixa-lucros lucros-ifood bg-faixa-footer px-4 py-2">
                        <div class="media">
                            <div class="pl-1 pr-4">
                                <img class="img-fluid mr-1" width="26" src="./images/icon_ifood.png" alt="ifood">
                                <small class="font-gilroy-semibold footer-valor text-quinta recebivel-ifood">'.numeroParaReal(calculaRecebivel($valor_ifood, $taxa_ifood_online)).'</small>
                            </div>
                            <div class="pr-3">
                                <img class="img-fluid mr-1" width="26" src="./images/icon_pix.png" alt="pix">
                                <small class="font-gilroy-semibold footer-valor text-quinta recebivel-pix">'.numeroParaReal(calculaRecebivel($valor_ifood, $taxa_ifood_online)).'</small>
                            </div>
                            <div class="">
                                <img class="img-fluid" width="26" src="./images/icon_dinheiro.png" alt="dinheiro">
                                <small class="font-gilroy-semibold footer-valor text-quinta recebivel-dinheiro">'.numeroParaReal(calculaRecebivel($valor_ifood, $taxa_ifood_loja)).'</small>
                            </div>
                            <div class="media-footer media-footer-valor-variacao">
                                <small class="font-gilroy-medium footer-valor-2 text-quinta porcentagem-lucro"><span class="valor-porcentagem">'.round((($lucro_ifood*100)/$custo_total), 1).'%</span> | <span class="valor-lucro">'.numeroParaReal($lucro_ifood).'</span></small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body faixa-valores valores-whatsapp bg-white px-4 py-3">
                        <div class="media">
                            <img class="img-fluid mr-2 mt-1" width="50" src="./images/logo_whatsapp.png" alt="whatsapp">
                            <div class="px-1">
                                <small class="font-gilroy-medium desc_valor">PRODUTO</small>
                                <h5 class="mb-0 font-gilroy-semibold valor_modal_cardapio text-terceiro">'.numeroParaReal($custos[$variacao]).'</h5>
                            </div>
                            <h5 class="px-1 mt-4">...........</h5>
                            <div class="px-1">
                                <small class="font-gilroy-medium desc_valor">EMBALAGENS</small>
                                <h5 class="mb-0 font-gilroy-semibold valor_modal_cardapio text-terceiro">'.numeroParaReal($valor_embalagens).'</h5>
                            </div>
                            <div class="media-footer media-footer-valor-variacao">
                                <small class="font-gilroy-medium desc_valor">VENDA</small>
                                <h5 class="mb-0" style="margin-top: -6px;">
                                    <input type="text" placeholder="R$ 0,00" data-taxa-credito="'.$taxa_loja_credito.'" data-taxa-debito="'.$taxa_loja_debito.'" data-custo-total="'.$custo_total.'" class="maskMoney font-gilroy-bold input_valor_modal_cardapio text-quinta border-0 text-right loja" style="width: auto;" value="'.numeroParaReal($valor_whats).'">
                                </h5> 
                            </div>
                        </div>
                    </div>
                    <div class="card-body faixa-lucros lucros-loja bg-faixa-footer px-4 py-2">
                        <div class="media">
                            <div class="pl-1 pr-4">
                                <img class="img-fluid mr-1" width="26" src="./images/icon_mastercard.png" alt="mastercard">
                                <small class="font-gilroy-semibold footer-valor text-quinta recebivel-credito">'.numeroParaReal(calculaRecebivel($valor_whats, $taxa_loja_credito)).'</small>
                            </div>
                            <div class="pr-3">
                                <img class="img-fluid mr-1" width="26" src="./images/icon_maestro.png" alt="maestro">
                                <small class="font-gilroy-semibold footer-valor text-quinta recebivel-debito">'.numeroParaReal(calculaRecebivel($valor_whats, $taxa_loja_debito)).'</small>
                            </div>
                            <div class="">
                                <img class="img-fluid" width="26" src="./images/icon_dinheiro.png" alt="dinheiro">
                                <small class="font-gilroy-semibold footer-valor text-quinta recebivel-dinheiro">'.numeroParaReal($valor_whats).'</small>
                            </div>
                            <div class="media-footer media-footer-valor-variacao">
                                <small class="font-gilroy-medium footer-valor-2 text-quinta porcentagem-lucro"><span class="valor-porcentagem">'.round((($lucro_whats*100)/$custo_total), 1).'%</span> | <span class="valor-lucro">'.numeroParaReal($lucro_whats).'</span></small>
                            </div>
                        </div>
                    </div>
                </div>      
            </div>';
        }//While

        $retorno['html'] = '<div class="modal-header d-flex p-0 border-0" style="margin-top: 0rem;">
        <div class="div-img-quadrado" style="background-image: url(\'upload/cardapio/'.$img.'\');"><i class="fa-solid fa-xmark fs-20 text-light editar cPointer" data-dismiss="modal"></i></div>
    </div>
    <div class="modal-body pb-0" style="margin-top: -9rem;">
        <div class="row box-variacoes px-0">'.$retorno['itens'].'
          
        </div>
    </div>
    <div class="modal-footer d-none animate__animated animate__fadeIn">
        <button type="button" class="btn btn-primary light" data-dismiss="modal">Fechar</button>
        <button type="button" class="btn btn-primary salvar" data-cod="'.$cod.'">Salvar</button>
    </div>';

        $retorno['erro'] = '0';
        echo json_encode($retorno);
        exit();
    else:
        $retorno['erro']     = '1';
        $retorno['mensagem'] = 'Tivemos um erro interno e nao foi possivel prosseguir!';
        echo json_encode($retorno);
        exit();
    endif;
endif;