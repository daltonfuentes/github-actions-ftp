<?php
ob_start();
session_start();

if(isset($_POST['consultaValorTotal']) && $_POST['consultaValorTotal'] == '1'):
    require_once("conexao.php");

    $retorno = array();
    
    $cod       = (isset($_POST['cod'])) ? $_POST['cod'] : '' ;
    $qtd    = (isset($_POST['qtd'])) ? $_POST['qtd'] : '' ;
    
    // VERIFICACAO 1 - SE TEM ALGUM CAMPO VAZIO //
    if(empty($cod) || empty($qtd)):
        $retorno['erro']     = '1';
        $retorno['mensagem'] = 'Erro interno, tente novamente mais tarde!';
        echo json_encode($retorno);
        exit();
    endif;

    $valorTotal = 0;

    // PROCURA A RECEITA QUE FOI PRODUZIDA
    $sql = "SELECT * FROM receitas WHERE cod='$cod'";
    try{
        $resultado = $conexaoAdmin->prepare($sql);
        $resultado->execute();
        $contar = $resultado->rowCount();

        if($contar > 0){
            // CALCULA O VALOR DA PRODUÇÃO DENTRO DO WHILE COM TODOS OS PRODUTOS DA RECEITA
            while($exibe = $resultado->fetch(PDO::FETCH_OBJ)){
                $produto = $exibe->produto;
                $qtd_necessario = $exibe->qtd;
                $qtd_utilizada = $qtd_necessario*$qtd;

                // Pega valor e quantidade da ultima compra de $produto
                $sql2 = "SELECT * FROM compras WHERE produto='$produto' ORDER BY dataId DESC LIMIT 1";
                $resultado2 = $conexaoAdmin->prepare($sql2);	
                $resultado2->execute();
                $contar2 = $resultado2->rowCount();

                if($contar2 > 0):
                    $exibe2 = $resultado2->fetch(PDO::FETCH_OBJ);

                    $qtd_ultima_compra = $exibe2->qtd;
                    $valor_ultima_compra = $exibe2->valor_un;
                else:
                    // Acusa erro e para tudo, pois nao pode continuar sem ter feito a compra do produto;
                    $retorno['erro']     = '1';
                    $retorno['mensagem'] = 'Existem produtos nessa receita que não foram comprados ainda!';
                    echo json_encode($retorno);
                    exit();
                endif;

                // Pega valor e quantidade da PENultima compra de $produto
                $sql3 = "SELECT * FROM compras WHERE produto='$produto' ORDER BY dataId DESC LIMIT 1, 1";
                $resultado3 = $conexaoAdmin->prepare($sql3);	
                $resultado3->execute();
                $contar3 = $resultado3->rowCount();

                if($contar3 > 0):
                    $exibe3 = $resultado3->fetch(PDO::FETCH_OBJ);

                    $valor_penultima_compra = $exibe3->valor_un;
                else:
                    $valor_penultima_compra = 0;
                    //unset($valor_penultima_compra);
                    // Pode nao haver uma penultima compra.
                endif;

                // Pega o estoque atual de $produto
                $sql4 = "SELECT * FROM estoque WHERE cod='$produto'";
                $resultado4 = $conexaoAdmin->prepare($sql4);	
                $resultado4->execute();
                $contar4 = $resultado4->rowCount();

                if($contar4 >0):
                    $exibe4 = $resultado4->fetch(PDO::FETCH_OBJ);

                    $estoqueAtual = $exibe4->estoque_atual;
                    $nomeProduto = $exibe4->produto;
                else:
                    // Impossivel prosseguir sem que o produto NAO esteja cadastrado em estoque
                    $retorno['erro']     = '1';
                    $retorno['mensagem'] = 'Erro interno, tente novamente mais tarde!';
                    echo json_encode($retorno);
                    exit();
                endif;

                if($estoqueAtual < $qtd_utilizada):
                    // Não tem estoque suficiente para produzir essa receita
                    $retorno['erro']     = '1';
                    $retorno['mensagem'] = 'O estoque de '.$nomeProduto.' não é suficiente para essa produção.';
                    echo json_encode($retorno);
                    exit();
                endif;

                if($estoqueAtual > $qtd_ultima_compra):
                // Estou utilizando produtos da penultima compra tambem - Faço verificações abaixo para saber se é tudo da penultima compra ou tem produtos da ultima tambem!
                    // $d= Produtos da penultima compra ainda em estoque;
                    $d = $estoqueAtual-$qtd_ultima_compra;

                    if($d >= $qtd_utilizada):
                    // Estou utilizando produtos da penultima compra APENAS.
                        $valorTotal = $valorTotal+($qtd_utilizada*$valor_penultima_compra);
                    else:
                    // Estou utilizando produtos da penultima e ultima compra.
                        // $pn= Produtos novos
                        // $pa= Produtos antigos
                        $pa = $qtd_utilizada-$d;
                        $pn = $d;
                        $s1 = $pa*$valor_penultima_compra;
                        $s2 = $pn*$valor_ultima_compra;
                        $valorTotal = $valorTotal+($s1+$s2);
                    endif;
                else:
                // Estou utilizando produtos da ultima compra.
                $valorTotal = $valorTotal+($qtd_utilizada*$valor_ultima_compra);
                endif;
            }//While
            $retorno['erro']     = '0';
            $retorno['valor'] = numeroParaReal($valorTotal);
            echo json_encode($retorno);
            exit();
        }else{
        //Não acha a recheita com o respectivo $cod
        $retorno['erro']     = '1';
        $retorno['mensagem'] = 'Erro interno, tente novamente mais tarde!';
        echo json_encode($retorno);
        exit();
        }
    }catch(PDOException $erro){
        $retorno['erro']     = '1';
        $retorno['mensagem'] = $erro;
        echo json_encode($retorno);
        exit();
    }
endif;

if(isset($_POST['cadastraProducao']) && $_POST['cadastraProducao'] == '1'):
    require_once("conexao.php");

    $retorno = array();
    
    $cod       = (isset($_POST['cod'])) ? $_POST['cod'] : '' ;
    $data       = (isset($_POST['data'])) ? $_POST['data'] : '' ;
    $qtd    = (isset($_POST['qtd'])) ? $_POST['qtd'] : '' ;
    $rendimento       = (isset($_POST['rendimento'])) ? $_POST['rendimento'] : '' ;
    
    // VERIFICACAO 1 - SE TEM ALGUM CAMPO VAZIO //
    if(empty($cod) || empty($data) || empty($qtd) || empty($rendimento)):
        $retorno['erro']     = '1';
        $retorno['mensagem'] = 'Erro interno, tente novamente mais tarde!';
        echo json_encode($retorno);
        exit();
    endif;

    $valorTotal = 0;

    $sql = "SELECT * FROM receitas WHERE cod='$cod'";
    try{
        $resultado = $conexaoAdmin->prepare($sql);
        $resultado->execute();
        $contar = $resultado->rowCount();

        if($contar > 0){

            while($exibe = $resultado->fetch(PDO::FETCH_OBJ)){
                $produto = $exibe->produto;
                $qtd_necessario = $exibe->qtd;
                $qtd_utilizada = $qtd_necessario*$qtd;

                // Pega valor e quantidade da ultima compra de $produto
                $sql2 = "SELECT * FROM compras WHERE produto='$produto' ORDER BY dataId DESC LIMIT 1";
                $resultado2 = $conexaoAdmin->prepare($sql2);	
                $resultado2->execute();
                $contar2 = $resultado2->rowCount();

                if($contar2 > 0):
                    $exibe2 = $resultado2->fetch(PDO::FETCH_OBJ);

                    $qtd_ultima_compra = $exibe2->qtd;
                    $valor_ultima_compra = $exibe2->valor_un;
                else:
                    // Acusa erro e para tudo, pois nao pode continuar sem ter feito a compra do produto;
                    $retorno['erro']     = '1';
                    $retorno['mensagem'] = 'Existem produtos nessa receita que não foram comprados ainda!';
                    echo json_encode($retorno);
                    exit();
                endif;

                // Pega valor e quantidade da PENultima compra de $produto
                $sql3 = "SELECT * FROM compras WHERE produto='$produto' ORDER BY dataId DESC LIMIT 1, 1";
                $resultado3 = $conexaoAdmin->prepare($sql3);	
                $resultado3->execute();
                $contar3 = $resultado3->rowCount();

                if($contar3 > 0):
                    $exibe3 = $resultado3->fetch(PDO::FETCH_OBJ);

                    $valor_penultima_compra = $exibe3->valor_un;
                else:
                    $valor_penultima_compra = 0;
                    //unset($valor_penultima_compra);
                    // Pode nao haver uma penultima compra.
                endif;

                // Pega o estoque atual de $produto
                $sql4 = "SELECT * FROM estoque WHERE cod='$produto'";
                $resultado4 = $conexaoAdmin->prepare($sql4);	
                $resultado4->execute();
                $contar4 = $resultado4->rowCount();

                if($contar4 >0):
                    $exibe4 = $resultado4->fetch(PDO::FETCH_OBJ);

                    $estoqueAtual = $exibe4->estoque_atual;
                    $nomeProduto = $exibe4->produto;
                else:
                    // Impossivel prosseguir sem que o produto NAO esteja cadastrado em estoque
                    $retorno['erro']     = '1';
                    $retorno['mensagem'] = 'Erro interno, tente novamente mais tarde!';
                    echo json_encode($retorno);
                    exit();
                endif;

                if($estoqueAtual < $qtd_utilizada):
                    // Não tem estoque suficiente para produzir essa receita
                    $retorno['erro']     = '1';
                    $retorno['mensagem'] = 'O estoque de '.$nomeProduto.' não é suficiente para essa produção.';
                    echo json_encode($retorno);
                    exit();
                endif;

                if($estoqueAtual > $qtd_ultima_compra):
                // Estou utilizando produtos da penultima compra tambem - Faço verificações abaixo para saber se é tudo da penultima compra ou tem produtos da ultima tambem!
                    // $d= Produtos da penultima compra ainda em estoque;
                    $d = $estoqueAtual-$qtd_ultima_compra;

                    if($d >= $qtd_utilizada):
                    // Estou utilizando produtos da penultima compra APENAS.
                        $valorTotal = $valorTotal+($qtd_utilizada*$valor_penultima_compra);
                    else:
                    // Estou utilizando produtos da penultima e ultima compra.
                        // $pn= Produtos novos
                        // $pa= Produtos antigos
                        $pa = $qtd_utilizada-$d;
                        $pn = $d;
                        $s1 = $pa*$valor_penultima_compra;
                        $s2 = $pn*$valor_ultima_compra;
                        $valorTotal = $valorTotal+($s1+$s2);
                    endif;
                else:
                // Estou utilizando produtos da ultima compra.
                $valorTotal = $valorTotal+($qtd_utilizada*$valor_ultima_compra);
                endif;
            }//While
        }else{
        //Não acha a recheita com o respectivo $cod
        $retorno['erro']     = '1';
        $retorno['mensagem'] = 'Erro interno, tente novamente mais tarde!';
        echo json_encode($retorno);
        exit();
        }
    }catch(PDOException $erro){
        $retorno['erro']     = '1';
        $retorno['mensagem'] = $erro;
        echo json_encode($retorno);
        exit();
    }

    $sql = 'INSERT INTO producao (cod_receita, qtd_producao, rendimento, data_producao, valor_total) VALUES (:cod_receita, :qtd_producao, :rendimento, :data_producao, :valor_total)';
    $stmt = $conexaoAdmin->prepare($sql);
    $stmt->bindParam(':cod_receita', $cod);
    $stmt->bindParam(':qtd_producao', $qtd);
    $stmt->bindParam(':rendimento', $rendimento);
    $stmt->bindParam(':data_producao', $data);
    $stmt->bindParam(':valor_total', $valorTotal);
    $resposta = $stmt->execute();

    if( !$resposta ):
        $retorno['erro']     = '1';
        $retorno['mensagem'] = 'Tivemos um erro interneo e nao foi possivel finalizar o cadastro!';
        echo json_encode($retorno);
        exit();
    else:
        $retorno['erro']     = '0';
        $retorno['mensagem'] = 'Producão cadastrada com sucesso!';
        echo json_encode($retorno);
        exit();
    endif;
endif;

if(isset($_POST['atualizaProducao']) && $_POST['atualizaProducao'] == '1'):
    require_once("conexao.php");
    $response = array();

    $sql = 'SELECT * FROM producao ORDER BY id DESC';
    $resultado = $conexaoAdmin->prepare($sql);
    $resultado->execute();
    $contar = $resultado->rowCount();

    if($contar > 0):
        while($exibeO = $resultado->fetch(PDO::FETCH_OBJ)){
            $id = $exibeO->id;
            $receita = $exibeO->cod_receita;
            $qtd = $exibeO->qtd_producao;
            $rendimento = $exibeO->rendimento;
            $data = $exibeO->data_producao;
            $valor = numeroParaReal($exibeO->valor_total);

            $sql2 = "SELECT * FROM receitas WHERE cod='$receita' LIMIT 1";
            $resultado2 = $conexaoAdmin->prepare($sql2);
            $resultado2->execute();
            $contar2 = $resultado2->rowCount();

            if($contar2 > 0):
                $exibe2 = $resultado2->fetch(PDO::FETCH_OBJ);
                $nomeReceita = $exibe2->receita;
                $img = $exibe2->img;
            else:
                $response[] = '<li class="producao_item animate__fadeInUp animate__animated">
                <div class="timeline-panel py-4 mb-0">
                    <div class="media-body text-center">
                        <p>O servidor encontrou um erro interno. Se o erro persistir, por favor entre em contato com o WebMaster.</p>
                    </div>
                </div>
            </li>';
                echo json_encode($response);
                exit();
            endif;

            $dia = substr($data, 0, 2);
            $mes = substr($data, 3, -5);
            $ano = substr($data, -4);

            $newData = $dia.' '.numeroParaMes($mes).' '.$ano;

            $newId = str_pad($id, 4, "0", STR_PAD_LEFT);

            $response[] = '<li class="producao_item animate__fadeInUp animate__animated" data-id="'.$id.'" data-receita="'.$nomeReceita.'" data-date="'.$data.'" data-qtd="'.$qtd.'" data-rendimento="'.$rendimento.'" data-valor="'.$valor.'">
            <div class="timeline-panel py-4 mb-0">
                <div class="mr-3 edit">
                    <img src="upload/receitas/'.$img.'" alt="" width="100" height="100" class="rounded cPointer edit" style="object-fit: cover;">
                </div>
                <div class="media-body cPointer edit">
                    <h4 class="mb-0 filter">'.$nomeReceita.' <span class="font-w600 text-primary">x'.$qtd.'</span></h4>
                    <ul>
                        <li style=""><small class="text-muted fs-15 font-w500">#'.$newId.'</small></li>
                        <li><small class="text-dark fs-15 font-w500">Rendimento: '.$rendimento.'g</small></li>
                        <li style="margin-top: -5px;"><small class="text-dark fs-15 font-w500">'.$newData.'</small></li>
                    </ul>
                </div>
                <div class="media-footer align-self-center ml-auto d-block align-items-center d-sm-flex">
                    <h3 class="mb-0 font-w600 mt-1 cPointer edit">
                        <a>'.$valor.'</a>
                    </h3>
                    <div class="ml-4 d-flex">
                        <a class="btn btn-outline-secondary light sharp delete"><i class="fa fa-trash"></i></a>
                    </div>
                </div>
            </div>
        </li>';
        }//While
    else:
        $response[] =   '<li class="producao_item animate__fadeInUp animate__animated">
                            <div class="timeline-panel py-4 mb-0">
                                <div class="media-body text-center">
                                    <p>Nenhuma produção cadastrada.</p>
                                </div>
                            </div>
                        </li>';
    endif;

    echo json_encode($response);
    exit();
endif;

if(isset($_POST['consultaQtdProducao']) && $_POST['consultaQtdProducao'] == '1'):
    require_once("conexao.php");
    $retorno = array();    

    $sql = 'SELECT * FROM producao';
    $resultado = $conexaoAdmin->prepare($sql);
    $resultado->execute();
    $contar = $resultado->rowCount();

    $pages = ceil($contar/10);
    $retorno['pages'] = $pages;
    
    echo json_encode($retorno);
    exit();
endif;

if(isset($_POST['atualizaProducaoCondicao']) && $_POST['atualizaProducaoCondicao'] == '1'):
    require_once("conexao.php");
    $response = array();

    $condicao = (isset($_POST['condicao'])) ? $_POST['condicao'] : '' ;

    $sql = 'SELECT * FROM producao ORDER BY id DESC';
    $resultado = $conexaoAdmin->prepare($sql);
    $resultado->execute();
    $contar = $resultado->rowCount();

    if($contar > 0):
        while($exibeO = $resultado->fetch(PDO::FETCH_OBJ)){
            $id = $exibeO->id;
            $receita = $exibeO->cod_receita;
            $qtd = $exibeO->qtd_producao;
            $rendimento = $exibeO->rendimento;
            $data = $exibeO->data_producao;
            $valor = numeroParaReal($exibeO->valor_total);

            $sql2 = "SELECT * FROM receitas WHERE cod='$receita' && LOWER(receita) LIKE '%$condicao%' LIMIT 1";
            $resultado2 = $conexaoAdmin->prepare($sql2);
            $resultado2->execute();
            $contar2 = $resultado2->rowCount();

            if($contar2 > 0):
                $exibe2 = $resultado2->fetch(PDO::FETCH_OBJ);
                $nomeReceita = $exibe2->receita;
                $img = $exibe2->img;
            else:
                continue;
            endif;

            $dia = substr($data, 0, 2);
            $mes = substr($data, 3, -5);
            $ano = substr($data, -4);

            $newData = $dia.' '.numeroParaMes($mes).' '.$ano;

            $newId = str_pad($id, 4, "0", STR_PAD_LEFT);

            $response[] = '<li class="producao_item animate__fadeInUp animate__animated" data-id="'.$id.'" data-receita="'.$nomeReceita.'" data-date="'.$data.'" data-qtd="'.$qtd.'" data-rendimento="'.$rendimento.'" data-valor="'.$valor.'">
            <div class="timeline-panel py-4 mb-0">
                <div class="mr-3 edit">
                    <img src="upload/receitas/'.$img.'" alt="" width="100" height="100" class="rounded cPointer edit" style="object-fit: cover;">
                </div>
                <div class="media-body cPointer edit">
                    <h4 class="mb-0 filter">'.$nomeReceita.' <span class="font-w600 text-primary">x'.$qtd.'</span></h4>
                    <ul>
                        <li style=""><small class="text-muted fs-15 font-w500">#'.$newId.'</small></li>
                        <li><small class="text-dark fs-15 font-w500">Rendimento: '.$rendimento.'g</small></li>
                        <li style="margin-top: -5px;"><small class="text-dark fs-15 font-w500">'.$newData.'</small></li>
                    </ul>
                </div>
                <div class="media-footer align-self-center ml-auto d-block align-items-center d-sm-flex">
                    <h3 class="mb-0 font-w600 mt-1 cPointer edit">
                        <a>'.$valor.'</a>
                    </h3>
                    <div class="ml-4 d-flex">
                        <a class="btn btn-outline-secondary light sharp delete"><i class="fa fa-trash"></i></a>
                    </div>
                </div>
            </div>
        </li>';
        }//While

        if(empty($response)):
            $response[] = '<li class="producao_item animate__fadeInUp animate__animated">
                                <div class="timeline-panel py-4 mb-0">
                                    <div class="media-body text-center">
                                        <p>Não encontramos produções com essa busca.</p>
                                    </div>
                                </div>
                            </li>';
        endif;
    else:
        $response[] =   '<li class="producao_item animate__fadeInUp animate__animated">
                            <div class="timeline-panel py-4 mb-0">
                                <div class="media-body text-center">
                                    <p>Nenhuma produção cadastrada.</p>
                                </div>
                            </div>
                        </li>';
    endif;

    echo json_encode($response);
    exit();
endif;

if(isset($_POST['consultaQtdProducaoCondicao']) && $_POST['consultaQtdProducaoCondicao'] == '1'):
    require_once("conexao.php");
    $retorno = array();

    $condicao = (isset($_POST['condicao'])) ? $_POST['condicao'] : '' ;

    $sql = 'SELECT * FROM producao';
    $resultado = $conexaoAdmin->prepare($sql);
    $resultado->execute();
    $contar = $resultado->rowCount();

    $n = 0;

    if($contar > 0):
        while($exibeO = $resultado->fetch(PDO::FETCH_OBJ)){
            $id = $exibeO->id;
            $receita = $exibeO->cod_receita;
            $qtd = $exibeO->qtd_producao;
            $rendimento = $exibeO->rendimento;
            $data = $exibeO->data_producao;
            $valor = numeroParaReal($exibeO->valor_total);

            $sql2 = "SELECT * FROM receitas WHERE cod='$receita' && LOWER(receita) LIKE '%$condicao%' LIMIT 1";
            $resultado2 = $conexaoAdmin->prepare($sql2);
            $resultado2->execute();
            $contar2 = $resultado2->rowCount();

            if($contar2 > 0):
                $n++;
            else:
                continue;
            endif;
        }
    endif;

    $pages = ceil($n/10);
    $retorno['pages'] = $pages;
    
    echo json_encode($retorno);
    exit();
endif;


if(isset($_POST['refreshRanking']) && $_POST['refreshRanking'] == '1'):
    require_once("conexao.php");
    $response = array();

    $sql = 'SELECT *, sum(qtd_producao) soma FROM producao GROUP BY cod_receita ORDER BY soma DESC';
    $resultado = $conexaoAdmin->prepare($sql);
    $resultado->execute();
    $contar = $resultado->rowCount();

    if($contar > 0):
        $n = 0;
        while($exibe = $resultado->fetch(PDO::FETCH_OBJ)){
            if($n > 5):
                continue;
            endif;

            $qtd = $exibe->soma;
            $cod_receita = $exibe->cod_receita;

            $sql2 = "SELECT * FROM receitas WHERE cod='$cod_receita' LIMIT 1";
            $resultado2 = $conexaoAdmin->prepare($sql2);
            $resultado2->execute();
            $contar2 = $resultado2->rowCount();

            if($contar2 > 0):
                $exibe2 = $resultado2->fetch(PDO::FETCH_OBJ);
                $nomeReceita = $exibe2->receita;
                $img = $exibe2->img;
            else:
                $response[] =  '<div class="pb-3 mb-3 text-center">
                                    <h2 class="text-dark fs-15 mb-0 text-center">O servidor encontrou um erro interno. Se o erro persistir, por favor entre em contato com o WebMaster.</h2>
                                </div>';
                echo json_encode($response);
                exit();
            endif;

            $ranking = $n+1;

            if($contar >= 5):
                if($ranking == 5):
                    $border = '';
                else:
                    $border = 'border-bottom';
                endif;
            else:
                if($ranking == $contar):
                    $border = '';
                else:
                    $border = 'border-bottom';
                endif;
            endif;

            $response[] =  '<div class="d-flex pb-3 mb-3 '.$border.' tr-row align-items-center">
                                <span class="num">#'.$ranking.'</span>
                                <div class="mr-auto pr-3">
                                    <h2 class="text-black fs-16 mb-0">'.$nomeReceita.'</h2>
                                    <span class="text-muted fs-14 font-w600">Produções: x'.$qtd.'</span>
                                </div>
                                <div class="mr-3">
                                    <img src="upload/receitas/'.$img.'" alt="" width="60" height="60" class="rounded" style="object-fit: cover;">
                                </div>
                            </div>';

            $n++;
        }
        if(empty($response)):
            $response[] =  '<div class="pb-3 mb-3 text-center">
                                <h2 class="text-dark fs-15 mb-0 text-center">O servidor encontrou um erro interno. Se o erro persistir, por favor entre em contato com o WebMaster.</h2>
                            </div>';
        endif;
    else:
        $response[] =  '<div class="pb-3 mb-3 text-center">
                            <h2 class="text-dark fs-15 mb-0 text-center">Nenhuma produção cadastrada.</h2>
                        </div>';
    endif;

    echo json_encode($response);
    exit();
endif;

if(isset($_POST['editaProducao']) && $_POST['editaProducao'] == '1'):
    require_once("conexao.php");

    $retorno = array();
    
    $cod            = (isset($_POST['cod'])) ? $_POST['cod'] : '' ;
    $data           = (isset($_POST['data'])) ? $_POST['data'] : '' ;
    $rendimento     = (isset($_POST['rendimento'])) ? $_POST['rendimento'] : '' ;
    
    // VERIFICACAO 1 - SE TEM ALGUM CAMPO VAZIO //
    if(empty($cod) || empty($data) || empty($rendimento)):
        $retorno['erro']     = '1';
        $retorno['mensagem'] = 'Erro interno, tente novamente mais tarde!';
        echo json_encode($retorno);
        exit();
    endif;

    //////////////////////////////////
    //////////////////////////////////
    //////////////////////////////////
    //////////////////////////////////
    //////////////////////////////////
    //////////////////////////////////
    //////////////////////////////////
    // FUTURAMENTE PRECISA VERIFICAR SE É POSSIVEL ALTERAR A DATA E RENDIMENTO DESSA PROCUÇÃO. CASO ALGUM ITEM DO CARDAPIO JA TENHA UTILIZADO ESSA PRODUÇÃO, A MESMA NÃO PODE MAIS SER ALTERADA PELA DASHBOARD
    //////////////////////////////////
    //////////////////////////////////
    //////////////////////////////////
    //////////////////////////////////
    //////////////////////////////////
    //////////////////////////////////
    //////////////////////////////////

    // ATUALIZA DADOS //
    $sql1 = 'UPDATE producao SET data_producao=:data_producao, rendimento=:rendimento WHERE id=:id';
    $stmt1 = $conexaoAdmin->prepare($sql1);
    $stmt1->bindParam(':id', $cod);
    $stmt1->bindParam(':data_producao', $data);
    $stmt1->bindParam(':rendimento', $rendimento);
    $resposta1 = $stmt1->execute();

    if( !$resposta1 ):
        $retorno['erro']     = '1';
        $retorno['mensagem'] = 'Tivemos um erro interno e nao foi possivel salvar as alterações!';
        echo json_encode($retorno);
        exit();
    else:
        $retorno['erro']     = '0';
        $retorno['mensagem'] = 'As informações foram alteradas corretamente!';
        echo json_encode($retorno);
        exit();
    endif;
endif;

if(isset($_POST['deletaProducao']) && $_POST['deletaProducao'] == '1'):
    require_once("conexao.php");

    $retorno = array();

    $id = (isset($_POST['id'])) ? $_POST['id'] : '' ;

    // VERIFICACAO 1 - SE PASSOU TUDO //
    if(empty($id)):
        $retorno['erro']     = '1';
        $retorno['mensagem'] = 'Tivemos um erro interno e não foi possivel excluir essa produção.';
        echo json_encode($retorno);
        exit();
    endif;

    //////////////////////////////////
    //////////////////////////////////
    //////////////////////////////////
    //////////////////////////////////
    //////////////////////////////////
    //////////////////////////////////
    //////////////////////////////////
    // FUTURAMENTE PRECISA VERIFICAR SE É POSSIVEL DELETAR ESSA PROCUÇÃO. CASO ALGUM ITEM DO CARDAPIO JA TENHA UTILIZADO ESSA PRODUÇÃO, A MESMA NÃO PODE MAIS APAGADA PELA DASHBOARD
    //////////////////////////////////
    //////////////////////////////////
    //////////////////////////////////
    //////////////////////////////////
    //////////////////////////////////
    //////////////////////////////////
    //////////////////////////////////

    $sql1 = 'DELETE FROM producao WHERE id = :id';
    $stmt1 = $conexaoAdmin->prepare($sql1);
    $stmt1->bindParam(':id', $id);
    $resposta1 = $stmt1->execute();

    if( !$resposta1 ):
        $retorno['erro']     = '1';
        $retorno['mensagem'] = 'Tivemos um erro interno e não foi possivel excluir essa produção.';
        echo json_encode($retorno);
        exit();
    else:
        $retorno['erro']     = '0';
        $retorno['mensagem'] = 'Produção excluida!';
        echo json_encode($retorno);
        exit();
    endif;
endif;